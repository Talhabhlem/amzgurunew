
<form method="POST" action="addProduct" class="text-left">

    {!! csrf_field() !!}

    <div class="col-md-5">
        <div class="login-group">
            <div class="form-group">
                <label for="asin">ASIN:</label>
                <input type="text" name="asin" id="asin" class="form-control" value="{{ old('asin') }}">
            </div>
            <div class="form-group">
                <label for="keyword">Keyword:</label>
                <input type="text" name="keyword" id="keyword" class="form-control" value="{{ old('keyword') }}">
            </div>

            <br>
            <div>
                <button type="submit" style="width:100%" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</form>
