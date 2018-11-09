<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index() {

        $user = \Auth::user();
        $statusOpened = Status::where('action', __('messages.ticket_action_create'))->first();
        $statusClosed = Status::where('action', __('messages.ticket_action_close'))->first();

        $tickets = [
            "openeds" => [
                "byMe" => [],
                "toMe" => [],
                "observeds" => [],
                "orphans" => []
            ],
            "closeds" => [
                "mine" => [],
                "observeds" => [],
                "orphans" => []
            ],
            "expireds" => [
                "myTickets" => [],
                "observedTickets" => [],
            ]
        ];


        $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
            ->leftJoin('observers', function($join) use ($user) {
                $join->on('observers.ticket_id', 'tickets.id')
                    ->on('observers.user_id', '=', \DB::raw($user->id) );
            })
            ->where(function($query) use ($user) {
                $query->where('observers.user_id', $user->id)
                    ->orWhere('tickets.user_id', $user->id)
                    ->orWhere('tickets.agent_user_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->whereNull('tickets.agent_user_id')
                    ->where('tickets.department_id', $user->department_id);
            })
            ->orderBy('tickets.id', 'ASC')
            ->get();


        if (\Auth::user()->is_admin) {
            $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
                ->leftJoin('observers', function($join) use ($user) {
                    $join->on('observers.ticket_id', 'tickets.id')
                        ->on('observers.user_id', '=', \DB::raw($user->id) );
                })
                ->orderBy('tickets.id', 'ASC')
                ->get();
        }

        foreach( $ticketsCollection as $ticket ) {
            if ( $ticket->status_id == $statusOpened->id ) {
                if ( $ticket->user_id == $user->id ) {
                    $tickets["openeds"]["byMe"][] = $ticket;
                } else if ( $ticket->agent_user_id == $user->id ) {
                    $tickets["openeds"]["toMe"][] = $ticket;
                } else if ( $ticket->observer_id == $user->id ) {
                    $tickets["openeds"]["observeds"][] = $ticket;
                } else {
                    $tickets["openeds"]["toMe"][] = $ticket;
                }
            } else if ( $ticket->status_id == $statusClosed->id ) {
                if ( $ticket->user_id == $user->id || $ticket->agent_user_id == $user->id ) {
                    $tickets["closeds"]["mine"][] = $ticket;
                } else if ( $ticket->observer_id == $user->id ) {
                    $tickets["closeds"]["observeds"][] = $ticket;
                } else {
                    $tickets["closeds"]["orphans"][] = $ticket;
                }
            }
        }


        return view('dashboard.index')
            ->with('tickets', $tickets)
            ->with('statusOpened', $statusOpened);
    }

}
