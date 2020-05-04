<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'      => 'admin',
            'email'         => 'admin@kai.com',
            'user_type'     => 'admin',
            'password'      => bcrypt('password') //password
        ]);
    }
}
