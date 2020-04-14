<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtraFieldsSeederTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'String',
                'cast' => 'string',
                'is_string' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'Integer',
                'cast' => 'int',
                'is_numeric' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'Float',
                'cast' => 'float',
                'is_float' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'Boolean',
                'cast' => 'boolean',
                'is_boolean' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'File',
                'cast' => 'string',
                'is_file' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'Date',
                'cast' => 'Date',
                'is_date' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'Time',
                'cast' => 'Time',
                'is_time' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_field_types')->insert(
            [
                'name' => 'DateTime',
                'cast' => 'DateTime',
                'is_date' => true,
                'is_time' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
