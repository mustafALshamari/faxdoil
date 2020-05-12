<?php

use App\Salon;
use App\Stylist;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stylists')->delete();

        $user = User::where('username', 'admin')->first();
        $salon = Salon::where('name', 'Test Salon')->first();

        Stylist::create([
           'user_id' => $user->id,
           'salon_id' => $salon->id
        ]);
    }
}
