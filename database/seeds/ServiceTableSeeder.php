<?php

use App\Salon;
use App\Services;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->delete();

        $salon = Salon::where('name', 'Test Salon')->first();

        Services::create([
            'name' => 'Test Service',
            'price' => '11 $',
            'salon_id' => $salon->id
        ]);
    }
}
