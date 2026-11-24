<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /** Halaman tunggal: /post/{slug} — hanya post terbit. */
    public function show(Request $request, string $slug): View
    {
        $post = Post::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('post', compact('post'));
    }
}
