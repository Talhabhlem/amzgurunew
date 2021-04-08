
<form method="POST" action="addProductBulk" class="text-left">

    {!! csrf_field() !!}

    <div class="col-md-5">
        <div class="login-group">
            <div class="form-group">
                <label for="asin">ASIN:</label>
                <input type="text" name="asin" id="asin" class="form-control" value="{{ old('asin') }}">
            </div>
            <div class="form-group">
                <label for="keyword">Keywords:</label>
                <textarea type="text" rows="10" placeholder="Enter keywords here. One per line" name="keywords" id="keywords" class="form-control" value="{{ old('keyword') }}"></textarea>
            </div>

            <br>
            <div>
                <button type="submit" style="width:100%" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</form>
