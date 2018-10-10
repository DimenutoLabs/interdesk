<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priors')->insert(
            [
                'name' => 'Alta',
                'color' => '#D42449',
            ]
        );
        DB::table('priors')->insert(
            [
                'name' => 'Média',
                'color' => '#B4A7BE',
            ]
        );
        DB::table('priors')->insert(
            [
                'name' => 'Baixa',
                'color' => '#CCECF2',
            ]
        );
    }
}
