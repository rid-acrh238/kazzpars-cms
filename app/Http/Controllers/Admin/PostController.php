<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /** Daftar post + filter status + search. */
    public function index(Request $request): View
    {
        $query = Post::with('category')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return view('admin.posts.index', [
            'posts' => $query->paginate(10)->withQueryString(),
            'currentStatus' => $request->input('status'),
            'currentSearch' => $request->input('q'),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // Simpan cover (kalau ada) sebelum post dibuat, supaya slug-nya bisa
        // dipakai sebagai dasar nama file.
        $slug = $this->uniqueSlug($data['title']);
        $coverPath = $this->storeCover($request, $slug);

        $post = Post::create([
            ...$data,
            'slug' => $slug,
            'cover_image' => $coverPath,
            'status' => $request->input('status') === 'published' ? 'published' : 'draft',
            'published_at' => $request->input('status') === 'published' ? now() : null,
        ]);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post berhasil dibuat!');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request, $post);

        $status = $request->input('status') === 'published' ? 'published' : 'draft';

        // published_at hanya diisi saat pertama kali diterbitkan.
        $publishedAt = $post->published_at;
        if ($status === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        // ---- Cover: ganti / hapus / biarkan ----
        $coverPath = $post->cover_image;

        if ($request->boolean('remove_cover')) {
            // Centang "hapus cover" → buang file lama + kosongkan kolom.
            $this->deleteCover($coverPath);
            $coverPath = null;
        } elseif ($request->hasFile('cover')) {
            // Ada file baru → ganti, file lama dibuang supaya nggak numpuk.
            $this->deleteCover($coverPath);
            $coverPath = $this->storeCover($request, $post->slug);
        }

        $post->update([
            ...$data,
            'status' => $status,
            'published_at' => $publishedAt,
            'cover_image' => $coverPath,
        ]);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post berhasil diperbarui!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        // Hapus file cover jika ada.
        $this->deleteCover($post->cover_image);

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post berhasil dihapus!');
    }

    /** Toggle draft ↔ published. */
    public function toggleStatus(Post $post): RedirectResponse
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft']);
            $message = 'Post dipindahkan ke draft.';
        } else {
            $post->update([
                'status' => 'published',
                'published_at' => $post->published_at ?? now(),
            ]);
            $message = 'Post diterbitkan!';
        }

        return back()->with('status', $message);
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'excerpt' => ['nullable', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'status' => ['nullable', 'in:draft,published'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'remove_cover' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Pindahkan gambar cover ke public/uploads/, kembalikan path-nya.
     * Nama file digenerate server (slug + waktu) — bukan nama asli user.
     */
    private function storeCover(Request $request, string $slug): ?string
    {
        if (! $request->hasFile('cover')) {
            return null;
        }

        $file = $request->file('cover');
        $name = $slug . '-' . time() . '-' . Str::random(4)
            . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $name);

        return 'uploads/' . $name;
    }

    /** Hapus file cover dari disk (aman kalau file sudah tidak ada). */
    private function deleteCover(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            File::delete(public_path($path));
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $i = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
