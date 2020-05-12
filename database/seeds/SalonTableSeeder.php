<?php

use App\Salon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salons')->delete();

        Salon::create([
            'name' => 'Test Salon',
            'address' => 'test address',
            'images' => 'test.jpg',
            'latitude' => 123123,
            'longitude' => 123123,
        ]);
    }
}
