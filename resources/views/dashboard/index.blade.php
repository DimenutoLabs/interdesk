@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Bem Vindo</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2" style="text-align: right; color: #999; font-size: 12px; line-height: 20px; white-space: nowrap;">Nome:</div>
                            <div class="col-4">{{ \Auth::user()->name }}</div>

                            <div class="col-2" style="text-align: right; color: #999; font-size: 12px; line-height: 20px; white-space: nowrap;">Email:</div>
                            <div class="col-4">{{ \Auth::user()->email }}</div>

                            <div class="col-12"><div style="background-color: #EEE; height: 1px; width: 100%; margin: 5px 0;"></div></div>

                            <div class="col-2" style="text-align: right; color: #999; font-size: 12px; line-height: 20px; white-space: nowrap;">Departamento:</div>
                            <div class="col-4">{{ \Auth::user()->department->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Estatísticas</div>
                    <div class="card-body">
                        <span style="color: #77F">{{ count($tickets["openeds"]["byMe"]) + count($tickets["openeds"]["toMe"]) + count($tickets["openeds"]["observeds"]) + count($tickets["closeds"]["mine"]) }}</span> Chamados Totais
                        <div style="background-color: #EEE; height: 1px; width: 100%; margin: 5px 0;"></div>
                        <span style="color: #77F">{{ count($tickets["openeds"]["byMe"]) + count($tickets["openeds"]["toMe"]) }}</span> Chamados em Aberto
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
            {{--<div class="col-md-8">--}}
                {{--<div class="card">--}}
                    {{--<div class="card-header"><i class="fa fa-bar-chart"></i> {{ __('messages.welcome') }}</div>--}}
                    {{--<div class="card-body">--}}
                        {{--corpo--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}
                        {{--<form method="post">--}}
                            {{--<div class="input-group mb-3">--}}
                                {{--<input type="text" class="form-control" placeholder="{{ __('messages.search_tickets') }}" aria-label="Recipient's username" aria-describedby="basic-addon2">--}}
                                {{--<div class="input-group-append">--}}
                                    {{--<span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-12 margin-top-20">--}}
                        {{--<div class="card">--}}
                            {{--<div class="card-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-12"><i class="fa fa-user fa-fw"></i> {{ \Auth::user()->name }}</div>--}}
                                {{--</div>--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-10"><i class="fa fa-envelope fa-fw"></i> {{ \Auth::user()->email }}</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-12 margin-top-20">--}}
                        {{--<div class="card">--}}
                            {{--<div class="card-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-12">--}}
                                        {{--<span>0</span> {{ __('messages.opened_tickets')}}--}}
                                    {{--</div>--}}
                                    {{--<div class="col-12">--}}
                                        {{--<span>0</span> {{ __('messages.unassigned_tickets')}}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="card-footer">--}}
                                {{--aaa--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-12 margin-top-20">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-12 col-md-6">--}}
                                {{--<a href="{{ route('ticket.create') }}"><button class="btn btn-primary btn-lg btn-block color-white" type="button"><i class="fa fa-plus-circle fa-fw"></i> {{ __('messages.add_ticket') }}</button></a>--}}
                            {{--</div>--}}
                            {{--<div class="col-12 col-md-6">--}}
                                {{--<button class="btn btn-info btn-lg btn-block color-white" type="button"><i class="fa fa-plus-circle fa-fw"></i> {{ __('messages.add_staff') }}</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        @php
            $sectionTickets = $tickets["openeds"]["byMe"];
            $sectionName = "Chamados abertos por mim";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')


        @php
            $sectionTickets = $tickets["openeds"]["toMe"];
            $sectionName = "Chamados abertos para mim";
            $hideIfBlank = false;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["openeds"]["observeds"];
            $sectionName = "Chamados abertos que observo";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["closeds"]["mine"];
            $sectionName = "Chamados fechados que participo";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

    </div>
@endsection

@section('footer-js')
    <script>
        @foreach( $tickets["closeds"]["mine"] as $ticket )
            @if ($ticket->rating == null && $ticket->user_id == \Auth::user()->id )
                $('#rating-{{ $ticket->id }}').starrr({
                    change: function(e, value) {
                        var ticket = e.currentTarget.getAttribute('data-ticket');
                        var confirmation = confirm("Tem certeza que deseja enviar esta avaliação?");
                        if ( confirmation ) {
                            sendRate(ticket, value)
                        }
                    }
                });
            @endif
        @endforeach

        var sendRate = function(id, value) {
            $.get('/ticket/' + id + '/rate/' + value)
                .fail(function(e) {
                    new Noty({
                        text: "Não foi possível enviar a avaliação, tente novamente mais tarde, ou entre em contato com o administrador",
                        layout: 'topCenter',
                        timeout: 2500,
                        progressBar: true,
                        type: 'error',
                        theme: 'bootstrap-v4'
                    }).show();
                })
                .done(function(e) {
                    new Noty({
                        text: "Avaliação enviada com sucesso!",
                        layout: 'topCenter',
                        timeout: 1500,
                        progressBar: true,
                        type: 'success',
                        theme: 'bootstrap-v4'
                    }).show();

                    $('#rating-' + id).html( value + '<i class="fa fa-fw fa-star"></i>');
                })
        }
    </script>
@endsection