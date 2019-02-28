<?php

namespace App;

use App\Models\Department;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function newQuery()
    {
        $adminLogin = \Session::get('admin_login');
        if (!$adminLogin && $wantsAdmin = \Request::get('admin_login')) {
            \Session::put('admin_login', $wantsAdmin);
            $adminLogin = 1;
        }

        if ( !$adminLogin ) {
            return parent::newQuery()->where('should_display', true);
        } else {
            return parent::newQuery();
        }
    }

}
