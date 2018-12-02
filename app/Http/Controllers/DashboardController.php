<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Ticket;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index() {

        $user = \Auth::user();


        $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
            ->leftJoin('observers', function($join) use ($user) {
                $join->on('observers.ticket_id', 'tickets.id')
                    ->on('observers.user_id', '=', \DB::raw($user->id) );
            })
            ->where(function($query) use ($user) {
                $query->where(function($subQuery) use ($user) {
                    $subQuery->where('observers.user_id', $user->id)
                        ->orWhere('tickets.user_id', $user->id)
                        ->orWhere('tickets.agent_user_id', $user->id);
                })
                    ->orWhere(function($subQuery) use ($user) {
                        $subQuery->whereNull('tickets.agent_user_id')
                            ->where('tickets.department_id', $user->department_id);
                    });
            })
            ->orderBy('tickets.id', 'ASC')
            ->get();


        if (\Auth::user()->is_admin) {
            $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
                ->leftJoin('observers', function($join) use ($user) {
                    $join->on('observers.ticket_id', 'tickets.id')
                        ->on('observers.user_id', '=', \DB::raw($user->id) );
                })
                ->orderBy('tickets.id', 'ASC')
                ->get();
        }

        return $this->getTickets($ticketsCollection, $user);
    }

    public function getTickets($ticketsCollection, User $user) {

        $statusOpened = Status::where('action', __('messages.ticket_action_create'))->first();
        $statusClosed = Status::where('action', __('messages.ticket_action_close'))->first();
        $statusExpired = Status::where('action', __('messages.ticket_status_expired'))->first();

        $tickets = [
            "openeds" => [
                "byMe" => [],
                "toMe" => [],
                "observeds" => [],
                "orphans" => [],
            ],
            "closeds" => [
                "mine" => [],
                "observeds" => [],
                "orphans" => []
            ],
            "expireds" => [
                "mine" => [],
                "observeds" => [],
                "orphans" => [],
            ],
        ];

        foreach( $ticketsCollection as $ticket ) {
            if ( $ticket->status_id == $statusOpened->id ) {
                if ( $ticket->user_id == $user->id ) {
                    $tickets["openeds"]["byMe"][] = $ticket;
                } else if ( $ticket->agent_user_id == $user->id ) {
                    $tickets["openeds"]["toMe"][] = $ticket;
                } else if ( $ticket->observer_id == $user->id ) {
                    $tickets["openeds"]["observeds"][] = $ticket;
                } else {
                    $tickets["openeds"]["orphans"][] = $ticket;
                }
            } else if ( $ticket->status_id == $statusClosed->id ) {
                if ( $ticket->user_id == $user->id || $ticket->agent_user_id == $user->id ) {
                    array_unshift($tickets["closeds"]["mine"], $ticket);
                } else if ( $ticket->observer_id == $user->id ) {
                    array_unshift($tickets["closeds"]["observeds"], $ticket);
                } else {
                    array_unshift($tickets["closeds"]["orphans"], $ticket);
                }
            } else if ( $ticket->status_id == $statusExpired->id ) {
                if ( $ticket->user_id == $user->id || $ticket->agent_user_id == $user->id ) {
                    array_unshift($tickets["expireds"]["mine"], $ticket);
                } else if ( $ticket->observer_id == $user->id ) {
                    array_unshift($tickets["expireds"]["observeds"], $ticket);
                } else {
                    array_unshift($tickets["expireds"]["orphans"], $ticket);
                }
            }
        }

        return view('dashboard.index')
            ->with('tickets', $tickets)
            ->with('statusOpened', $statusOpened);
    }


    public function filterForm() {

        $possibleUsers = User::all();
        $agents = User::all();

        return view('dashboard.filter')
                    ->with('agents', $agents)
                    ->with('users', $possibleUsers);
    }

    public function filter(Request $request) {

        $user = \Auth::user();


        $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
            ->leftJoin('observers', function($join) use ($user) {
                $join->on('observers.ticket_id', 'tickets.id')
                    ->on('observers.user_id', '=', \DB::raw($user->id) );
            })
            ->where(function($query) use ($user) {
                $query->where(function($subQuery) use ($user) {
                    $subQuery->where('observers.user_id', $user->id)
                        ->orWhere('tickets.user_id', $user->id)
                        ->orWhere('tickets.agent_user_id', $user->id);
                })
                    ->orWhere(function($subQuery) use ($user) {
                        $subQuery->whereNull('tickets.agent_user_id')
                        ->where('tickets.department_id', $user->department_id);
                });
            })

            ->orderBy('tickets.id', 'ASC');



        if (\Auth::user()->is_admin) {
            $ticketsCollection = Ticket::select('tickets.*', 'observers.user_id as observer_id')
                ->leftJoin('observers', function($join) use ($user) {
                    $join->on('observers.ticket_id', 'tickets.id')
                        ->on('observers.user_id', '=', \DB::raw($user->id) );
                })
                ->orderBy('tickets.id', 'ASC');
        }

        if ( $createdBy = $request->get('user') ) {
            $ticketsCollection = $ticketsCollection->where('tickets.user_id', $createdBy);
        }
        if ( $agent = $request->get('agent') ) {
            $ticketsCollection = $ticketsCollection->where('tickets.agent_user_id', $agent);
        }
        if ( $title = $request->get('title') ) {
            $ticketsCollection = $ticketsCollection->where(function($query) use ($title) {
                $query->where('tickets.small_title', 'LIKE', '%' . $title . '%')
                    ->orWhere('tickets.title', 'LIKE', '%' . $title . '%');
            });
        }
        if ( $content = $request->get('content')) {
            $ticketsCollection = $ticketsCollection->where('tickets.content', 'LIKE', '%' . $content . '%');
        }
        if ( $start = $request->get('start-date')) {
            $ticketsCollection = $ticketsCollection->where('tickets.created_at', '>=', preg_replace("/^(..).(..).(....).*$/", "$3-$2-$1", $start) );
        }
        if ( $end = $request->get('end-date')) {
            $ticketsCollection = $ticketsCollection->where('tickets.created_at', '<=', preg_replace("/^(..).(..).(....).*$/", "$3-$2-$1", $end) );
        }

        $ticketsCollection = $ticketsCollection->get();

        return $this->getTickets($ticketsCollection, $user);
    }

}
