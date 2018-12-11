<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Status;
use App\Models\Ticket;
use App\User;
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
                "rating" => null
        ]);

        $this->error("Expiring " . $dueOpeneds . " tickets");

        foreach ( $tickets->cursor() as $ticket ) {

            $notification = new Notification();
            $notification->ticket_id = $ticket->id;
            $notification->user_id = $ticket->user_id;
            $notification->read = false;
            $notification->message = "Um ticket criado por você expirou.";
            $notification->url = "/ticket/" . $ticket->id . "/edit";
            $notification->save();

            if ( $ticket->agent_user_id ) {
                $notification = new Notification();
                $notification->ticket_id = $ticket->id;
                $notification->user_id = $ticket->agent_user_id;
                $notification->read = false;
                $notification->message = "Um ticket cujo você é responsável expirou.";
                $notification->url = "/ticket/" . $ticket->id . "/edit";
                $notification->save();
            } else {
                $department = $ticket->department_id;
                $departmentUsers = User::where('department_id', $department)->get();
                foreach( $departmentUsers as $departmentUser ) {
                    if ( $departmentUser->id == $ticket->user_id ) {
                        continue;
                    }

                    $notification = new Notification();
                    $notification->ticket_id = $ticket->id;
                    $notification->user_id = $departmentUser->id;
                    $notification->read = false;
                    $notification->message = "Um ticket aberto para seu departamento expirou.";
                    $notification->url = "/ticket/" . $ticket->id . "/edit";
                    $notification->save();
                }
            }
        }
    }
}
