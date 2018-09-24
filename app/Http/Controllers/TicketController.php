<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index() {
        return view('tickets.index');
    }

    public function create() {
        return view('tickets.form_new');
    }

    public function edit($id) {
        return view('tickets.form_edit');
    }

}
