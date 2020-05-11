<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'username'      => 'admin',
            'email'         => 'admin@kai.com',
            'user_type'     => 'admin',
            'password'      => Hash::make('password') //password
        ]);
    }
}
