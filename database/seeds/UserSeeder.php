<?php

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
                'name' => 'Luiz Eduardo Campos Soares',
                'email' => 'l.eduardosoares@gmail.com',
                'password' => '$2y$10$LJHEy0cfkyj5KEpHVhrdiefWSfuQ4x1e81MEoQvglEY3WEU1fYcWK',
            ]
        );
    }
}
