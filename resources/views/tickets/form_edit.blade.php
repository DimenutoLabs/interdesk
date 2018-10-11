@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ __('messages.tickets') }} / {{ __('messages.tickets') }} / {{ __('messages.edit') }}
                    </div>
                </div>
            </div>

            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <div><i class="fa fa-user fa-fw"></i> {{ $ticket->user->name }}</div>
                            <div><i class="fa fa-envelope fa-fw"></i> {{ $ticket->user->email }}</div>
                            <div class="color-gray"><span class="font-10">{{ $ticket->created_at->format('d/m/Y @ H:i:s') }}</span> </div>
                        </div>
                        <div class="float-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('messages.action') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6><i class="fa fa-comment-o fa-fw"></i> {{ mb_strtoupper($ticket->small_title) }}</h6>
                        <div class="font-14 color-gray">{{ $ticket->title }}</div>
                        <hr>
                        <div>
                            <?php echo $ticket->content; ?>
                        </div>
                    </div>
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
                <form method="post" action=" {{ route('ticket.update', [$ticket->id]) }}">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-reply fa-fw"></i> {{ __('messages.reply_ticket') }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="reply_content">{{ __('messages.message') }} <b class="color-red">*</b></label>
                                <textarea name="reply_content" id="reply_content"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fa fa-check fa-fw"></i> {{ __('messages.reply_ticket') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-history fa-fw"></i> {{ __('messages.ticket_history') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td style="width: 30%">
                                    <div>23/10/2018 @ 10:00:00</div>
                                    <div class="font-12 color-gray">Usuário / 192.168.0.1 </div>
                                </td>
                                <td style="width: 70%">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. A assumenda commodi dolores et eveniet exercitationem explicabo iste nostrum omnis voluptates?
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div>23/10/2018 @ 10:00:00</div>
                                    <div class="font-12 color-gray">Usuário / 192.168.0.1 </div>
                                </td>
                                <td style="width: 70%">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. A assumenda commodi dolores et eveniet exercitationem explicabo iste nostrum omnis voluptates?
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div>23/10/2018 @ 10:00:00</div>
                                    <div class="font-12 color-gray">Usuário / 192.168.0.1 </div>
                                </td>
                                <td style="width: 70%">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. A assumenda commodi dolores et eveniet exercitationem explicabo iste nostrum omnis voluptates?
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div>23/10/2018 @ 10:00:00</div>
                                    <div class="font-12 color-gray">Usuário / 192.168.0.1 </div>
                                </td>
                                <td style="width: 70%">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. A assumenda commodi dolores et eveniet exercitationem explicabo iste nostrum omnis voluptates?
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection