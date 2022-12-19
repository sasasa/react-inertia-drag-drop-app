<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'test',
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('test'),
        ]);
        User::factory()->count(10)->create();
        // $response = Http::get("https://jsonplaceholder.typicode.com/users");
        // // dd(json_decode($response->getBody()->getContents()));
        // collect(json_decode($response->getBody()->getContents()))->each(function($user){
        //     User::create([
        //         'name' => $user->name,
        //         'username' => $user->username,
        //         'email' => $user->email,
        //         'password' => Hash::make('test'),
        //     ]);
        // });
    }
}
