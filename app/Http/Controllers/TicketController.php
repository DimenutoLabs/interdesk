<?php

namespace App\Http\Controllers;

use App\DateHelpers;
use App\Models\Department;
use App\Models\Message;
use App\Models\Observer;
use App\Models\Prior;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TicketLogMessage;
use App\Models\UserTicketAccess;
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
        $status = Status::all();

        $access = new UserTicketAccess();
        $access->user_id = \Auth::user()->id;
        $access->ticket_id = $id;
        $access->save();

        $logs = TicketLogMessage::where('ticket_id', $id)
            ->get();

        return view('tickets.form_edit')
            ->with('ticket', $ticket)
            ->with('status', $status)
            ->with('logs', $logs);
    }

    public function save(Request $request) {

        \DB::beginTransaction();
        $ticket = new Ticket();
        $ticket->user_id = $request->user()->id;
        $ticket->small_title = $request->get('small_title');
        $ticket->title = $request->get('title');
        $ticket->limit_date = $request->get('limit_date') ? DateHelpers::brToSql($request->get('limit_date')) : null;
        $ticket->estimated_time = $request->get('estimated_time');
        $ticket->content = $request->get('content');
        $ticket->prior_id = $request->get('prior');
        $ticket->status_id = Status::where('default', true)->first()->id;
        if ( $assigned = $request->get('assigned_to') ) {
            $ticket->agent_user_id = $assigned;
        }
        $ticket->save();

        dd( $request->all() );
        dd( $request->get('observers') );
        if ( $observers = explode(",", $request->get('observers')) ) {
            foreach( $observers as $user ) {
                if ( !$user ) {
                    continue;
                }
                $observer = new Observer();
                $observer->ticket_id = $ticket->id;
                $observer->user_id = $user;
                $observer->save();
            }
        }

        $message = __('messages.log_ticket_created');
        $message = str_replace("{%0}", $request->user()->name . " (#" . $request->user()->id . ")", $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = $request->user()->id;
        $log->ip = $request->ip();
        $log->save();

        \DB::commit();

        return redirect( route('ticket.edit', [$ticket->id]) );

    }

    public function update(Request $request, $id) {

        \DB::beginTransaction();

        $ticket = Ticket::findOrFail($id);
        $message = new Message();
        $message->user_id = $request->user()->id;
        $message->ticket_id = $id;
        $message->message = $request->reply_content;
        $message->save();


        $message = __('messages.log_ticket_new_message');
        $message = str_replace("{%0}", $request->user()->name . " (#" . $request->user()->id . ")", $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = $request->user()->id;
        $log->ip = $request->ip();
        $log->save();

        \DB::commit();

        return redirect( route('ticket.edit', [$ticket->id]) );
    }

    public function becomeAgent(Request $request, $id) {

        \DB::beginTransaction();
        $ticket = Ticket::findOrFail($id);

        if ( $request->user()->id != $ticket->user_id && !$ticket->agent_user_id ) {
            $ticket->agent_user_id = $request->user()->id;
            $ticket->save();
        }

        $message = __('messages.log_ticket_become_agent');
        $message = str_replace("{%0}", $request->user()->name . " (#" . $request->user()->id . ")", $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = $request->user()->id;
        $log->ip = $request->ip();
        $log->save();
        \DB::commit();

        return redirect( route('ticket.edit', [$ticket->id]) );
    }

    public function close(Request $request, $id) {

        \DB::beginTransaction();
        $ticket = Ticket::findOrFail($id);
        $status = Status::where('action', __('messages.ticket_action_close'))->first();

        if ( $request->user()->id == $ticket->user_id || $request->user()->id == $ticket->agent_user_id ) {
            $ticket->status_id = $status->id;
            $ticket->save();
        }

        $message = __('messages.log_ticket_status_change');
        $message = str_replace("{%0}", $request->user()->name . " (#" . $request->user()->id . ")", $message);
        $message = str_replace("{%1}", $status->name, $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = $request->user()->id;
        $log->ip = $request->ip();
        $log->save();
        \DB::commit();

        return redirect( route('ticket.edit', [$ticket->id]) );
    }

    public function rate(Request $request, $id, $value = null) {

        \DB::beginTransaction();
        $ticket = Ticket::findOrFail($id);

        if ( $request->user()->id == $ticket->user_id && $value !== null && $ticket->rating === null ) {
            $ticket->rating = $value;
            $ticket->save();
        } else {
            abort(400);
        }

        $message = __('messages.log_ticket_rated');
        $message = str_replace("{%0}", $request->user()->name . " (#" . $request->user()->id . ")", $message);
        $message = str_replace("{%1}", $value, $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = $request->user()->id;
        $log->ip = $request->ip();
        $log->save();
        \DB::commit();

        return response("ok");
    }

}
