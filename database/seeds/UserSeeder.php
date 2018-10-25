<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name' => 'John Doe',
                'email' => 'firstuser@gmail.com',
                'password' => \Hash::make('123456'),
                'is_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('users')->insert(
            [
                'name' => 'Charles Albert',
                'email' => 'seconduser@gmail.com',
                'password' => \Hash::make('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('users')->insert(
            [
                'name' => 'Gregg Swaer',
                'email' => 'thirduser@gmail.com',
                'password' => \Hash::make('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('users')->insert(
            [
                'name' => 'Eduardo Soares',
                'email' => 'fourthuser@gmail.com',
                'password' => \Hash::make('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
