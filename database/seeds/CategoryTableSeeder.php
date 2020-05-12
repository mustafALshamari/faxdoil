<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();

        Category::create(['name' => 'Hair', 'image' => 'image']);
        Category::create(['name' => 'Nail', 'image' => 'image']);
        Category::create(['name' => 'Column', 'image' => 'image']);
    }
}
