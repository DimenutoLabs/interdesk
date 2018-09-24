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
                                <button class="btn btn-primary btn-lg btn-block color-white" type="button"><i class="fa fa-plus-circle fa-fw"></i> {{ __('messages.add_ticket') }}</button>
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
                        <table class="table table-hover">
                            @for( $i = 0; $i < 10; $i++ )
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <div>00001</div>
                                            <div>Baixa</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class=""><b>Luiz Eduardo Campos Soares</b></div>
                                            <div><i>Diretoria</i></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="">Eduardo Soares</div>
                                            <div class="color-gray font-12 line-26">12/09/2018 @ 18:00</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="">Mário Alberto</div>
                                            <div class="color-gray font-12 line-26">12/09/2018 @ 18:00</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="notifications-badge">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> 4
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            ---
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endfor
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection