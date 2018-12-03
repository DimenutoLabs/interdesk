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

        <div class="filter-button">
            <a href="{{ route('dashboard.filter') }}">
            <button type="button" class="btn btn-info" style="color: white !important;">
                <i class="fa fa-fw fa-filter"></i><br/>
                Filtros
            </button>
            </a>
        </div>

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
            $sectionTickets = array_slice($tickets["closeds"]["mine"],0,10);
            //dd( $sectionTickets );
            //$sectionTickets = $tickets["closeds"]["mine"];
            $sectionName = "Chamados fechados que participo";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["openeds"]["orphans"];
            $sectionName = "Demais chamados - Abertos";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = array_slice($tickets["closeds"]["orphans"],0,10);
            $sectionName = "Demais chamados - Fechados";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["expireds"]["mine"];
            $sectionName = "Meus chamados expirados.";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["expireds"]["observeds"];
            $sectionName = "Chamados que observo expirados.";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

        @php
            $sectionTickets = $tickets["expireds"]["orphans"];
            $sectionName = "Chamados que não participo expirados.";
            $hideIfBlank = true;
        @endphp
        @include('dashboard.section')

    </div>
@endsection

@section('footer-js')
    <script>
        @foreach( $tickets["closeds"]["mine"] as $ticket )
            @if ($ticket->rating === null && $ticket->user_id == \Auth::user()->id )
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