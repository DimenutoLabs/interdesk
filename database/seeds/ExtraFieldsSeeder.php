<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\Models\UserExtraFieldType as FieldType;

class ExtraFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $typeString = FieldType::where('name', 'String')->first();
        $typeFile = FieldType::where('name', 'File')->first();

        DB::table('user_extra_fields')->insert(
            [
                'name' => 'CPF',
                'user_extra_field_type_id' => $typeString->id,
                'max_length' => 14,
                'mask' => '000.000.000-00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_fields')->insert(
            [
                'name' => 'Nascimento',
                'user_extra_field_type_id' => $typeString->id,
                'max_length' => 10,
                'mask' => '00/00/0000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('user_extra_fields')->insert(
            [
                'name' => 'Avatar',
                'user_extra_field_type_id' => $typeFile->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
