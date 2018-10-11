<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index() {
        $tickets = Ticket::where('user_id', \Auth::user()->id )->get();
        return view('dashboard.index')
            ->with('tickets', $tickets);
    }

}
