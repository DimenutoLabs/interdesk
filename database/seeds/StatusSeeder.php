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
                'name' => __('messages.ticket_status_created'),
                'action' => __('messages.ticket_action_create'),
                'default' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => __('messages.ticket_status_on_hold'),
                'action' => __('messages.ticket_action_hold'),
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => __('messages.ticket_status_closed'),
                'action' => __('messages.ticket_action_close'),
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('status')->insert(
            [
                'name' => __('messages.ticket_status_expired'),
                'action' => __('messages.ticket_status_expired'),
                'default' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

    }
}
