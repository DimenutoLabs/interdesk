@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Notificações
                    </div>
                    <div class="card-body">
                        <table class="table table-strip ed">
                            @foreach( $notifications as $notification )
                                <tr @if( !$notification->read ) style="background-color: #FFF3F3;" @endif>
                                    <td style="width: 70%">{{ $notification->message }}</td>
                                    <td style="width: 15%">{{ preg_replace("/^(....).(..).(..).+$/", "$3/$2/$1", $notification->created_at) }}</td>
                                    <td style="width: 15%"><a href="{{ $notification->url }}"><button type="button" class="btn btn-success"><i class="fa fa-fw fa-eye"></i></button></a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection