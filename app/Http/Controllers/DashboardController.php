<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalPosts' => Post::count(),
            'publishedPosts' => Post::published()->count(),
            'draftPosts' => Post::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'latestPosts' => Post::with('category')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
