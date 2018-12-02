<?php

namespace App\Console\Commands;

use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Console\Command;

class invalidateDueTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invalidade due tickets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("Searching all due tickets");

        $tickets = Ticket::where('status_id', Status::where('name', __('messages.ticket_status_created'))->first()->id )
                            ->where('limit_date', '<', date('Y-m-d'));


        $dueOpeneds = $tickets->count();

        $this->warn($dueOpeneds . " Tickets with due date");

        $tickets->update([
                "status_id" => Status::where('name', __('messages.ticket_status_expired'))->first()->id,
                "rating" => 0
        ]);

        $this->error("Expiring " . $dueOpeneds . " tickets");
    }
}
