<?php

use Carbon\Carbon;
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
                'background' => '#D42449',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('priors')->insert(
            [
                'name' => 'Média',
                'background' => '#B4A7BE',
                'color' => '#FFF',
                'default' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('priors')->insert(
            [
                'name' => 'Baixa',
                'background' => '#CCECF2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
