<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Widget sidebar kanan (kategori + arsip) selalu tersedia
        // di halaman mana pun tanpa perlu diteruskan tiap controller.
        View::composer('partials.widgets', function ($view) {
            $view->with([
                'widgetCategories' => Category::withCount('posts')
                    ->orderBy('name')
                    ->get(),
                'widgetArchive' => Post::published()
                    ->get()
                    ->groupBy(fn (Post $post) => $post->published_at->format('F Y')),
            ]);
        });
    }
}
