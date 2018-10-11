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
                                        <select class="form-control selectpicker" id="prior" name="prior">
                                            @foreach( $priors as $prior )
                                                <option value="{{ $prior->id }}" {{ $prior->default ? "selected" : "" }}>{{ $prior->name }}</option>
                                            @endforeach
                                        </select>
                                        {{--<input type="text" class="form-control" id="prior" name="prior" placeholder="{{ __('messages.choose_prior') }}">--}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">{{ __('messages.department') }}</label>
                                        <select class="form-control selectpicker" id="department" name="department">
                                            <option></option>
                                            @foreach( $departments as $department )
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
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
                                <select class="form-control selectpicker same_value" data-same="user" id="assigned_to" name="assigned_to" >
                                    <option></option>
                                    @foreach( $users as $user )
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="observers">{{ __('messages.observers') }}</label>
                                <select type="text" class="form-control selectpicker same_value" data-same="user" id="observers" name="observers" multiple>
                                    @foreach( $users as $user )
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
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

        $('.same_value').on('change', function() {
            let self = $(this);
            var values = ($(this).val() + "").split(",");
            var same = $(this).attr('data-same');
            $('.same_value').each( function() {
                if ( self.html() !== $(this).html() ) {
                    $(this).children().prop('disabled', false);
                    for ( value in values ) {
                        console.log( values[value] );
                        $(this).find('option[value="' + values[value] + '"]').prop('disabled', true);
                    }
                }
            })
            $('.selectpicker').select2({
                allowClear: true,
                placeholder: '---'
            });
        });
    </script>
@endsection