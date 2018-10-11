@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><i class="fa fa-bar-chart"></i> {{ __('messages.welcome') }}</div>
                    <div class="card-body">
                        corpo
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="{{ __('messages.search_tickets') }}" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 margin-top-20">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12"><i class="fa fa-user fa-fw"></i> {{ \Auth::user()->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-10"><i class="fa fa-envelope fa-fw"></i> {{ \Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 margin-top-20">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <span>0</span> {{ __('messages.opened_tickets')}}
                                    </div>
                                    <div class="col-12">
                                        <span>0</span> {{ __('messages.unassigned_tickets')}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                aaa
                            </div>
                        </div>
                    </div>
                    <div class="col-12 margin-top-20">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a href="{{ route('ticket.create') }}"><button class="btn btn-primary btn-lg btn-block color-white" type="button"><i class="fa fa-plus-circle fa-fw"></i> {{ __('messages.add_ticket') }}</button></a>
                            </div>
                            <div class="col-12 col-md-6">
                                <button class="btn btn-info btn-lg btn-block color-white" type="button"><i class="fa fa-plus-circle fa-fw"></i> {{ __('messages.add_staff') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-bar-chart"></i> {{ __('messages.opened_tickets') }}</div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">

                            @if ( count($tickets) )
                            <tr style="background-color: gray; color: #FFF" class="text-center">
                                <th style="border-right: #FFF">
                                    Dados
                                </th>
                                <th>
                                    Responsável(eis)
                                </th>
                                <th>
                                    Solicitante
                                </th>
                                <th>
                                    Ultima Resposta
                                </th>
                                <th>
                                    Ult.
                                </th>
                                <th te>
                                    Ações
                                </th>
                            </tr>
                            @else
                                <tr>
                                    <td class="text-center">
                                        <h3 class="padding-full-15 color-gray">Não há chamados disponíveis <i class="fa-thumbs-o-up fa fa-fw"></i> </h3>
                                    </td>
                                </tr>
                            @endif

                            @foreach( $tickets as $ticket )
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <div>#{{ str_pad($ticket->id, 5, "0", STR_PAD_LEFT) }}</div>
                                                <button class="btn btn-sm" style="background-color: {{ $ticket->prior->color }}">{{ $ticket->prior->name }}</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class=""><b>Luiz Eduardo Campos Soares</b></div>
                                                <div><i>{{ $ticket->department ? $ticket->department->name : "----" }}</i></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="">{{ $ticket->user->name }}</div>
                                                <div class="color-gray font-12 line-26">{{ $ticket->created_at->format('d/m/Y @ H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="">{{ $ticket->messages->last() ? $ticket->messages->last()->user->name : ""  }}</div>
                                                <div class="color-gray font-12 line-26">{{ $ticket->messages->last() ? $ticket->messages->last()->created_at->format('d/m/Y @ H:i') : ""  }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-sm"><i class="fa fa-exclamation-circle fa-fw"></i> 4</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <a href="{{ route('ticket.edit', [$ticket->id]) }}"><button class="btn btn-success btn-sm"><i class="fa fa-fw fa-binoculars"></i></button></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            {{--@for( $i = 0; $i < 10; $i++ )--}}

                            {{--@endfor--}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection