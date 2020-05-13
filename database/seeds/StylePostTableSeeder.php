<?php

use App\StylePost;
use App\Stylist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StylePostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('style_posts')->delete();

        $stylist = Stylist::orderBy('id')->first();

        StylePost::create([
            'description' => 'test description',
            'media' => 'test media',
            'tags' => ' test',
            'brand_name' => 'test brand name',
            'style_name' => 'test style name',
            'color' => 'test brand name',
            'stylist_id' => $stylist->id,
        ]);
    }
}
