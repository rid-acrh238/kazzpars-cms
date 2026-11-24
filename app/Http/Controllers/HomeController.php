<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $posts = Post::published()
            ->with('category')
            ->orderByDesc('published_at')
            ->get();

        return view('home', compact('posts'));
    }
}
