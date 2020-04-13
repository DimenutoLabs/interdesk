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
                        <form method="post" action="{{ route('dashboard') }}">
                            @csrf
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="title">Título</label>
                                        <input type="text" class="form-control" id="title" name="title" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="content">Conteúdo</label>
                                        <input type="text" class="form-control" id="content" name="content" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start-date">Data Inicial</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control date" id="start-date" name="start-date" value="" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end-date">Data Final</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control date" id="end-date" name="end-date" value="" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user">Usuário</label>
                                        <select name="user" id="user"  class="form-control selectpicker">
                                                <option></option>
                                            @foreach( $users as $user )
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="agent">Responsável</label>
                                        <select name="agent" id="agent"  class="form-control selectpicker">
                                            <option></option>
                                            @foreach( $agents as $agent )
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right margin-15">
                                <button class="btn btn-success">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection