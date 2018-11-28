@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Filtros
                    </div>
                    <div class="card-body">
                        <form method="post">
                            @csrf
                            <div>
                                Escolha um setor:<br/>
                                <select multiple name="departments[]" class="form-control selectpicker" id="departments">
                                    <option></option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" @if ( in_array($department->id, $selected_departments)) selected @endif>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="">
                                Filtrar por usuário:<br/>
                                <select multiple name="users[]" class="form-control selectpicker" id="users">
                                    <option></option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if ( in_array($user->id, $selected_users)) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="start-date">Data Inicial</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control datepicker" id="start-date" name="start-date" value="{{ $start_date }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="start-date">Data Final</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control datepicker" id="end-date" name="end-date" value="{{ $end_date }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right margin-15">
                                <button class="btn btn-success">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ( $tickets )
                    <div class="card margin-30">
                        <div class="card-header">
                            Relatório Sintético (Por Setor)
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                @foreach($sinthetic_department as $ticket)
                                    <tr>
                                        {{--<td style="width: 70%">--}}
{{--                                            {{ $ticket[0]->agent->name }}--}}
                                        {{--</td>--}}
                                        <td style="width: 90%">
                                            {{ $ticket[0]->agent->department->name }}
                                        </td>
                                        <td style="width: 10%" class="text-center">
                                            <i class="fa fa-fw fa-star"></i>{{ number_format(collect($ticket)->avg('rating'), 1, ",", ".") }}
                                        </td>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card margin-30">
                        <div class="card-header">
                            Relatório Sintético (Por Usuário)
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                            @foreach($sinthetic_user as $ticket)
                                <tr>
                                    <td style="width: 70%">
                                        {{ $ticket[0]->agent->name }}
                                    </td>
                                    <td style="width: 20%">
                                        {{ $ticket[0]->agent->department->name }}
                                    </td>
                                    <td style="width: 10%" class="text-center">
                                        <i class="fa fa-fw fa-star"></i>{{ number_format(collect($ticket)->avg('rating'), 1, ",", ".") }}
                                    </td>
                            @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card margin-30">
                        <div class="card-header">
                            Relatório Analítico
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td style="width: 10%">
                                        #{{ str_pad($ticket->id, 4, "0", STR_PAD_LEFT) }}
                                    </td>
                                    <td style="width: 60%">
                                        {{ $ticket->agent->name }}
                                    </td>
                                    <td style="width: 20%">
                                        {{ $ticket->agent->department->name }}
                                    </td>
                                    <td style="width: 10%" class="text-center">
                                        <i class="fa fa-fw fa-star"></i>{{ $ticket->rating }}
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

{{--@section('footer-js')--}}
    {{--<script>--}}
        {{--$('#users').change( function() {--}}
            {{--var users = $(this).val();--}}
            {{--$('.filter-data').removeClass('filter-data-show');--}}
            {{--if (users.length > 0) {--}}
                {{--for ( id in users ) {--}}
                    {{--$('.filter-user-' + users[id]).addClass('filter-data-show').fadeTo(500,1);--}}
                {{--}--}}
                {{--$('.filter-data').not('.filter-data-show').fadeTo(500, 0, function() {--}}
                    {{--$(this).hide();--}}
                {{--});--}}
            {{--} else {--}}
                {{--$('.filter-data').fadeTo(500,0);--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
{{--@endsection--}}