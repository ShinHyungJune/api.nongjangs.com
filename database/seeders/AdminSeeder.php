<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('admin', 1)->first()->update([
            'password' => \Hash::make("damduk--1996@")
        ]);
    }
}
