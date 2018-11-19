@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ __('messages.tickets') }} / {{ __('messages.edit') }}
                    </div>
                </div>
            </div>

            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <div><h5>Aberto Por:</h5></div>
                            <div><i class="fa fa-user fa-fw"></i> {{ $ticket->user->name }}</div>
                            <div><i class="fa fa-envelope fa-fw"></i> {{ $ticket->user->email }}</div>
                            <div class="color-gray"><span class="font-10">{{ $ticket->created_at->format('d/m/Y @ H:i:s') }}</span> </div>
                        </div>
                        @if ($ticket->user_id == \Auth::user()->id || $ticket->agent_user_id == \Auth::user()->id || ($ticket->agent_user_id == null && $ticket->department_id == \Auth::user()->department_id ) )
                        <div class="float-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $ticket->status->name }}
                                </button>
                                <div class="dropdown-menu">
                                    @if ( $ticket->agent_user_id == null && $ticket->user_id != \Auth::user()->id )
                                        <a class="dropdown-item" href="{{ route('ticket.agent.become', $ticket->id) }}">Tornar Responsável</a>
                                    @elseif ( $ticket->status->action == __('messages.ticket_action_create') )
                                        @if ( $ticket->user_id == \Auth::user()->id )
                                            <div class="dropdown-item" id="close-ticket" style="cursor: pointer">Fechar</div>
                                        @elseif ( $ticket->agent_user_id == \Auth::user()->id )

                                        @endif
                                        {{--<a class="dropdown-item" href="#">Colocar em Espera</a>--}}
                                        {{--<div class="dropdown-divider"></div>--}}
                                        {{--<a class="dropdown-item" href="#">Transferir</a>--}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if ( $ticket->agent_user_id || $ticket->observers->count() )
                    <div class="card-header" style="background-color: #F4F4FF">
                        <div class="row">
                            @if ($ticket->agent_user_id)
                            <div class="col-6">
                                <div>Responsável:</div>
                                <div><i class="fa fa-user fa-fw"></i> {{ $ticket->agent->name }} ({{ $ticket->agent->email }})</div>
                            </div>
                            @endif
                            @if ( $ticket->observers->count() )
                            <div class="col-6">
                                <div>Observadores:</div>
                                @foreach( $ticket->observers as $observer )
                                <div><i class="fa fa-user fa-fw"></i> {{ $observer->user->name }} ({{ $observer->user->email }})</div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="card-body">
                        <h6><i class="fa fa-comment-o fa-fw"></i> {{ mb_strtoupper($ticket->small_title) }}</h6>
                        <div class="font-14 color-gray">{{ $ticket->title }}</div>
                        <hr>
                        <div>
                            <?php echo $ticket->content; ?>
                        </div>
                    </div>
                    @if ( $ticket->attachments )
                    <div class="card-header">
                        <div>Arquivos em anexo:</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach( $ticket->attachments as $attachment )
                            <div class="col-2 text-center">
                                <div style="background-color: #EEE; padding: 5px;">
                                    <div style="background-color: #FFF; padding: 5px;">
                                       <a href="{{ $attachment->path }}" target="__blank"><img src="{{ $attachment->path }}" height="80"></a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <div><i class="fa fa-comment-o fa-fw"></i> {{ __('messages.ticket_messages') }}</div>
                    </div>
                    <div class="card-body">

                        @if ( count($ticket->messages) )

                            <div class="middle-line">

                                @foreach( $ticket->messages as $message )
                                    @if ( $message->user_id == \Auth::user()->id )
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="alert alert-success">
                                                    <i class="fa fa-user fa-fw"></i> {{ __('messages.me') }}: <small>({{ $message->created_at->format('d/m/Y @ H:i:s') }})</small>
                                                    <hr>
                                                    <div>
                                                        <?php echo $message->message; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="offset-md-6 col-md-6">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-user fa-fw"></i> {{ $message->user->name }}: <small>({{ $message->created_at->format('d/m/Y @ H:i:s') }})</small>
                                                    <hr>
                                                    <div>
                                                        <?php echo $message->message; ?>
                                                    </div>
                                                    @if ( $message->attachment_id )
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-2 col-3">
                                                            <img src="https://picsum.photos/200/300/?random&1=1" style="width: 100%;">
                                                        </div>
                                                        <div class="col-sm-2 col-3">
                                                            <img src="https://picsum.photos/200/300/?random&2=2" style="width: 100%;">
                                                        </div>
                                                        <div class="col-sm-2 col-3">
                                                            <img src="https://picsum.photos/200/300/?random&3=3" style="width: 100%;">
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        @else
                            <div class="text-center padding-full-10">
                                <h3 class="color-gray">{{ __('messages.ticket_no_messages') }} <i class="fa fa-thumbs-o-up fa-fw"></i></h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 margin-top-30">
                <form method="post" id="edit_ticket_form" action=" {{ route('ticket.update', [$ticket->id]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-reply fa-fw"></i> {{ __('messages.reply_ticket') }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="reply_content">{{ __('messages.message') }} <b class="color-red">*</b></label>
                                <textarea name="reply_content" id="reply_content" data-field_name="{{ __('messages.field_edit_ticket_reply_name') }}"></textarea>
                            </div>

                            <div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-info" id="file-preview-button" style="color: #FFF"><i class="fa fa-fw fa-plus"></i> Selecionar</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="row" id="file-preview-zone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button
                                    @if ( ($ticket->agent_user_id != \Auth::user()->id && $ticket->user_id != \Auth::user()->id) || $ticket->status->name == __('messages.ticket_status_closed') )
                                    disabled
                                    @endif
                                    class="btn btn-success">
                                <i class="fa fa-check fa-fw"></i> {{ __('messages.reply_ticket') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            @if ( \Auth::user()->is_admin )
            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-history fa-fw"></i> {{ __('messages.ticket_history') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            @foreach( $logs as $log )
                                <tr>
                                    <td style="width: 30%">
                                        <div>{{ $log->created_at->format("d/m/Y @ H:i") }}</div>
                                        <div class="font-12 color-gray">{{ $log->user->name }} / {{ $log->ip }} </div>
                                    </td>
                                    <td style="width: 70%">
                                        {{ $log->message }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('footer-js')
    <script src="{{ asset('js/editticket.js') }}?v={{ microtime() }}"></script>
    @if ( ($ticket->agent_user_id != \Auth::user()->id && $ticket->user_id != \Auth::user()->id) || $ticket->status->name == __('messages.ticket_status_closed') )
    <script>
        @if ($ticket->status->name == __('messages.ticket_status_closed'))
        $('div[contenteditable="true"]').css('background', '#EEE').html('Você não pode mais enviar mensagens pois o chamado está fechado.').on('click', function() {
            $(this).html('Você não pode mais enviar mensagens pois o chamado está fechado.').blur();
        });
        @else
        $('div[contenteditable="true"]').css('background', '#EEE').html('Você não pode enviar mensagens neste ticket pois não é responsável pelo mesmo.').on('click', function() {
            $(this).html('Você não pode enviar mensagens neste ticket pois não é responsável pelo mesmo.').blur();
        });
        @endif
    </script>
    @endif
    <script>
        $('#file-preview-button').scelUploader();
        $('#close-ticket').click(function() {
            if ( confirm("Deseja realmente fechar este chamado?") ) {
                window.location.href = "{{ route('ticket.close', $ticket->id) }}"
            }
        })
    </script>
@endsection