<?php

namespace App\Http\Controllers\Auth;

use App\Models\Department;
use App\Models\UserExtraField;
use App\Models\UserExtraFieldValue;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    public function showRegistrationForm()
    {
        $departments = Department::all();
        return view('auth.register')->with('departments', $departments);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => $data['department'],
            'email_verified_at' => Carbon::now(),
            'should_display' => true,
        ]);

        $cpf = UserExtraField::where('name', 'CPF')->first();
        $nascimento = UserExtraField::where('name','Nascimento')->first();

        UserExtraFieldValue::create([
            'user_id' => $user->id,
            'user_extra_field_id' => $cpf->id,
            'value' => $data['cpf'],
        ]);

        UserExtraFieldValue::create([
            'user_id' => $user->id,
            'user_extra_field_id' => $nascimento->id,
            'value' => $data['nascimento'],
        ]);

        DB::commit();

        return $user;
    }
}
