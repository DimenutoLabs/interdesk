@if ( !$hideIfBlank || count($sectionTickets) )
<div class="row margin-top-20">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="fa fa-bar-chart"></i> {{ $sectionName }} ({{ count($sectionTickets) }})</div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped table-responsive" style="width: 100%">

                    @if ( count($sectionTickets) )
                        <tr style="background-color: gray; color: #FFF" class="text-center">
                            <th style="border-right: #FFF">
                                Dados
                            </th>
                            <th>
                                Solicitante
                            </th>
                            <th>
                                Responsável
                            </th>
                            <th>
                                Título
                            </th>
                            <th>
                                Obs.
                            </th>
                            <th>
                                Ult. Resp.
                            </th>
                            <th>
                                @if ($sectionTickets[0]->status_id == $statusOpened->id )
                                Nov.
                                @else
                                Aval.
                                @endif
                            </th>
                            <th>
                                Ações
                            </th>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">
                                <h3 class="padding-full-15 color-gray">Não há chamados nesta seção <i class="fa-thumbs-o-up fa fa-fw"></i> </h3>
                            </td>
                        </tr>
                    @endif

                    @foreach( $sectionTickets as $ticket )
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div>#{{ str_pad($ticket->id, 5, "0", STR_PAD_LEFT) }}</div>
                                        <button class="btn btn-sm" style="background-color: {{ $ticket->prior->background }}; color: {{ $ticket->prior->color }}">{{ $ticket->prior->name }}</button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">{{ $ticket->user->name }}</div>
                                        <div class="color-gray font-12 line-26">{{ $ticket->created_at->format('d/m/Y @ H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div class=""><b>{{ $ticket->agent ? $ticket->agent->name : "----" }}</b></div>
                                        <div><i>{{ $ticket->department ? $ticket->department->name : "----" }}</i></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        {{ $ticket->small_title }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        @if ( $ticket->observers->count() )
                                            <button type="button" class="btn btn-success" data-toggle="tooltip" data-html="true" data-placement="bottom" title="@foreach ($ticket->observers as $observer) <i class='fa fa-fw fa-user'></i> {{ $observer->user->name }}<br/> @endforeach">
                                                <i class="fa fa-fw fa-user"></i>
                                            </button>
                                        @else
                                            ---
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">{{ $ticket->messages->last() ? $ticket->messages->last()->user->name : ""  }}</div>
                                        <div class="color-gray font-12 line-26">{{ $ticket->messages->last() ? $ticket->messages->last()->created_at->format('d/m/Y @ H:i') : ""  }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($ticket->status_id == $statusOpened->id)
                                <div class="text-center">
                                    @if( $number = $ticket->last_actions)
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-exclamation-circle fa-fw"></i> {{ $number }}</button>
                                    @endif
                                </div>
                                @else
                                <div class="text-center color-blue-star" id="rating-{{$ticket->id}}" data-ticket="{{ $ticket->id }}">
                                    @if ($ticket->rating !== null)
                                        {{$ticket->rating}} <i class="fa fa-fw fa-star"></i>
                                    @elseif ( $ticket->user_id != \Auth::user()->id )
                                        ---
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('ticket.edit', [$ticket->id]) }}"><button class="btn btn-info btn-sm" style="color: #FFF"><i class="fa fa-fw fa-binoculars"></i></button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endif