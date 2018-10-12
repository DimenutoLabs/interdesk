<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index() {

        $user = \Auth::user();
        $tickets = Ticket::select('tickets.*')
            ->leftJoin('observers', 'observers.ticket_id', 'tickets.id')
            ->where('observers.user_id', $user->id)
            ->orWhere('tickets.user_id', $user->id)
            ->orWhere('tickets.agent_user_id', $user->id)
            ->get();

        if (\Auth::user()->is_admin) {
            $tickets = Ticket::all();
        }

        return view('dashboard.index')
            ->with('tickets', $tickets);
    }

}
