<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    public function user() {
        return $this->BelongsTo(User::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

}
