<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);
        $this->call(PriorSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(ExtraFieldsSeederTypes::class);
        $this->call(ExtraFieldsSeeder::class);
    }
}
