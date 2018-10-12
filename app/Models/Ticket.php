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

}
