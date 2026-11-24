<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AuthAndCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'kazzpars0@gmail.com',
        ]);
    }

    private function makePost(array $overrides = []): Post
    {
        $category = Category::firstOrCreate(
            ['slug' => 'personal'],
            ['name' => 'Personal'],
        );

        return Post::create([
            'category_id' => $category->id,
            'title' => 'Post Uji',
            'slug' => 'post-uji',
            'excerpt' => 'Ringkasan uji.',
            'body' => '<p>Isi uji.</p>',
            'status' => 'published',
            'published_at' => now(),
            ...$overrides,
        ]);
    }

    // ---------- AUTH ----------

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/posts')->assertRedirect('/login');
    }

    public function test_login_page_displays(): void
    {
        $this->get('/login')->assertOk()->assertSee('ADMIN LOGIN');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $this->admin();

        $this->post('/login', [
            'email' => 'kazzpars0@gmail.com',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->admin();

        $this->post('/login', [
            'email' => 'kazzpars0@gmail.com',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        $this->admin();

        foreach (range(1, 5) as $i) {
            $this->post('/login', [
                'email' => 'kazzpars0@gmail.com',
                'password' => 'salah',
            ]);
        }

        $this->post('/login', [
            'email' => 'kazzpars0@gmail.com',
            'password' => 'password',
        ])->assertStatus(429);
    }

    // ---------- PUBLIK ----------

    public function test_home_displays_published_posts(): void
    {
        $this->makePost(['title' => 'JUDUL TERPUBLIKASI']);

        $this->get('/')->assertOk()->assertSee('JUDUL TERPUBLIKASI');
    }

    public function test_home_hides_draft_posts(): void
    {
        $this->makePost([
            'title' => 'RAHASIA DRAFT',
            'slug' => 'rahasia-draft',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->get('/')->assertOk()->assertDontSee('RAHASIA DRAFT');
    }

    public function test_single_post_page_shows_post(): void
    {
        $this->makePost();

        $this->get('/post/post-uji')->assertOk()->assertSee('Post Uji');
    }

    public function test_single_post_404_for_draft(): void
    {
        $this->makePost([
            'slug' => 'draft-ini',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->get('/post/draft-ini')->assertNotFound();
    }

    // ---------- CRUD POST ----------

    public function test_admin_can_create_post(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/posts', [
            'title' => 'Post Baru',
            'category_id' => null,
            'excerpt' => 'Ringkas.',
            'body' => '<p>Baru.</p>',
            'status' => 'published',
        ])->assertRedirect();

        $this->assertDatabaseHas('posts', ['title' => 'Post Baru']);
        $this->assertDatabaseHas('posts', ['status' => 'published']);
    }

    public function test_post_title_is_required(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/posts', [
            'title' => '',
            'body' => '<p>x</p>',
        ])->assertSessionHasErrors('title');
    }

    public function test_admin_can_update_post(): void
    {
        $post = $this->makePost();
        $this->actingAs($this->admin());

        $this->put("/admin/posts/{$post->id}", [
            'title' => 'Post Diubah',
            'category_id' => $post->category_id,
            'excerpt' => 'Diubah.',
            'body' => '<p>Diubah.</p>',
            'status' => 'published',
        ])->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Post Diubah',
        ]);
    }

    public function test_admin_can_delete_post(): void
    {
        $post = $this->makePost();
        $this->actingAs($this->admin());

        $this->delete("/admin/posts/{$post->id}")->assertRedirect('/admin/posts');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_can_toggle_status(): void
    {
        $post = $this->makePost();
        $this->actingAs($this->admin());

        $this->patch("/admin/posts/{$post->id}/toggle-status")->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'draft',
        ]);
    }

    // ---------- CRUD KATEGORI ----------

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/categories', ['name' => 'Teknologi'])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['name' => 'Teknologi']);
    }

    public function test_category_with_posts_cannot_be_deleted(): void
    {
        $post = $this->makePost();
        $this->actingAs($this->admin());

        $this->delete("/admin/categories/{$post->category_id}")
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['id' => $post->category_id]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $category = Category::create(['name' => 'Sementara', 'slug' => 'sementara']);
        $this->actingAs($this->admin());

        $this->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_logout_invalidates_session(): void
    {
        $this->actingAs($this->admin());

        $this->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }

    // ---------- COVER IMAGE ----------

    private function fakeImage(): \Illuminate\Http\UploadedFile
    {
        return \Illuminate\Http\UploadedFile::fake()->image('cover.jpg', 640, 480);
    }

    public function test_admin_can_upload_cover_while_creating_post(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/admin/posts', [
            'title' => 'Post Dengan Cover',
            'body' => '<p>Isi.</p>',
            'status' => 'published',
            'cover' => $this->fakeImage(),
        ]);

        $response->assertRedirect();

        $post = Post::where('title', 'Post Dengan Cover')->firstOrFail();

        $this->assertNotNull($post->cover_image, 'cover_image harus tersimpan');
        $this->assertStringStartsWith('uploads/', $post->cover_image);
        $this->assertFileExists(public_path($post->cover_image));

        // Bersihkan file tes supaya folder nggak numpuk.
        @unlink(public_path($post->cover_image));
    }

    public function test_invalid_cover_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $fake = \Illuminate\Http\UploadedFile::fake()->create('dokumen.txt', 100, 'text/plain');

        $this->post('/admin/posts', [
            'title' => 'Post Cover Rusak',
            'body' => '<p>Isi.</p>',
            'cover' => $fake,
        ])->assertSessionHasErrors('cover');

        $this->assertDatabaseMissing('posts', ['title' => 'Post Cover Rusak']);
    }

    public function test_oversized_cover_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $fake = \Illuminate\Http\UploadedFile::fake()->create('besar.jpg', 3072, 'image/jpeg');

        $this->post('/admin/posts', [
            'title' => 'Post Cover Besar',
            'body' => '<p>Isi.</p>',
            'cover' => $fake,
        ])->assertSessionHasErrors('cover');
    }

    public function test_replacing_cover_deletes_old_file(): void
    {
        $post = $this->makePost();
        $this->actingAs($this->admin());

        // Upload cover pertama.
        $this->post('/admin/posts', [
            'title' => 'Ganti Cover',
            'body' => '<p>Isi.</p>',
            'cover' => $this->fakeImage(),
        ]);

        $created = Post::where('title', 'Ganti Cover')->firstOrFail();
        $firstPath = $created->cover_image;
        $this->assertFileExists(public_path($firstPath));

        // Ganti dengan cover baru.
        $this->put("/admin/posts/{$created->id}", [
            'title' => 'Ganti Cover',
            'body' => '<p>Isi.</p>',
            'status' => 'published',
            'cover' => $this->fakeImage(),
        ]);

        $created->refresh();

        $this->assertNotSame($firstPath, $created->cover_image, 'cover harus berganti');
        $this->assertFileDoesNotExist(public_path($firstPath), 'file lama harus terhapus');
        $this->assertFileExists(public_path($created->cover_image));

        @unlink(public_path($created->cover_image));
    }

    public function test_remove_cover_checkbox_deletes_file(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/posts', [
            'title' => 'Hapus Cover',
            'body' => '<p>Isi.</p>',
            'cover' => $this->fakeImage(),
        ]);

        $post = Post::where('title', 'Hapus Cover')->firstOrFail();
        $path = $post->cover_image;
        $this->assertFileExists(public_path($path));

        $this->put("/admin/posts/{$post->id}", [
            'title' => 'Hapus Cover',
            'body' => '<p>Isi.</p>',
            'status' => 'published',
            'remove_cover' => 1,
        ]);

        $post->refresh();

        $this->assertNull($post->cover_image, 'kolom harus kosong');
        $this->assertFileDoesNotExist(public_path($path), 'file harus terhapus');
    }

    public function test_cover_shows_on_public_pages(): void
    {
        $post = $this->makePost([
            'cover_image' => 'uploads/tes-cover.jpg',
        ]);

        // Buat file palsu biar gambar "ada".
        File::ensureDirectoryExists(public_path('uploads'));
        File::put(public_path('uploads/tes-cover.jpg'), 'dummy');

        $this->get('/')
            ->assertOk()
            ->assertSee('uploads/tes-cover.jpg', false);

        $this->get('/post/' . $post->slug)
            ->assertOk()
            ->assertSee('uploads/tes-cover.jpg', false);

        File::delete(public_path('uploads/tes-cover.jpg'));
    }

    public function test_post_without_cover_has_no_img_tag(): void
    {
        $this->makePost();

        $this->get('/')
            ->assertOk()
            ->assertDontSee('class="post-cover"', false);
    }

    // ---------- LINK PUBLIK (regresi bug 404) ----------

    public function test_post_links_use_slug_not_id(): void
    {
        $post = $this->makePost([
            'title' => 'Judul Unik Regresi',
            'slug'  => 'judul-unik-regresi',
        ]);

        // Route-nya {slug} — link harus berisi slug, BUKAN id.
        $this->get('/')
            ->assertOk()
            ->assertSee('/post/judul-unik-regresi', false)
            ->assertDontSee('/post/' . $post->id . '"', false);
    }

    public function test_every_title_link_on_home_resolves(): void
    {
        $slugs = ['satu-dua', 'tiga-empat', 'lima-enam'];

        foreach ($slugs as $slug) {
            $this->makePost(['slug' => $slug, 'title' => 'T ' . $slug]);
        }

        foreach ($slugs as $slug) {
            $this->get('/post/' . $slug)->assertOk();
        }
    }

    public function test_cover_appears_after_title_not_before(): void
    {
        $this->makePost(['cover_image' => 'uploads/urutan-test.jpg']);

        File::ensureDirectoryExists(public_path('uploads'));
        File::put(public_path('uploads/urutan-test.jpg'), 'dummy');

        $html = $this->get('/')->assertOk()->getContent();

        $titlePos = strpos($html, 'post-title');
        $coverPos = strpos($html, 'post-cover');
        $bodyPos = strpos($html, 'post-body');

        $this->assertNotFalse($titlePos, 'post-title harus ada');
        $this->assertNotFalse($coverPos, 'post-cover harus ada');
        $this->assertGreaterThan($titlePos, $coverPos, 'cover HARUS setelah judul');
        $this->assertLessThan($bodyPos, $coverPos, 'cover HARUS sebelum isi post');

        File::delete(public_path('uploads/urutan-test.jpg'));
    }
}
