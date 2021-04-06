<?php
if (isset($_GET) && isset($_GET['success'])) {
    $success = $_GET['success'];
} else {
    $success = -1;
}
?>
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        background: red;
        cursor: inherit;
        display: block;
    }
    input[readonly] {
        background-color: white !important;
        cursor: text !important;
    }
</style>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script>
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });

</script>

<div class="col-md-5">
    {{--    <form action="{{ url('upcToolPost') }}" id="myForm" method="post" enctype="multipart/form-data">--}}
    <form action="{{ url('/../amazonupc/run.php') }}" id="myForm" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <label>CSV File</label>
        <div class="input-group row">
    <span class="input-group-btn">
        <span class="btn btn-primary btn-file">
            Browse&hellip; <input type="file" name="upcfile" required="required" id="login">
        </span>
    </span>
            <input type="text" class="form-control" readonly>
        </div>
        <br>

        <div class="row">
            <div class="">
                <label for="">Marketplace</label>
                <select name="marketplace" id="marketplace" class="form-control">
                    <option>US</option>
                    <option>DE</option>
                    <option>UK</option>
                    <option>FR</option>
                    <option>ES</option>
                </select>
            </div>
        </div>
        <br>

        <div class="row">
            <label for="exampleInputEmail1">Referral Fee</label>
            <input type="text" name="refFee" class="form-control" id="referralFee" value="0.85" placeholder="0.85" required>
        </div>
        <br>

        <div class="row">
            <label for="exampleInputEmail1">Email</label>
            <input type="text" name="email" class="form-control" id="email" placeholder="Email" required>
        </div>
        <br>

        <div class="form-group">
            <button class="btn btn-primary" name="submit" type="submit" id="submitButton" style="width:100%">Go</button>
        </div>

        <div class="row" align="center"><b>
            @if ($success == 1)
                Success! An email has been sent to you.
            @elseif ($success == 0)
                <span style="font-color:red">Uhh oh! An error occurred.</span>
            @endif
        </b></div>
        {{--<div class="btn btn-primary" type="submit" id="submitButton" style="width:100%">Go</div>--}}
    </form>
</div>
