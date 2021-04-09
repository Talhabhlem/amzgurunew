@extends('layouts.master')

@section('title', trans_choice('user', 2))
@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Manage Users</h1>
    </div>
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
        <div id="view_users_table">
            @include('partials.users._list')
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Confirm Mode HTML -->
    <div class="hide">
        <a id="te_confirm_model_open" data-toggle="modal" href="#te_confirm_model_wrapper">Launch Modal</a>
    </div>

    <div tabindex="-1" role="dialog" aria-labelledby="exampleModalTitle" aria-hidden="true"
         id="exampleNiftyFlipVertical" class="modal fade modal-primary modal-3d-flip-vertical" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Import Users From CSV</h4>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;" id="import_status">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default margin-0" type="button">Close</button>
                    <button style="display: none;" class="btn btn-primary" type="button">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" style="display: none;" class="modal primary fade" id="te_confirm_model_wrapper">
        <!-- Modal dialog -->
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <!-- /Modal header -->

                <!-- Modal body -->
                <div class="modal-body">
                </div>
                <!-- /Modal body -->

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="te_confirm_true_click">Yes</button>
                </div>
                <!-- /Modal footer -->
            </div>
        </div>
        <!-- /Modal dialog -->
    </div>

    <script>
        function actions_user_form_success(thisForm, resp) {
            jQuery("#view_users_table").html(resp['view_table_html']);
            jQuery('.te-ajax-paginate').paginate_ajax();
            if (resp['is_error']) {
                jQuery("#view_users_table").prepend(bs_alert(resp['is_error'], resp['msg']));
            } else {
                jQuery("#view_users_table").prepend(bs_alert('success', resp['msg']));
            }
        }

        function toggleCheckboxes(isChecked) {
            checkboxes = document.getElementsByClassName('select_user');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = isChecked;
            }
            togglebulkbuttons(isChecked);
        }

        function togglebulkbuttons(isEnabled) {
            jQuery('.action_user_btn').each(function () {
                if (jQuery(this).data('action_type') == 'bulk') {
                    if (isEnabled) {
                        jQuery(this).removeAttr('disabled');
                    } else {
                        jQuery(this).attr('disabled', 'disabled');
                    }
                }
            });
        }
        jQuery(document).ready(function (e) {

            jQuery('body').on('change', '#select_all', function (e) {
                toggleCheckboxes(jQuery(this).is(':checked'));

            });

            jQuery('body').on('change', '.select_user', function (e) {
                if (jQuery(this).is(':checked')) {
                    togglebulkbuttons(true);
                } else {
                    if (!jQuery('.select_user').is(':checked')) {
                        togglebulkbuttons(false);

                    }
                }
            });

            jQuery('body').on('click', '.action_user_btn', function (e) {

                console.log('helo');
                var action = jQuery(this).data('action');
                jQuery('#user_action').val(action);

                var action_type = jQuery(this).data('action_type');
                var okey = false;

                if (action_type == 'bulk') {
                    checkboxes = document.getElementsByClassName('select_user');
                    for (var i = 0, n = checkboxes.length; i < n; i++) {
                        if (checkboxes[i].checked) {
                            okey = true;
                            break;
                        }
                    }
                }

                if (action_type == 'single') {
                    jQuery('.select_user').prop('checked', false);
                    var user_id = jQuery(this).data('user_id');
                    //alert(user_id);
                    document.getElementById('selected_user-' + user_id).checked = true;
                    okey = true;
                }
                if (okey) {
                    confirm_action('Want to ' + action + '?', 'Do you  want to ' + action + ' this User?', 'submit_user_form', 'cancel_callback');
                }
            });

        });
        function cancel_callback() {
            jQuery('.select_user').prop('checked', false);
        }
        var count = 0;
        function submit_user_form() {
            count++;
            console.log(count);
            jQuery('#actions_user_form').submit();
            stop_submit = false;
        }

        <?php
        $cuser = Auth::user();
        ?>
        function load_iboost_upload_system() {
            jQuery(function () {
                var upload_count = 0;
                jQuery('#fileupload').fileupload({
                    dataType: 'json',
                    beforeSend: function (xhr, data) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', jQuery('meta[name="_token"]').attr('content'));
                    },
                    add: function (e, data) {
                        upload_count++;
                        data.formData = {
                            'progress_id': upload_count,
                            'user_id': '{{$cuser->id}}'
                        };
                        data.submit();
                        jQuery('#panel-body-div').show_loading();
                    },
                    progress: function (e, data) {
                        if (data.context) {
                            var progress = Math.floor(data.loaded / data.total * 100);
                            data.context.find('label').html(progress + '%');
                        }
                    },
                    done: function (e, data) {
                        jQuery('#panel-body-div').hide_loading();
                        if (data.result.status == 'success') {

                            st = [];
                            st['ALREADY_EXISTS'] = 'User already exists with this email.';
                            st['SUCCESS'] = 'User successfully imported.';
                            st['ERROR'] = 'Error occured in DB.';
                            jQuery('#import_status').html("");
                            for (i in data.result.import_status) {
                                var alert_type = 'success';
                                if (data.result.import_status[i] == 'ERROR') {
                                    alert_type = 'danger';
                                } else if (data.result.import_status[i] == 'ALREADY_EXISTS') {
                                    alert_type = 'warning';
                                }
                                jQuery('#import_status').append(bs_alert(alert_type, st[data.result.import_status[i]], i));
                            }

                            jQuery('#exampleNiftyFlipVertical').modal('show');

                            jQuery("#view_users_table").html(data.result.view_table_html);
                            jQuery("#view_users_table").prepend(bs_alert('success', 'User(s) imported successfully.'));
                            load_iboost_upload_system();
                        } else {

                            jQuery("#view_users_table").prepend(bs_alert('error', 'Some error occurred.'));
                        }

                    }
                });
            });
        }


        jQuery(document).ready(function (e) {
            jQuery('#actions_user_form').on("success", function (event, thisForm, resp) {
                website_added = true;
                new_website_resp = resp;
                thisForm.reset_form();
//                var response = jQuery.parseJSON(resp);
                jQuery("#view_users_table").html(resp['view_table_html']);
            });
            jQuery('#view_users_table').on('click', '#change_csvfile_button', function (e) {
                e.preventDefault();
                jQuery('#fileupload').click();
            });
        });
        load_iboost_upload_system();
    </script>

@endsection