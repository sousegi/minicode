<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('blogs')->truncate();

        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->sentence(6);

            $photoUrl = 'https://picsum.photos/800/600';

            $imageContents = file_get_contents($photoUrl);

            $filename = 'blogs/' . Str::random(40) . '.jpg';

            Storage::disk('public')->put($filename, $imageContents);


            Blog::create([
                'title'        => $title,
                'slug'         => Str::slug($title) . '-' . $i,
                'content'      => $faker->paragraphs(5, true),
                'published'    => $faker->boolean(),
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'image'        => $filename,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
