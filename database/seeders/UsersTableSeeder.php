<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $user =   User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('user'),
        ]);
      $user->createToken($user->email)->plainTextToken;

      $user2 = User::create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'password' => bcrypt('user'),
        ]);
      $user2->createToken($user->email)->plainTextToken;
    }
}
