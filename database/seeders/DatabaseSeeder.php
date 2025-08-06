<?php

namespace Database\Seeders;

use App\Models\Tables;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory(10)->create();
//        User::factory()->create([
//            'name' => 'Ibrahim',
//            'email' => 'ichom88@gmail.com',
//            'role' => 'admin',
//            'password' => bcrypt('password')]);

        Tables::factory(10)->create();
    }
}
