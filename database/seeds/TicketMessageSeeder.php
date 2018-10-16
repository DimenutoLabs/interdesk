<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TicketMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('ticket_log_messages')->insert(
            [
                'message' => 'log_ticket_created',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('ticket_log_messages')->insert(
            [
                'message' => 'log_ticket_new_message',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('ticket_log_messages')->insert(
            [
                'message' => 'log_ticket_upload_file',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('ticket_log_messages')->insert(
            [
                'message' => 'log_ticket_status_change',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
