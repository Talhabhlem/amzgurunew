<div class="login-form-1">
    <form method="POST" action="keywordTracker/addApiKeys" class="text-left">
        {!! csrf_field() !!}

        <div class="col-md-5">
            {{--<div class="login-group">--}}

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

                <div class="form-group">
                    <label for="access_key">Access Key:</label>
                    <input type="text" name="access_key" id="access_key" class="form-control" value="{{ old('access_key') }}">
                </div>
                <div class="form-group">
                    <label for="associate_tag">Associate Tag:</label>
                    <input type="text" name="associate_tag" id="associate_tag" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="secret_key">Secret Key:</label>
                    <input type="text" name="secret_key" id="secret_key" class="form-control" value="">
                </div>

                <br>
                <div>
                    <button type="submit" style="width:100%" class="btn btn-primary">Submit</button>
                </div>
            {{--</div>--}}
        </div>
    </form>
</div>