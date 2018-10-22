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

        $openTickets = Ticket::select('tickets.*')
            ->leftJoin('observers', 'observers.ticket_id', 'tickets.id')
            ->where('status_id', $statusOpened->id )
            ->where(function($query) use ($user) {
                $query->where('observers.user_id', $user->id)
                    ->orWhere('tickets.user_id', $user->id)
                    ->orWhere('tickets.agent_user_id', $user->id);
            })
            ->get();

        $closedTickets = Ticket::select('tickets.*')
            ->leftJoin('observers', 'observers.ticket_id', 'tickets.id')
            ->where('status_id', $statusClosed->id )
            ->where(function($query) use ($user) {
                $query->where('observers.user_id', $user->id)
                    ->orWhere('tickets.user_id', $user->id)
                    ->orWhere('tickets.agent_user_id', $user->id);
            })
            ->get();

        if (\Auth::user()->is_admin) {
            $openTickets = Ticket::where('status_id', $statusOpened->id )
                            ->get();
            $closedTickets = Ticket::where('status_id', $statusClosed->id )
                ->get();
        }


        return view('dashboard.index')
            ->with('openTickets', $openTickets)
            ->with('closedTickets', $closedTickets);
    }

}
