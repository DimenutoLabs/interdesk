<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function change() {
        return view('auth.change');
    }

    public function save(Request $request) {

        $user = $request->user();

        $oldPass = $request->get('password');
        $newPass = $request->get('new_password');
        $confirm = $request->get('password_confirm');

        if ( !\Hash::check($oldPass, $user->password) && !$user->force_update_password ) {
            return redirect( route('password.change') )->with('error', 'Senha antiga é inválida.');
        }

        if ( $confirm != $newPass ) {
            return redirect(route('password.change') )->with('error', 'Nova senha e confirmação estão diferentes');
        }

        $user = $request->user();
        $user->password = \Hash::make($newPass);
        $user->force_update_password = 0;
        $user->save();

        return redirect('dashboard');

    }
}
