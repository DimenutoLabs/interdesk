@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ __('messages.tickets') }} / {{ __('messages.new') }}
                    </div>
                </div>
            </div>
            <div class="col-12 margin-top-30">
                <form method="post" action="{{ route('ticket.save') }}">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="small_title">{{ __('messages.ticket_small_subject') }} <b class="color-red">*</b></label>
                                <input type="text" class="form-control" id="small_title" name="small_title" placeholder="{{ __('messages.enter_subject') }}">
                                <small class="form-text text-muted">{{ __('messages.ticket_small_subject_description') }}</small>
                            </div>

                            <div class="form-group">
                                <label for="title">{{ __('messages.ticket_subject') }}</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('messages.enter_subject') }}">
                                <small class="form-text text-muted">{{ __('messages.ticket_subject_description') }}</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prior">{{ __('messages.prior') }} <b class="color-red">*</b></label>
                                        <select class="form-control" id="prior" name="prior">
                                            <option> -- </option>
                                            @foreach( $priors as $prior )
                                                <option value="{{ $prior->id }}">{{ $prior->name }}</option>
                                            @endforeach
                                        </select>
                                        {{--<input type="text" class="form-control" id="prior" name="prior" placeholder="{{ __('messages.choose_prior') }}">--}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">{{ __('messages.department') }}</label>
                                        <input type="text" class="form-control" id="department" name="department" placeholder="{{ __('messages.choose_department') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="limit_date">{{ __('messages.accept_date') }}</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control date datepicker" id="limit_date" name="limit_date">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="limit_date"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimated_time">{{ __('messages.estimated_time') }}</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control time" id="estimated_time" name="estimated_time">
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-focus-to="estimated_time"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="assigned_to">{{ __('messages.assigned_to') }}</label>
                                <input type="text" class="form-control" id="assigned_to" name="assigned_to" placeholder="{{ __('messages.choose_assignment') }}">
                            </div>

                            <div class="form-group">
                                <label for="observers">{{ __('messages.observers') }}</label>
                                <input type="text" class="form-control" id="observers" name="observers" placeholder="{{ __('messages.choose_observers') }}">
                            </div>

                            <div>
                                <div class="form-group">
                                    <label for="content">{{ __('messages.content') }} <b class="color-red">*</b></label>
                                    <textarea name="content" id="content"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fa fa-check fa-fw"></i> {{ __('messages.create_new_ticket') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-js')
    <script>
        $('.input-group-text').click(function() { $('#' + $(this).attr('data-focus-to') ).focus(); });
    </script>
@endsection