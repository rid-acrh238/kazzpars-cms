<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categoryBySlug = fn (string $slug) => Category::where('slug', $slug)->first();

        $welcomeBody = <<<'HTML'
<p>
    Halo dan selamat datang! Ini adalah sudut kecilku
    di internet — sebuah homepage bergaya retro yang
    sengaja kubuat senyaman mungkin.
</p>
<p>
    Di sini aku akan menulis tentang perjalanan belajar,
    project yang sedang dikerjakan, dan hal-hal teknis
    yang sedang kupelajari. Kalau kamu tersesat sampai
    ke sini, selamat membaca!
</p>
<p>
    Oh ya, jangan lupa tanda tangani buku tamu di
    bawah — setiap tanda tangan sangat berarti. ✦
</p>
HTML;

        $aboutBody = <<<'HTML'
<div class="about-profile">
    <div class="about-photo-frame">
        <img src="/img/photo.jpeg" alt="Foto Kazzpars" class="about-photo">
        <p class="photo-caption">★ THAT'S ME! ★</p>
    </div>
    <div class="about-text">
        <p>
            Aku Kazzpars, mahasiswa Informatika yang
            tertarik pada teknologi, Linux, pemrograman,
            dan pengembangan web.
        </p>
        <p>
            Saat ini aku sedang belajar membuat berbagai
            proyek dan mengeksplorasi dunia teknologi.
            Selamat datang di blog-ku!
        </p>
        <p>
            Jika ada pertanyaan, saran, atau sekadar
            ingin menyapa, jangan ragu untuk
            menghubungiku melalui widget kontak di
            sebelah kanan.
        </p>
        <p class="about-signature">~ Kazzpars</p>
    </div>
</div>
HTML;

        $projectsBody = <<<'HTML'
<article class="project-card">
    <h3>SmartRoad WebGIS</h3>
    <p>
        Proyek WebGIS untuk pemetaan jalan dan trotoar
        rusak berbasis crowdsourcing.
    </p>
</article>
<article class="project-card">
    <h3>Bendahara Management</h3>
    <p>
        Aplikasi pengelolaan data dan transaksi
        bendahara berbasis web.
    </p>
</article>
HTML;

        $skillsBody = <<<'HTML'
<p>✦ TECHNOLOGIES I WORK WITH ✦</p>
<div class="skills-badges">
    <span class="skill-badge badge-html">
        <i class="fa-brands fa-html5" aria-hidden="true"></i> HTML
    </span>
    <span class="skill-badge badge-css">
        <i class="fa-brands fa-css3-alt" aria-hidden="true"></i> CSS
    </span>
    <span class="skill-badge badge-js">
        <i class="fa-brands fa-js" aria-hidden="true"></i> JavaScript
    </span>
    <span class="skill-badge badge-php">
        <i class="fa-brands fa-php" aria-hidden="true"></i> PHP
    </span>
    <span class="skill-badge badge-laravel">
        <i class="fa-brands fa-laravel" aria-hidden="true"></i> Laravel
    </span>
    <span class="skill-badge badge-python">
        <i class="fa-brands fa-python" aria-hidden="true"></i> Python
    </span>
    <span class="skill-badge badge-mysql">
        <i class="fa-solid fa-database" aria-hidden="true"></i> MySQL
    </span>
</div>
HTML;

        $guestbookBody = <<<'HTML'
<p>✦ VISITORS, LEAVE YOUR MARK HERE! ✦</p>
<div class="guestbook-frame">
    <iframe
        src="https://kazzpars.atabook.org/"
        title="Kazzpars Guestbook"
        loading="lazy"
        width="100%"
        height="650"
        style="border: none;"
    ></iframe>
</div>
HTML;

        $posts = [
            [
                'slug' => 'welcome',
                'title' => 'WELCOME TO KAZZPARS PORTAL!',
                'category' => 'personal',
                'date' => '2026-11-23 10:00:00',
                'body' => $welcomeBody,
                'excerpt' => 'Sudut kecilku di internet — selamat datang di blog retro-ku!',
            ],
            [
                'slug' => 'about',
                'title' => 'HELLO, INTERNET PEOPLE!',
                'category' => 'about',
                'date' => '2026-11-22 09:00:00',
                'body' => $aboutBody,
                'excerpt' => 'Kenalan dulu: aku Kazzpars, mahasiswa Informatika.',
            ],
            [
                'slug' => 'projects',
                'title' => '★ MY PROJECTS ★',
                'category' => 'projects',
                'date' => '2026-11-20 15:00:00',
                'body' => $projectsBody,
                'excerpt' => 'SmartRoad WebGIS dan Bendahara Management.',
            ],
            [
                'slug' => 'skills',
                'title' => '★ MY SKILLS ★',
                'category' => 'skills',
                'date' => '2026-11-18 11:00:00',
                'body' => $skillsBody,
                'excerpt' => 'Teknologi yang biasa kupakai sehari-hari.',
            ],
            [
                'slug' => 'guestbook',
                'title' => 'SIGN MY GUESTBOOK!',
                'category' => 'guestbook',
                'date' => '2026-11-15 13:00:00',
                'body' => $guestbookBody,
                'excerpt' => 'Tinggalkan tanda tanganmu di buku tamu!',
            ],
        ];

        foreach ($posts as $data) {
            Post::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $categoryBySlug($data['category'])?->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'body' => $data['body'],
                    'status' => 'published',
                    'published_at' => $data['date'],
                ],
            );
        }
    }
}
