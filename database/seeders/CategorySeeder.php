<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Personal', 'slug' => 'personal'],
            ['name' => 'About', 'slug' => 'about'],
            ['name' => 'Projects', 'slug' => 'projects'],
            ['name' => 'Skills', 'slug' => 'skills'],
            ['name' => 'Guestbook', 'slug' => 'guestbook'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
