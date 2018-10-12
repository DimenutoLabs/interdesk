<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('status')->insert(
            [
                'name' => 'Criado',
                'action' => 'Criar',
                'default' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => 'Aberto',
                'action' => 'Abrir',
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => 'Atribuido',
                'action' => 'Atribuir',
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => 'Espera',
                'action' => 'Suspender',
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => 'Fechado',
                'action' => 'Fechar',
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => 'Cancelado',
                'action' => 'Cancelar',
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

    }
}
