<?php

namespace App\Http\Controllers;

use App\DateHelpers;
use App\Models\Department;
use App\Models\Message;
use App\Models\Prior;
use App\Models\Ticket;
use App\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index() {
        return view('tickets.index');
    }

    public function create() {
        $priors = Prior::all();
        $users = User::where('id', '!=', \Auth::user()->id )->get();
        $departments = Department::all();

        return view('tickets.form_new')
            ->with('priors', $priors)
            ->with('users', $users)
            ->with('departments', $departments);
    }

    public function edit($id) {
        $ticket = Ticket::find($id);

        return view('tickets.form_edit')
            ->with('ticket', $ticket);
    }

    public function save(Request $request) {

        $ticket = new Ticket();
        $ticket->user_id = $request->user()->id;
        $ticket->small_title = $request->get('small_title');
        $ticket->title = $request->get('title');
        $ticket->limit_date = DateHelpers::brToSql($request->get('limit_date'));
        $ticket->estimated_time = $request->get('estimated_time');
        $ticket->content = $request->get('content');
        $ticket->prior_id = $request->get('prior');
        $ticket->save();

        return redirect( route('ticket.edit', [$ticket->id]) );

    }

    public function update(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);
        $message = new Message();
        $message->user_id = $request->user()->id;
        $message->ticket_id = $id;
        $message->message = $request->reply_content;
        $message->save();

        return redirect( route('ticket.edit', [$ticket->id]) );
    }

}
