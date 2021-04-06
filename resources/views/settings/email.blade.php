@extends('layouts.master')

@section('page-header')
    <div class="content-heading">
        Email Settings
        <small data-localize="dashboard.WELCOME"></small>
    </div>
@endsection

@section('title', 'Email Settings- AMZ')

@section('site-menu-footer')
    <a href="{{url('settings/amazon')}}" class="fold-show">
        <span class="icon fa fa-key" aria-hidden="true"></span>
    </a>
    <a href="{{url('settings/changepassword')}}" class="fold-show">
        <span class="icon fa fa-unlock-alt" aria-hidden="true"></span>
    </a>
    <a href="{{url('auth/logout')}}" class="fold-show">
        <span class="icon wb-power" aria-hidden="true"></span>
    </a>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            {!! trans('lcp::auth.error') !!}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="panel-body">
        <form role="form" class="te-ajax-form" action="settings/ajax_save_email_settings" id="email_setting_form">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group form-material">
                        <label class="control-label" for="">Email Alert Types</label>

                        <?php
                        $daily_hours_left   = '';
                        $weekly_time_left   = '';
                        $monthly_time_left  = '';
                        $now_hour           = (int) date( 'H' );
                        $daily_hours_left   = 8 - $now_hour;

                        if ( $daily_hours_left != 0 ) {
                            $daily_hours_left = 24 + $daily_hours_left;
                        }

                        $daily_hours_left = $daily_hours_left.' hours left';

                        if($cuser->weekly_email_time=='' || $cuser->weekly_email_time=='0000-00-00 00:00:00') {
                            $weekly_time_left = $daily_hours_left;
                        } else {
                            $last_w = \Carbon\Carbon::createFromTimestamp(strtotime($cuser->weekly_email_time));
                            $weekly_time_left = $last_w->addWeek(1)->diffForHumans();
                        }

                        if($cuser->monthly_email_time=='' || $cuser->monthly_email_time=='0000-00-00 00:00:00') {
                            $monthly_time_left = $daily_hours_left;
                        } else {
                            $last_w = \Carbon\Carbon::createFromTimestamp(strtotime($cuser->monthly_email_time));
                            $monthly_time_left = $last_w->addMonth(1)->diffForHumans();
                        }

                        ?>

                        <div class="form-group">
                            <label class="control-label">Daily Alert | Send Daily Alerts {{$daily_hours_left}}</label>
                            <div class="col-sm-10">
                                <label class="switch switch-lg">
                                    <input type="checkbox" name="package[]" value="daily" {{in_array('daily',$email->package) ? 'checked':''}}>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Weekly Alert | Send Weekly Alerts {{$weekly_time_left}}</label>
                            <div class="col-sm-10">
                                <label class="switch switch-lg">
                                    <input type="checkbox" name="package[]" value="weekly" {{in_array('weekly',$email->package) ? 'checked':''}}>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Monthly Alert | Send Monthly Alerts {{$monthly_time_left}}</label>
                            <div class="col-sm-10">
                                <label class="switch switch-lg">
                                    <input type="checkbox" name="package[]" value="monthly" {{in_array('monthly',$email->package) ? 'checked':''}}>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-material">
                        <label class="control-label" for="email">Alternate Email for Alerts</label>
                        <input style="width: 320px;" type="text" class="form-control validate-optional-email" id="email" name="email" placeholder="Enter an alternate email for alerts (optional)" value="{{$email->email}}" />
                    </div>

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn-primary btn">Save Settings</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group form-material">
                        <label class="control-label" for="email">Email Alert Status</label>
                    </div>
                    @if($cuser->email_time=='' || $cuser->email_time=='0000-00-00 00:00:00')
                    <div class="alert alert-alt alert-danger alert-dismissible" role="alert">
                        No <strong>Daily</strong> email Alert has been sent till now.
                    </div>
                    @else
                    <div class="alert alert-alt alert-info alert-dismissible" role="alert">
                        Last <strong>Daily</strong> Email was sent on <strong style="color:#baffff">{{date('H:i A d-M-Y',strtotime($cuser->email_time))}}</strong>. <span style="display:none">{{"NOW : ".date('H:i A d-M-Y')}}</span>
                    </div>
                    @endif

                    @if($cuser->weekly_email_time=='' || $cuser->weekly_email_time=='0000-00-00 00:00:00')
                    <div class="alert alert-alt alert-danger alert-dismissible" role="alert">
                        No <strong>Weekly</strong> email Alert has been sent till now.
                    </div>
                    @else
                    <div class="alert alert-alt alert-info alert-dismissible" role="alert">
                        Last <strong>Weekly</strong> Email was sent on <strong style="color:#baffff">{{date('H:i A d-M-Y',strtotime($cuser->weekly_email_time))}}</strong>. <span style="display:none">{{"NOW : ".date('H:i A d-M-Y')}}</span>
                    </div>
                    @endif
                    @if($cuser->monthly_email_time=='' || $cuser->monthly_email_time=='0000-00-00 00:00:00')
                    <div class="alert alert-alt alert-danger alert-dismissible" role="alert">
                        No <strong>Monthly</strong> email Alert has been sent till now.
                    </div>
                    @else
                    <div class="alert alert-alt alert-info alert-dismissible" role="alert">
                        Last <strong>Monthly</strong> Email was sent on <strong style="color:#baffff">{{date('H:i A d-M-Y',strtotime($cuser->monthly_email_time))}}</strong>. <span style="display:none">{{"NOW : ".date('H:i A d-M-Y')}}</span>
                    </div>
                    @endif
                </div>
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    {{-- <script src="{!!url('assets/js/components/jquery-labelauty.js')!!}"></script>
    <script src="{!!url('assets/vendor/jquery-labelauty/jquery-labelauty.js')!!}"></script> --}}
    <script type="text/javascript">
        function email_setting_form_success(thisForm, resp) {
            thisForm.find('.bs-alert').remove();
            thisForm.prepend(bs_alert('success','Setting updated successfully.'));
        }
    </script>
@endsection