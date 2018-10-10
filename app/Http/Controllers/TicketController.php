<?php

namespace App\Http\Controllers;

use App\Models\Prior;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index() {
        return view('tickets.index');
    }

    public function create() {
        $priors = Prior::all();
        return view('tickets.form_new')
            ->with('priors', $priors);
    }

    public function edit($id) {
        return view('tickets.form_edit');
    }

    public function save(Request $request) {

        $ticket = new Ticket();
        $ticket->user_id = $request->user()->id;
        $ticket->small_title = $request->get('small_title');
        $ticket->title = $request->get('title');
        $ticket->limit_date = $request->get('limit_date');
        $ticket->estimated_time = $request->get('estimated_time');
        $ticket->content = $request->get('content');
        $ticket->save();

        return $ticket;

    }

}
