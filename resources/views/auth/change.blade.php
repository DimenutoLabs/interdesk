@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Atualizar Senha</div>

                <div class="card-body">
                    @if ( $erro = \Session::get('error') )
                        <div class="alert alert-danger">
                            {{ $erro }}
                        </div>
                    @endif

                    <form method="POST">
                        @csrf

                        @if ( !\Auth::user()->force_update_password )
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha Atual:</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{ $email ?? old('password') }}" required autofocus>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">Nova Senha:</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control" name="new_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirm" class="col-md-4 col-form-label text-md-right">Confirmar Senha:</label>

                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control" name="password_confirm" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Alterar Senha
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
