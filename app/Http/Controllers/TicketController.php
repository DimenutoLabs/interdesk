<?php

namespace App\Http\Controllers;

use App\DateHelpers;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Observer;
use App\Models\Prior;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TicketLogMessage;
use App\Models\UserTicketAccess;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

        $notifications = Notification::where('user_id', \Auth::user()->id)
                            ->where('ticket_id', $id)
                            ->where('read', false)
                            ->get();
        foreach( $notifications as $notification ) {
            $notification->read = true;
            $notification->save();
        }

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
        if ( $department = $request->get('department') ) {
            $ticket->department_id = $department;
        }
        $ticket->save();

        if ( $observers = $request->get('observers') ) {
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

        if ( $files = $request->file('attachments') ) {
            foreach ( $files as $file ) {
                $name = $this->uploadFile($file);
                $attachment = new Attachment();
                $attachment->ticket_id = $ticket->id;
                $attachment->path = $name[0];
                $attachment->original_name = $name[1];
                $attachment->save();
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

        $notification = new Notification();
        $notification->ticket_id = $id;
        $notification->user_id = $ticket->user_id == $request->user()->id ? $ticket->agent_user_id : $ticket->user_id;
        $notification->read = false;
        $notification->message = "Respondeu um chamado que você participa";
        $notification->url = route('ticket.update', $ticket->id);
        $notification->save();



        if ( $files = $request->file('attachments') ) {
            foreach ( $files as $file ) {
                $name = $this->uploadFile($file);
                $attachment = new Attachment();
//                $attachment->ticket_id = $ticket->id;
                $attachment->message_id = $message->id;
                $attachment->path = $name[0];
                $attachment->original_name = $name[1];
                $attachment->save();
            }
        }

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

    public function uploadFile(UploadedFile $file) {

//        if ( $file->getMimeType() == "application/pdf" ||
//            preg_match("/^image.+$/",$file->getMimeType()) ||
//            preg_match("/^video.+$/",$file->getMimeType()) ||
//            preg_match("/.+officedocument.+$/",$file->getMimeType())
//        ) {
//            echo "Mime OK!";
//        }

        $hashName = $file->hashName();

        $file->storeAs(
          'tickets', $hashName
        );
        return [$hashName, $file->getClientOriginalName()];
    }

    public function getFilePath($filename) {
        return route('ticket.file.download', $filename, 1);
    }

    public function getFile($filename) {
        if (Storage::disk('local')->exists("tickets" . DIRECTORY_SEPARATOR . $filename)) {
            return response()->file( config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . "tickets" . DIRECTORY_SEPARATOR . $filename );
        }
    }

}
