<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use App\User;
use Illuminate\Http\Request;

class ControllerRoomController extends Controller
{

    public function index() {
        $users = User::all();
        $departments = Department::all();
        return view('controller.index')
            ->with('users', $users)
            ->with('departments', $departments)
            ->with('tickets', null)
            ->with('start_date', "")
            ->with('end_date', "")
            ->with('selected_departments', [])
            ->with('selected_users', []);

    }

    public function filter(Request $request) {

        $users = User::all();
        $departments = Department::all();

        $tickets = Ticket::select('tickets.*', 'departments.id as dept_id', 'departments.name as dept_nome')
                        ->leftJoin('users', 'users.id', '=', 'tickets.agent_user_id')
                        ->leftJoin('departments', 'departments.id', '=', 'users.department_id');

        $selectedDepartments = $request->get('departments') ?: [];
        $selectedUsers = $request->get('users') ?: [];

        if ( count($selectedUsers) ) {
            $tickets = $tickets->whereIn('agent_user_id', $selectedUsers);
        }
        if ( count($selectedDepartments) ) {
            $tickets = $tickets->whereIn('departments.id', $selectedDepartments);
        }

        if ( $start = $request->get('start-date') ) {
            $tickets = $tickets->where('tickets.updated_at', '>=', preg_replace("/^(..).(..).(....)$/", "$3-$2-$1", $start) );
        }

        if ( $end = $request->get('end-date') ) {
            $tickets = $tickets->where('tickets.updated_at', '<=', preg_replace("/^(..).(..).(....)$/", "$3-$2-$1", $end) );
        }

        $tickets = $tickets->get();

        $syntheticUser = [];
        $syntheticDepartment = [];

        foreach( $tickets as $ticket ) {
            if ( !isset($syntheticDepartment[ $ticket->dept_id ]) ) {
                $syntheticDepartment[ $ticket->dept_id ] = [];
            }
            if ( !isset($syntheticUser[ $ticket->agent_user_id ]) ) {
                $syntheticUser[ $ticket->agent_user_id ] = [];
            }

            $syntheticDepartment[ $ticket->dept_id ][] = $ticket;
            $syntheticUser[ $ticket->agent_user_id ][] = $ticket;
        }

        return view('controller.index')
            ->with('users', $users)
            ->with('departments', $departments)
            ->with('tickets', $tickets)
            ->with('start_date', $request->get('start-date'))
            ->with('end_date', $request->get('end-date'))
            ->with('selected_departments', $selectedDepartments)
            ->with('selected_users', $selectedUsers)
            ->with('sinthetic_department', $syntheticDepartment)
            ->with('sinthetic_user', $syntheticUser);

    }


}
