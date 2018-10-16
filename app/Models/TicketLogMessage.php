<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TicketLogMessage extends Model
{
    public function user() {
        return $this->BelongsTo(User::class);
    }
}
