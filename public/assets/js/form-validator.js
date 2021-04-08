var animation = '';
var animate_count=  0;
function validate_email($email) {
    if($email=='') return false;
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test( $email ) ) {
        return false;
    } else {
        return true;
    }
}

function validate_url(url) {
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

function validate_password(password) {

    if(password=='') {
        return false;
    } else if(password.length < 4) {
        return false;
    }
    return true;

}

function bs_alert(type,msg) {
    var html = '<div class="bs-alert alert alert-'+type+' alert-dismissable fade in te_animated2  te_flipOutX2">';
    html += '<button class="close" data-dismiss="alert">×</button>';
    html += msg;
    html += '</div>';
    return html;
}

function te_ajax(action, serialized_data, success_func, fail_func) {

    lastrequest = jQuery.ajax({
        type: "POST",
        url: base_url+action,
        data: serialized_data,
        dataType: 'json',
        cache: false ,
        headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
        success: function(resp){

            if( resp['success'] == true ) {
                success_func(resp);

            } else if( resp['success'] == false ) {

                fail_func(resp);

            }

        },
        error: function(response){

            if ( response.status == 401) { //user is unauthorized
                window.location = base_url;
            } else {
                //thisForm.hide_loading();
                //thisForm.prepend(bs_alert('danger','Error while sending request'));
            }

            alert('Error while sending request');

        }
    });

}


jQuery.fn.extend({
    show_loading: function () {

        jQuery(this).css('position','relative').prepend('<div class="te-loading"></div>');

    },
    hide_loading: function () {
        jQuery.each(jQuery(this).find('.te-loading'), function (index, element) {
            jQuery(element).remove();

        });
    },
    has_scrollbar : function() {
        return jQuery(this).prop('scrollHeight') > jQuery(this).height();
    },

    submit_form: function () {

        var thisForm = jQuery(this);
        thisForm.show_loading();
        var action = '';
        var serialized_data = '';
        if(thisForm.data('form_type') && thisForm.data('form_type')=='div') {
            action = thisForm.data('action');
            serialized_data = thisForm.find('input[name],select[name],textarea[name]').serialize();
        } else {
            action = thisForm.attr('action');
            serialized_data = thisForm.serialize();
        }
        jQuery.ajax({
            type: "POST",
            url: base_url+"/"+action,
            data: serialized_data,
            dataType: 'json',
            cache: false ,
            headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
            success: function(data){
                console.log(data);
                thisForm.hide_loading();
                thisForm.find('.myError').remove();
                if(data.success == false)
                {
                    //var arr = data.errors;
                    //$.each(arr, function(index, value)
                    //{
                    //    if (value.length != 0)
                    //    {
                    //        $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
                    //    }
                    //
                    //});
                    //$("#validation-errors").show();
                    //thisForm.trigger( "fail" , [thisForm, data]);
                    for(i in data.errors) {
                        thisForm.find('#'+resp['errors'][i]['field']).parents('.form-group').addClass('has-error').append('<span class="help-block">'+resp['errors'][i]['error']+'</span>');
                    }
                } else {
                    window[thisForm.attr('id')+"_success"](thisForm,data);
                }
            },
            error: function(response){

                if ( response.status == 401) { //user is unauthorized
                    window.location = base_url;
                } else {
                    thisForm.hide_loading();
                    thisForm.prepend(bs_alert('danger','Error while sending request'));
                }

            }
        });
    },

    reset_form: function () {

        jQuery.each(jQuery(this).find('input, textarea, select'), function (index, element) {

            if(!jQuery(element).hasClass('donotreset')) {
                jQuery(element).val('');
            }
        });
    },

    validate_form: function(){

        var okey = true;

        jQuery(this).find('.errorForm').remove();
        jQuery(this).find('.form-group').removeClass('has-error');

        jQuery.each(jQuery(this).find('input, textarea, select'), function (index,element) {
            var thisOkey = true;
            if(!jQuery(this).is(':disabled')) {

                if(jQuery(this).hasClass('required')) {

                    if(jQuery(this).val() == '') {
                        error = 'Requied field';
                        thisOkey = okey = false;

                    } else {
                        if(this.name == 'credit_card_expiration_month' || this.name == 'credit_card_expiration_year') {

                            var d = new Date();
                            var month = d.getMonth()+1;
                            var year = d.getFullYear();

                            var cc_month = parseInt(jQuery('#credit_card_expiration_month').val())
                            var cc_year = parseInt(jQuery('#credit_card_expiration_year').val())

                            if((year > cc_year) || (year == cc_year && month > cc_month)) {
                                error = 'Invalid Expiry Date';
                                thisOkey = okey = false;
                            }
                        }
                    }
                } else if(jQuery(this).hasClass('validate-email')) {
                    if(!validate_email(jQuery(this).val())) {
                        error = 'Invalid Email Address';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-password')) {
                    if(!validate_password(jQuery(this).val())) {
                        error = 'Invalid Password';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-confirm-password')) {
                    var confirm_pass = jQuery(this).val();
                    var pass = jQuery('#'+jQuery(this).data('match')).val();
                    if(pass != confirm_pass) {
                        error = 'Password does not match';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-url')) {
                    if(!validate_url(jQuery(this).val())) {
                        error = 'Invalid URL';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-card')) {
                    jQuery('#credit_card_number').validateCreditCard(function(result){
                        if (result.card_type == null) {
                            error = "Invalid Card.";
                            thisOkey = okey = false;
                            jQuery('#card_span').html('');
                            jQuery('#card_name').val('');
                            return;
                        }
                        if( result.length_valid) {
                            thisOkey = okey = false;
                            jQuery('#card_span').html(result.card_type.name);
                            jQuery('#card_name').val(result.card_type.name);
                        } else {
                            thisOkey = okey = false;
                            jQuery('#card_span').html('');
                            jQuery('#card_name').val('');
                        }
                    });
                }
                if(!thisOkey) {

                    jQuery(this).mark_err(error);

                }

            }

        });
        if(!okey) {
            animate_count++;
        }
        return okey;
    },

    validate_form2: function(){

        var okey = true;

        jQuery(this).find('.errorForm').remove();
        jQuery(this).find('.form-group').removeClass('has-error');

        jQuery.each(jQuery(this).find('input, textarea, select'), function (index,element) {
            var thisOkey = true;
            if(!jQuery(this).is(':disabled')) {

                if(jQuery(this).hasClass('required')) {

                    if(jQuery(this).val() == '') {
                        error = 'Requied field';
                        thisOkey = okey = false;

                    } else {
                        if(this.name == 'credit_card_expiration_month' || this.name == 'credit_card_expiration_year') {

                            var d = new Date();
                            var month = d.getMonth()+1;
                            var year = d.getFullYear();

                            var cc_month = parseInt(jQuery('#credit_card_expiration_month').val())
                            var cc_year = parseInt(jQuery('#credit_card_expiration_year').val())

                            if((year > cc_year) || (year == cc_year && month > cc_month)) {
                                var error = 'Invalid Date';
                                thisOkey = okey = false;
                            }
                        }
                    }
                } else if(jQuery(this).hasClass('validate-email')) {
                    if(!validate_email(jQuery(this).val())) {
                        error = 'Invalid Email Address';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-password')) {
                    if(!validate_password(jQuery(this).val())) {
                        error = 'Invalid Password';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-confirm-password')) {
                    var confirm_pass = jQuery(this).val();
                    var pass = jQuery('#'+jQuery(this).data('match')).val();
                    if(pass != confirm_pass) {
                        error = 'Password does not match';
                        thisOkey = okey = false;
                    }
                } else if(jQuery(this).hasClass('validate-card')) {
                    jQuery('#credit_card_number').validateCreditCard(function(result){
                        if (result.card_type == null) {
                            error = "Invalid Card Number.";
                            thisOkey = okey = false;
                            jQuery('#card_span').html('');
                            jQuery('#card_name').val('');
                            return;
                        }
                        if( result.length_valid) {
                            jQuery(this).parents('.form-group').addClass('has-error');
                            jQuery('#card_span').html(result.card_type.name);
                            jQuery('#card_name').val(result.card_type.name);
                        } else {
                            jQuery(this).parents('.form-group').addClass('has-error');
                            jQuery('#card_span').html('');
                            jQuery('#card_name').val('');
                        }
                    });
                }

                if(!thisOkey) {

                    jQuery(this).mark_error(error);

                }
            }


        });
        if(!okey) {
            animate_count++;
        }
        return okey;
    },

    mark_error: function (error) {

        var err_html = '<div class="errorForm"><label class="error">'+error+'</label></div>';
        jQuery(this).parents('.form-group').addClass('has-error').parent().append(err_html);
        jQuery(this).parents('.form-group').parent().removeClass('animated').removeClass('rubberBand').removeClass('rubberBand_again');
        if(animate_count%2 == 0) {
            animation = "rubberBand";
        } else {
            animation = "rubberBand_again";
        }
        //alert(1);
        jQuery(this).parents('.form-group').parent().addClass('animated '+animation);

    },

    mark_err: function (error) {

        var err_html = '<div class="errorForm" style="margin-top:5px;"><label class="error">'+error+'</label></div>';
        jQuery(this).parents('.form-group').addClass('has-error').append(err_html);
        jQuery(this).parents('.form-group').removeClass('animated').removeClass('rubberBand').removeClass('rubberBand_again');
        if(animate_count%2 == 0) {
            animation = "rubberBand";
        } else {
            animation = "rubberBand_again";
        }
        //alert(1);
        jQuery(this).parents('.form-group').addClass('animated '+animation);

    },

    paginate_ajax: function () {

        var thisObj = jQuery(this);
        var thisObj_id = thisObj.attr('id');

        console.log( jQuery(this) );
        jQuery('body').on('click', '#'+thisObj_id+'_pagination .pageno', function (e) {

            var action = thisObj.data('action');
            var order_by = thisObj.data('order_by');
            var order = thisObj.data('order');
            var thisForm = jQuery('#'+thisObj_id+"_search_form");
            var otherParams = jQuery('#'+thisObj_id+"_search_form").serialize();

            jQuery('#'+thisObj_id+"_pagination .pageno")
            var page_no = jQuery(this).attr('data-pageno');

                        var loading_target = thisObj.data('loading_target');
            jQuery(loading_target).show_loading();


            var querystring = 'order_by='+order_by+'&order='+order+'&pageno='+page_no+"&"+otherParams;
            jQuery.ajax({
                headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
                type: "POST",
                url: base_url+action,
                data: querystring,
                cache: false,
                success: function(response){

                                var loading_target = thisObj.data('loading_target');
            jQuery(loading_target).show_loading();


                    var resp = eval("("+response+")");

                    if( resp['status'] == 'success' ) {
                        jQuery("#view_"+thisObj_id).html(resp['view_table_html']);
                        window[thisObj_id+"_search_form_success"](thisForm,resp);
                        //jQuery("#"+thisObj_id).paginate_ajax();
                        //jQuery('.te-ajax-paginate').paginate_ajax();
                    }

                }
            });
        });
    },
    sort_ajax: function () {


        var thisObj = jQuery(this);
        var thisObj_id = thisObj.attr('id');
        jQuery('body').on('click', '#'+thisObj_id+' thead th.enablesort a', function (e) {

            e.preventDefault();
            var tableth = jQuery(this);
            if ( tableth.hasClass('sorting') || tableth.hasClass('asc') ) {
                thisObj.data('order','ASC');
                thisObj.data('order_by',tableth.data('sortby'));
                //alert('DESC');
            }  else {
                thisObj.data('order','DESC');
                thisObj.data('order_by',tableth.data('sortby'));
                //alert('ASC');
            }


            var action = thisObj.data('action');
            var order_by = thisObj.data('order_by');
            var order = thisObj.data('order');
            var otherParams = jQuery('#'+thisObj_id+"_search_form").serialize();

            jQuery('#'+thisObj_id+"_pagination .pageno")

            var page_no = 1;

            var loading_target = thisObj.data('loading_target');
            console.log()
            jQuery(loading_target).show_loading();


            var querystring = 'order_by='+order_by+'&order='+order+'&pageno='+page_no+"&"+otherParams;
            jQuery.ajax({
                headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
                type: "POST",
                url: base_url+action,
                data: querystring,
                cache: false,
                success: function(response){
                    var loading_target = thisObj.data('loading_target');
                    jQuery(loading_target).show_loading();
                    var resp = eval("("+response+")");

                    if( resp['status'] == 'success' ) {
                        jQuery("#view_"+thisObj_id).html(resp['view_table_html']);

                        //jQuery("#"+thisObj_id).paginate_ajax();
                        //jQuery('.te-ajax-paginate').paginate_ajax();
                    }

                }
            });
        });
    }
});

jQuery(document).ready(function(e) {

    jQuery('body').on('keyup','.enter-to-submit', function(event){
        if ( event.which == 13 ) {
            jQuery(this).parents('.te-form-div').find('.te-submit-btn').click();
        }
    });

    jQuery('body').on('click', '.te-submit-btn', function (e) {

        e.preventDefault();
        var thisbtn = jQuery(this);
        var submit_target = jQuery(this).data('submit_target');
        if ( typeof submit_target == 'undefined' || submit_target == '' ) {
            return;
        }
        //console.log( submit_target );
        var thisForm = jQuery('#'+submit_target);

        if(thisForm.validate_form()) {

            thisForm.submit_form();

        }
    });

    jQuery('body').on('submit', '.te-ajax-form', function (e) {

        e.preventDefault();
        var thisForm = jQuery(this);
        if(thisForm.validate_form()) {

            thisForm.submit_form();

        }

    });

    jQuery('.te-ajax-paginate').each(function(){
        jQuery(this).paginate_ajax();
    })

    jQuery('.te-ajax-sort').each(function(){
        jQuery(this).sort_ajax();
    })

    jQuery('body').on('click', 'ul.te-tabs li', function (e) {
        e.preventDefault();
        if(!jQuery(this).hasClass('locked')) {
            jQuery(this).addClass('active').siblings('li').removeClass('active');
            jQuery('#'+jQuery(this).data('target')).show().siblings('.te-tabs-content').hide();
        }
    });

    jQuery('body').on('click', '.next-step-btn', function (e) {
        var thisNextBtn = jQuery(this);
        var nextStep = thisNextBtn.data('target_step');
        var thisStep = thisNextBtn.parents('.te-tabs-content');
        if(thisStep.validate_form()) {
            thisNextBtn.parents('.te-tabs-wrapper').find('.step-tab-'+nextStep).removeClass('locked').click();
        }
    });

    jQuery('body').on('click', '.prev-step-btn', function (e) {
        var thisPrevBtn = jQuery(this);
        var target_step = thisPrevBtn.data('target_step');
        thisPrevBtn.parents('.te-tabs-wrapper').find('.step-tab-'+target_step).removeClass('locked').click();
    });

    jQuery('input.donotsubmit,select.donotsubmit').keypress(function(event) {
        return event.keyCode != 13;
    });

    jQuery('body').on('change', '.select_all', function (e) {
        var checked = jQuery(this).is(':checked');
        var checkboxelement;
        jQuery(jQuery(this).data('binding')).each(function(index, element) {
            jQuery(element).prop('checked',checked);
            checkboxelement = element;

        });

        jQuery(checkboxelement).change();
    });

    jQuery('body').on('click', '.toggle_checkbox', function (e) {
        e.preventDefault();

        var checked = jQuery('#'+jQuery(this).data('for')).is(':checked');
        jQuery('#'+jQuery(this).data('for')).prop('checked',true );
        return false;
    });

    jQuery('body').on('change', '.table-scrollable table', function (e) {
        //alert('table changed');
    });

});

function adjust_scrollbar_width() {
    jQuery('.table-scrollable').each(function(index, element) {
        if(jQuery(element).has_scrollbar()) {
            //alert(1);

            jQuery(element).parent().find('.table-header-scrollable').each(function(index2, element2) {
                //alert(1);
                jQuery(element2).css('overflow-y','scroll');
            });
        } else {
            jQuery(element).parent().find('.table-header-scrollable').each(function(index2, element2) {
                jQuery(element2).css('overflow-y','auto');
            });
        }
    });
}

function confirm_action(title,message,confirm_function,cancel_function,data){
    if(typeof(data)=='undefined'){
        var data = {};
    }
    if( jQuery('#te_confirm_model_open').length>0 ){
        jQuery('#te_confirm_model_open').click();
        jQuery('#te_confirm_model_wrapper .modal-body').html(message);
        jQuery('#te_confirm_model_wrapper .modal-title').html(title);
    }
    jQuery('body').on('click','#te_confirm_true_click', function(){
        window[confirm_function](data);
        jQuery('#te_confirm_model_wrapper [data-dismiss] ').click();
        delete confirm_function;
    });
    jQuery('body').on('click','#te_confirm_model_wrapper [data-dismiss]', function(){
        if ( window[cancel_function] ) {
            window[cancel_function](data);
            delete cancel_function;
        }

    });
    return false;
}

function addTimezones() {
    moment.tz.add('America/Los_Angeles|PST PDT|80 70|0101|1Lzm0 1zb0 Op0');
}

(function( $ ){
    'use strict';
    $.fn.makeClock = function() {
        var $clock = this;
        setInterval(function(){
            var clockFormat = 'YYYY-MM-DD HH:mm:ss';
            addTimezones();
            var clockTime = moment().tz('America/Los_Angeles');//.format(clockFormat);
            $clock.html(clockTime.format('DD-MMM-YYYY hh:mm:ss a z'));
        },1000)

    }
}( jQuery ));