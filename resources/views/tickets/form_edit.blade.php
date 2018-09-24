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
                        <div><i class="fa fa-user fa-fw"></i> Criador do Ticket</div>
                        <div><i class="fa fa-envelope fa-fw"></i> emaildocriador@email.com</div>
                        <div class="color-gray"><span class="font-10">12/09/2018 @ 22:01:01</span> </div>
                    </div>
                    <div class="card-body">
                        <h6><i class="fa fa-comment-o fa-fw"></i> TITULO RESUMIDO</h6>
                        <div class="font-14 color-gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto blanditiis consequatur ipsam saepe similique sint.</div>
                        <hr>
                        <div>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto doloribus ducimus ea iste libero maiores minima. Accusantium, architecto atque commodi consectetur corporis cumque delectus est illo minima molestiae necessitatibus nostrum nulla obcaecati odio pariatur quidem reprehenderit repudiandae saepe, sapiente, voluptates. Eius facere id laborum perspiciatis quaerat similique vero voluptatem! Enim ex explicabo illo incidunt itaque iure labore officia perferendis ratione?
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
                        <div class="middle-line">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        <i class="fa fa-user fa-fw"></i> Eu: <small>(29/09/2018 @ 18:10:16)</small>
                                        <hr>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias, suscipit.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="offset-md-6 col-md-6">
                                    <div class="alert alert-info">
                                        <i class="fa fa-user fa-fw"></i> Coleguinha: <small>(29/09/2018 @ 18:10:16)</small>
                                        <hr>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolore.</div>
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
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="offset-md-6 col-md-6">
                                    <div class="alert alert-info">
                                        <i class="fa fa-user fa-fw"></i> Coleguinha: <small>(29/09/2018 @ 18:10:16)</small>
                                        <hr>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolore.</div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 col-3">
                                                <img src="https://picsum.photos/200/300/?random&4=1" style="width: 100%;">
                                            </div>
                                            <div class="col-sm-2 col-3">
                                                <img src="https://picsum.photos/200/300/?random&5=2" style="width: 100%;">
                                            </div>
                                            <div class="col-sm-2 col-3">
                                                <img src="https://picsum.photos/200/300/?random&6=3" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 margin-top-30">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-reply fa-fw"></i> {{ __('messages.reply_ticket') }}
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="conteudo">{{ __('messages.message') }} <b class="color-red">*</b></label>
                            <textarea name="conteudo" id="conteudo"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary"><i class="fa fa-check fa-fw"></i> {{ __('messages.reply_ticket') }}</button>
                    </div>
                </div>
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