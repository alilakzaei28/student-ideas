<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ساخت دسته‌بندی‌های پیش‌فرض
        $categories = [
            ['name' => 'آموزشی', 'slug' => 'education'],
            ['name' => 'رفاهی', 'slug' => 'welfare'],
            ['name' => 'تکنولوژی', 'slug' => 'tech'],
            ['name' => 'ورزشی', 'slug' => 'sports'],
            ['name' => 'فضای سبز', 'slug' => 'environment'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}