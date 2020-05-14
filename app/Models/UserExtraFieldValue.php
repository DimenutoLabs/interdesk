<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExtraFieldValue extends Model
{
    protected $fillable = ['user_id', 'user_extra_field_id', 'value'];
}
