<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class ExportSite extends Command
{
    protected $signature = 'site:export
        {--url= : URL dasar tujuan, contoh: https://rid-acrh238.github.io/webpage}
        {--output= : Folder output relatif terhadap root project (default: docs)}
        {--force : Izinkan timpa folder output yang bukan milik export ini}';

    protected $description = 'Generate situs statis (HTML/CSS/JS) dari data CMS — untuk GitHub Pages / Neocities';

    /** Berkas dari public/ yang disalin apa adanya. */
    private array $files = [
        'style.css',
        'favicon.svg',
        'favicon.ico',
        'music.js',
        'dateTime.js',
        'retro.js',
        'nocturne.html',
        'nocturne.css',
        'nocturne.js',
        '404.html',
    ];

    /** Folder dari public/ yang disalin apa adanya. */
    private array $directories = ['img', 'music', 'uploads'];

    /** Penanda supaya command tahu folder output memang hasil export dia. */
    private const MARKER = '.kazzpars-export';

    /** URL tujuan, dipakai saat merewrite URL root-relative. */
    private string $url = '';

    public function handle(): int
    {
        // ---------------------------------------------------------------
        // 1. URL tujuan
        // ---------------------------------------------------------------
        $url = rtrim((string) ($this->option('url') ?: config('app.url')), '/');

        if ($url === '' || str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $this->error('URL tujuan masih kosong/localhost: ' . $url);
            $this->line('Pakai: php artisan site:export --url=https://<username>.github.io/<repo>');
            return self::FAILURE;
        }

        // ---------------------------------------------------------------
        // 2. Folder output (dengan penjagaan supaya nggak salah hapus)
        // ---------------------------------------------------------------
        $output = (string) ($this->option('output') ?: 'docs');
        $outputPath = rtrim(base_path($output), '\\/');

        if ($outputPath === rtrim(base_path(), '\\/') || str_starts_with($outputPath, public_path())) {
            $this->error('Folder output tidak aman: ' . $outputPath);
            return self::FAILURE;
        }

        if (File::isDirectory($outputPath)) {
            $ownedByUs = File::exists($outputPath . '/' . self::MARKER);

            if (! $ownedByUs && ! $this->option('force')) {
                $this->error('Folder "' . $output . '" sudah ada dan bukan hasil export tool ini.');
                $this->line('Tambahkan --force kalau yakin mau ditimpa.');
                return self::FAILURE;
            }

            File::deleteDirectory($outputPath);
        }

        File::ensureDirectoryExists($outputPath);

        // ---------------------------------------------------------------
        // 3. Kunci semua URL (asset + route) ke URL tujuan
        // ---------------------------------------------------------------
        $this->url = $url;
        URL::forceRootUrl($url);
        if (str_starts_with($url, 'https://')) {
            URL::forceScheme('https');
        }
        config(['app.url' => $url]);

        // ---------------------------------------------------------------
        // 4. Render tiap halaman lewat HTTP kernel (persis seperti aslinya)
        // ---------------------------------------------------------------
        $kernel = $this->laravel->make(Kernel::class);

        $posts = Post::published()
            ->with('category')
            ->orderByDesc('published_at')
            ->get();

        $pages = ['/' => 'index.html'];

        foreach ($posts as $post) {
            $pages['/post/' . $post->slug] = 'post/' . $post->slug . '/index.html';
        }

        $rendered = 0;
        $failed = [];

        foreach ($pages as $path => $file) {
            $html = $this->render($kernel, $path);

            if ($html === null) {
                $failed[] = $path;
                continue;
            }

            $this->writeFile($outputPath . '/' . $file, $html);
            $rendered++;
        }

        // ---------------------------------------------------------------
        // 5. Salin aset statis
        // ---------------------------------------------------------------
        $copied = 0;

        foreach ($this->files as $file) {
            $from = public_path($file);
            if (! File::exists($from)) {
                continue;
            }

            File::copy($from, $outputPath . '/' . $file);
            $copied++;
        }

        foreach ($this->directories as $dir) {
            $from = public_path($dir);
            if (! File::isDirectory($from)) {
                continue;
            }

            File::copyDirectory($from, $outputPath . '/' . $dir);
            $copied++;
        }

        // 404 untuk Neocities (GitHub Pages pakai 404.html)
        $notFound = $outputPath . '/404.html';
        if (File::exists($notFound)) {
            File::copy($notFound, $outputPath . '/not_found.html');
            $copied++;
        }

        // ---------------------------------------------------------------
        // 6. sitemap.xml + robots.txt
        // ---------------------------------------------------------------
        $this->writeFile($outputPath . '/sitemap.xml', $this->sitemap($url, $pages, array_keys($pages)));
        $this->writeFile(
            $outputPath . '/robots.txt',
            "User-agent: *\nDisallow:\n\nSitemap: {$url}/sitemap.xml\n"
        );

        // ---------------------------------------------------------------
        // 7. Penanda + ringkasan
        // ---------------------------------------------------------------
        $this->writeFile(
            $outputPath . '/' . self::MARKER,
            'Hasil export php artisan site:export — jangan edit manual, jalankan export ulang.'
        );

        $size = $this->folderSize($outputPath);

        $this->newLine();
        $this->info('Export selesai → ' . $outputPath);
        $this->table(
            ['Item', 'Jumlah'],
            [
                ['Halaman dirender', $rendered],
                ['Aset disalin', $copied],
                ['Post terbit', $posts->count()],
                ['Ukuran total', $size],
            ]
        );

        if ($failed !== []) {
            $this->warn('Gagal render: ' . implode(', ', $failed));
            return self::FAILURE;
        }

        $this->line('Base URL : ' . $url);
        $this->line('Coba lokal: php -S 127.0.0.1:8088 -t ' . $output);

        return self::SUCCESS;
    }

    /** Jalankan request palsu lewat kernel — dapet HTML persis seperti produksi. */
    private function render(Kernel $kernel, string $path): ?string
    {
        $request = Request::create($path, 'GET');
        $response = $kernel->handle($request);

        $status = $response->getStatusCode();
        $content = $response->getContent();
        $kernel->terminate($request, $response);

        if ($status !== 200) {
            $this->warn("{$path} → HTTP {$status}");
            return null;
        }

        return $this->absoluteUrls($content);
    }

    /**
     * Ubah URL root-relative (src="/img/x") jadi absolut sesuai base URL tujuan.
     *
     * Isi post disimpan apa adanya di database — path seperti /img/photo.jpeg
     * benar di localhost, tapi salah kalau situs di-host di sub-path
     * (contoh: https://user.github.io/webpage/img/photo.jpeg).
     */
    private function absoluteUrls(string $html): string
    {
        $html = preg_replace(
            '/\b(src|href|content)="(\/(?!\/)[^"]*)"/',
            '$1="' . $this->url . '$2"',
            $html
        );

        return $this->trailingSlashes($html);
    }

    /**
     * Tambahkan trailing slash pada link internal yang menunjuk "folder".
     *
     * GitHub Pages melayani /post/welcome/ (200) sedangkan /post/welcome
     * di-redirect 301. Link langsung pakai slash supaya tidak ada redirect
     * dan Google tidak menganggapnya dua URL berbeda.
     */
    private function trailingSlashes(string $html): string
    {
        $base = preg_quote($this->url, '/');

        return preg_replace_callback(
            '/\b(href|src)="(' . $base . '[^"]*)"/',
            function (array $m): string {
                $url = $m[2];

                // Pisahkan fragment (#anchor) dulu
                $fragment = '';
                if (($hash = strpos($url, '#')) !== false) {
                    $fragment = substr($url, $hash);
                    $url = substr($url, 0, $hash);
                }

                // URL yang sudah punya slash atau menunjuk berkas (ada titik di
                // segmen terakhir, mis. style.css) dibiarkan apa adanya.
                $last = substr($url, strrpos($url, '/') ?: 0);
                if (str_ends_with($url, '/') || str_contains($last, '.')) {
                    return $m[0];
                }

                return $m[1] . '="' . $url . '/' . $fragment . '"';
            },
            $html
        );
    }

    private function sitemap(string $url, array $pages, array $paths): string
    {
        $items = '';

        foreach ($paths as $path) {
            // GitHub Pages melayani folder dengan trailing slash — pakai URL
            // yang sama supaya Google tidak menganggapnya duplikat.
            $loc = $path === '/' ? $url . '/' : $url . $path . '/';
            $items .= "  <url>\n    <loc>{$loc}</loc>\n  </url>\n";
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$items}</urlset>

XML;
    }

    private function writeFile(string $path, string $contents): void
    {
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $contents);
    }

    private function folderSize(string $path): string
    {
        $bytes = 0;

        foreach (File::allFiles($path, true) as $file) {
            $bytes += $file->getSize();
        }

        return round($bytes / 1024 / 1024, 1) . ' MB';
    }
}
