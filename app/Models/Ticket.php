<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    public function user() {
        return $this->BelongsTo(User::class, 'user_id', 'id');
    }

    public function agent() {
        return $this->BelongsTo(User::class, 'agent_user_id', 'id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function prior() {
        return $this->BelongsTo(Prior::class);
    }

    public function department() {
        return $this->BelongsTo(Department::class);
    }

    public function  status() {
        return $this->belongsTo(Status::class);
    }

    public function observers() {
        return $this->hasMany(Observer::class);
    }

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }

    public function getLastActionsAttribute() {

        $access = UserTicketAccess::where('user_id', \Auth::user()->id)
            ->where('ticket_id', $this->id )
            ->orderBy('created_at', 'DESC')
            ->first();

        $lastAccess = "0000-00-00 00:00:00";
        $numberOfAlerts = 0;

        if ( $access == null ) {
            $numberOfAlerts++;
        } else {
            $lastAccess = $access->created_at->format('Y-m-d H:i:s');
        }

        $messages = Message::where('created_at', '>', $lastAccess)
            ->where('ticket_id', $this->id)
            ->get();

        $numberOfAlerts += $messages->count();

        return $numberOfAlerts;
    }

}
