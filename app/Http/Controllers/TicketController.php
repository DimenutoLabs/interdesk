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
        $users = User::where('id', '!=', \Auth::user()->id )
            ->where('should_display', true)->get();
        $departments = Department::all();

        return view('tickets.form_new')
            ->with('priors', $priors)
            ->with('users', $users)
            ->with('departments', $departments);
    }

    public function edit($id) {
        $ticket = Ticket::find($id);
        $status = Status::all();
        $priors = Prior::all();

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


        $message = __('messages.log_ticket_accessed');
        $message = str_replace("{%0}", Request::user()->name . " (#" . Request::user()->id . ")", $message);
        $log = new TicketLogMessage();
        $log->message = $message;
        $log->ticket_id = $ticket->id;
        $log->user_id = Request::user()->id;
        $log->ip = Request::ip();
        $log->save();


        foreach( $notifications as $notification ) {
            $notification->read = true;
            $notification->save();
        }

        return view('tickets.form_edit')
            ->with('ticket', $ticket)
            ->with('status', $status)
            ->with('priors', $priors)
            ->with('logs', $logs);
    }

    public function saveDateAndPriorChanges(Request $request, $ticketId) {

        \DB::beginTransaction();
        $ticket = Ticket::findOrFail($ticketId);
        if ( $date = $request->get('limit_date') ) {
            $ticket->limit_date = preg_replace("/^(..).(..).(....)$/", "$3-$2-$1", $date);
        }
        if ( $prior = $request->get('prior') ) {
            $ticket->prior_id = $prior;
        }
        if ( $request->get('status') && $request->user()->is_admin ) {
            $ticket->status_id = $request->get('status');
        }
        if ( $nota = $request->get('remove_rating') ) {
            $ticket->rating = null;
        }
        $ticket->save();
        \DB::commit();

        return redirect( route('ticket.edit', [$ticket->id]) );
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

        if ( $ticket->agent_user_id ) {
            $notification = new Notification();
            $notification->ticket_id = $ticket->id;
            $notification->user_id = $ticket->user_id == $request->user()->id ? $ticket->agent_user_id : $ticket->user_id;
            $notification->read = false;
            $notification->message = "Foi aberto um novo chamado para você.";
            $notification->url = "/ticket/" . $ticket->id . "/edit";
            $notification->save();
        } else {
            $department = $ticket->department_id;
            $departmentUsers = User::where('department_id', $department)->get();
            foreach( $departmentUsers as $departmentUser ) {
                if ( $departmentUser->id == \Auth::user()->id ) {
                    continue;
                }

                $notification = new Notification();
                $notification->ticket_id = $ticket->id;
                $notification->user_id = $departmentUser->id;
                $notification->read = false;
                $notification->message = "Foi aberto um novo chamado para seu setor.";
                $notification->url = "/ticket/" . $ticket->id . "/edit";
                $notification->save();
            }
        }

        if ( $observers = $request->get('observers') ) {
            foreach( $observers as $user ) {
                if ( !$user ) {
                    continue;
                }
                $observer = new Observer();
                $observer->ticket_id = $ticket->id;
                $observer->user_id = $user;
                $observer->save();

                $notification = new Notification();
                $notification->ticket_id = $ticket->id;
                $notification->user_id = $user;
                $notification->read = false;
                $notification->message = "Foi aberto um novo onde você é observador";
                $notification->url = "/ticket/" . $ticket->id . "/edit";
                $notification->save();
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

        if ( $ticket->agent_user_id ) {
            $notification = new Notification();
            $notification->ticket_id = $id;
            $notification->user_id = $ticket->user_id == $request->user()->id ? $ticket->agent_user_id : $ticket->user_id;
            $notification->read = false;
            $notification->message = "Foi respondido um chamado que você participa";
            $notification->url = "/ticket/" . $ticket->id . "/edit";
            $notification->save();
        } else {
            $department = $ticket->department_id;
            $departmentUsers = User::where('department_id', $department)->get();

            foreach( $departmentUsers as $departmentUser ) {
                if ( $departmentUser->id == \Auth::user()->id ) {
                    continue;
                }

                $notification = new Notification();
                $notification->ticket_id = $ticket->id;
                $notification->user_id = $departmentUser->id;
                $notification->read = false;
                $notification->message = "Foi respondido um chamado para o seu setor.";
                $notification->url = "/ticket/" . $ticket->id . "/edit";
                $notification->save();
            }
        }


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

        $notification = new Notification();
        $notification->ticket_id = $ticket->id;
        $notification->user_id = $ticket->user_id == $request->user()->id ? $ticket->agent_user_id : $ticket->user_id;
        $notification->read = false;
        $notification->message = "Um chamado que você participa foi fechado.";
        $notification->url = "/ticket/" . $ticket->id . "/edit";
        $notification->save();

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

        $notification = new Notification();
        $notification->ticket_id = $ticket->id;
        $notification->user_id = $ticket->agent_user_id;
        $notification->read = false;
        $notification->message = "Foi dado uma nota em um chamado que você é responsável.";
        $notification->url = "/ticket/" . $ticket->id . "/edit";
        $notification->save();

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
