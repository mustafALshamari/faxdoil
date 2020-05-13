<?php

use App\Article;
use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('articles')->delete();
        DB::table('article_category')->delete();

        $firstCategory = Category::where('name', 'Hair')->first();
        $secondCategory = Category::where('name', 'Nail')->first();
        $thirdCategory = Category::where('name', 'Column')->first();

        $firstArticle = Article::create([
            'title' => $faker->name(),
            'content' => $faker->text(),
            'image'  => 'image.jpg',
            'likes_quantity' => 1,
            'views_quantity' => 1,
        ]);

        $secondArticle = Article::create([
            'title' => $faker->name(),
            'content' => $faker->text(),
            'image'  => 'image.jpg',
            'likes_quantity' => 1,
            'views_quantity' => 1,
        ]);

        $thirdArticle = Article::create([
            'title' => $faker->name(),
            'content' => $faker->text(),
            'image'  => 'image.jpg',
            'likes_quantity' => 1,
            'views_quantity' => 1,
        ]);

        $firstArticle->categories()->attach($firstCategory);
        $secondArticle->categories()->attach($secondCategory);
        $thirdArticle->categories()->attach($thirdCategory);
    }
}
