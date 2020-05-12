<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SalonTableSeeder::class);
        $this->call(StylistTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        $this->call(StylePostTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ArticleTableSeeder::class);
    }
}
