@extends('layouts.app')

@section('header-js')
<style>
    .ui-datepicker {
        z-index: 4000 !important;
    }
    .select2, .select2-container, .select2-container--default, .select2-container--focus {
        width: 100% !important
    }
</style>
@endsection

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
                                            <div class="dropdown-item" id="edit-ticket"  data-toggle="modal" data-target="#exampleModal" style="cursor: pointer">Editar</div>
                                        @elseif ( $ticket->agent_user_id == \Auth::user()->id )
                                        @endif
                                        {{--<a class="dropdown-item" href="#">Colocar em Espera</a>--}}
                                        {{--<div class="dropdown-divider"></div>--}}
                                        {{--<a class="dropdown-item" href="#">Transferir</a>--}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @elseif (\Auth::user()->is_admin)
                            <div class="float-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $ticket->status->name }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item" id="edit-ticket"  data-toggle="modal" data-target="#exampleModal" style="cursor: pointer">Editar</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ( $ticket->agent_user_id || $ticket->observers->count() )
                    <div class="card-header" style="background-color: #F4F4FF">
                        <div class="row">
                            @if ($ticket->agent_user_id)
                            <div class="col-5">
                                <div>Responsável:</div>
                                <div><i class="fa fa-user fa-fw"></i> {{ $ticket->agent->name }} ({{ $ticket->agent->email }})</div>
                            </div>
                            @endif
                            <div class="col-2">
                                Expiração:
                                @if ($ticket->limit_date)
                                    <div class="font-14"><i class="fa fa-calendar fa-fw"></i> {{ preg_replace("/^(....).(..).(..)$/", "$3/$2/$1", $ticket->limit_date) }}</div>
                                @endif
                            </div>
                            @if ( $ticket->observers->count() )
                            <div class="col-5">
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
                                       <a href="{{ route('ticket.file.download',$attachment->path) }}" target="__blank">
                                           <{{ $attachment->type }} src="{{ $attachment->src }}" height="80">
                                       </a>
                                    </div>
                                    <div style="font-size: 10px">{{ $attachment->original_name }}</div>
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
                                                    @if ( $message->attachments->count() )
                                                        <hr>
                                                        <div class="row">
                                                            @foreach( $message->attachments as $attachment )
                                                                <div class="col-sm-2 col-3">
                                                                    <div style="background-color: #EEE; padding: 5px;">
                                                                        <div style="background-color: #FFF; padding: 5px; height: 50px;">
                                                                            <a href="{{ route('ticket.file.download',$attachment->path) }}" target="__blank">
                                                                                <{{ $attachment->type }} src="{{ $attachment->src }}" style="max-width: 100%; max-height: 100%">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
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
                                                    @if ( $message->attachments->count() )
                                                        <hr>
                                                        <div class="row">
                                                            @foreach( $message->attachments as $attachment )
                                                                <div class="col-sm-2 col-3">
                                                                    <div style="background-color: #EEE; padding: 5px;">
                                                                        <div style="background-color: #FFF; padding: 5px; height: 50px;">
                                                                            <a href="{{ route('ticket.file.download',$attachment->path) }}" target="__blank">
                                                                                <{{ $attachment->type }} src="{{ $attachment->src }}" style="max-width: 100%; max-height: 100%">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" action="{{ route("ticket.update.date.prior", [$ticket->id]) }}">
            {{ csrf_field() }}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterações no chamado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="limit_date">Data de expiração:</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control datepicker" id="limit_date" name="limit_date" value="{{ preg_replace("/^(....).(..).(..)$/", "$3/$2/$1", $ticket->limit_date ) }}">
                            <div class="input-group-append">
                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <small class="form-text text-muted">Após esta data o chamado será expirará e será solicitado atribuição de nota.</small>
                    </div>

                    <div class="form-group">
                        <label for="prior">{{ __('messages.prior') }} <b class="color-red">*</b></label><br/>
                        <select class="form-control selectpicker" id="prior" name="prior" data-field_name="{{__('messages.field_new_ticket_prior')}}">
                            @foreach( $priors as $prior )
                                <option value="{{ $prior->id }}" @if ( $prior->id == $ticket->prior_id ) selected @else {{ $prior->default ? "selected" : "" }} @endif>{{ $prior->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (\Auth::user()->is_admin)
                    <div class="form-group">
                        <label for="status">Situação do Chamado<b class="color-red">*</b></label><br/>
                        <select class="form-control selectpicker" id="status" name="status">
                            @foreach( $status as $statu )
                                <option value="{{ $statu->id }}" @if ( $statu->id == $ticket->status_id ) selected @endif>{{ $statu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                        @if ($ticket->rating !== null )
                        <div class="form-group">
                            <label for="remove_rating"><input type="checkbox" value="1" name="remove_rating" id="remove_rating"> Remover Nota</label><br/>
                        </div>
                        @endif
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('footer-js')
    <script src="{{ asset('js/editticket.js') }}?v={{ microtime() }}"></script>
    @if ( ($ticket->agent_user_id != \Auth::user()->id && $ticket->user_id != \Auth::user()->id) || $ticket->status->name != __('messages.ticket_status_created') )
    <script>
        @if ($ticket->status->name != __('messages.ticket_status_created'))
        $('div[contenteditable="true"]').css('background', '#EEE').html('Você não pode enviar mensagens pois o chamado não está aberto.').on('click', function() {
            $(this).html('Você não pode enviar mensagens pois o chamado não está aberto.').blur();
        });
        @else
        $('div[contenteditable="true"]').css('background', '#EEE').html('Você não pode enviar mensagens neste ticket pois não é responsável pelo mesmo.').on('click', function() {
            $(this).html('Você não pode enviar mensagens neste ticket pois não é responsável pelo mesmo.').blur();
        });
        @endif
    </script>
    @endif
    <script>
        $('#file-preview-button').scelUploader({
            input: {
                class: ["scel-preview-item-input"],
                name: "attachments"
            },
        });
    </script>
    <script>
        $('#close-ticket').click(function() {
            if ( confirm("Deseja realmente fechar este chamado?") ) {
                window.location.href = "{{ route('ticket.close', $ticket->id) }}"
            }
        });
        $('#edit-ticket').click(function() {
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        });
    </script>
@endsection