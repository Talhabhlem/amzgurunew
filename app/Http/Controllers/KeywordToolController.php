<?php

namespace App\Http\Controllers;

use Auth;
use User;
use Input;
use Session;
use JavaScript;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scripts\ProductAdvertisingApi;

class KeywordToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // If the user isn't logged in, redirect to home.
        // Page loads conditionally based on a specific user's data
        if ($user == null) {
            return redirect('auth/login');
        }

        $user->load('ApiPaSettings');

        // If user doesn't not have valid API keys, then show API key add page
        if (!isset($user->ApiPaSettings) || $user->ApiPaSettings == null || $user->ApiPaSettings->has_api_keys == false) {

            $session = Session::all();
            if (!isset($session['message'])) {
                Session::flash('message', 'Please add your Product Advertising API keys before continuing');
                Session::flash('alert-class', 'alert-danger');
            }

            return view('keyword_tool.add_api_keys.addApiKeys');

        }
        // If user does, then show the dashboard
        else {

            // Get data to pass along
            $watchedProducts = $user->getWatchedProductsForDashboardTable();
            JavaScript::put(['testvar' => 'testing']);
            if (isset($watchedProducts[0])) {
                $detailData = $user->getDashboardDetailData($watchedProducts[0]->productkeyword);


                JavaScript::put(['watchedProduct' => $watchedProducts, 'detailData' => $detailData]);
//                JavaScript::put(['watchedProduct' => 'just a test']);

                $this->data['auth'] = Auth::check();
                $this->data['user'] = Auth::user();
                $this->data['watchedProducts'] = $watchedProducts;
            } else {

                Session::flash('message', 'It looks like you don\'t have any tracked produts yet. Let\'s add some.');
                Session::flash('alert-class', 'alert-info');
                return view('keyword_tool.add_product.index');
//                return 'no watched products';
            }

            return view('keyword_tool.dashboard.dashboard');
        }

        return ('test');
    }

    public function postApiKeys(Request $request)
    {

        // Validate input
        $this->validate($request, [
            'access_key' => 'required|max:100',
            'associate_tag' => 'required|max:100',
            'secret_key' => 'required|max:100'
        ]);

        if(!ProductAdvertisingApi::isValidKeys([
            'access_key' => $request['access_key'],
            'associate_tag' => $request['associate_tag'],
            'secret_key' => $request['secret_key']
        ])) {
            Session::flash('message', 'Invalid API keys!');
            Session::flash('alert-class', 'alert-danger');
//            Session::set(['message' => "Invalid API keys!"]);
            return redirect('/keywordTracker');
        }

        $user = Auth::user()->load('ApiPaSettings')->load('AmazonSettings');

        $apiKey = \App\ApiPaSettings::firstOrCreate(['user_id' => $user->id]);

        $marketplace = $user->AmazonSettings->first()->marketplace_id;
        $location = \App\Location::where('marketplace_id', $marketplace)->select('id')->first()->toArray()['id'];

        $apiKey->fill([
            'user_id' => $user->id,
            'access_key' => $request['access_key'],
            'associate_tag' => $request['associate_tag'],
            'secret_key' => $request['secret_key'],
            'has_api_keys' => 1,
            'location' => $location
        ]);
        $apiKey->save();

        Session::flash('message', 'Api keys submitted successfully.');
        Session::flash('alert-class', 'alert-danger');

        return redirect('keywordTracker');
    }

    public function addProduct()
    {
        return view('keyword_tool.add_product.index');
    }

    public function postProduct(Request $request)
    {
        // Validate input
        $this->validate($request, [
            'asin' => 'required|max:10|min:10',
            'keyword' => 'required|max:50'
        ]);

        $user = Auth::user();

        $isDuplicate = $user->saveProductKeyword($request['asin'], $request['keyword']);

        if ( !$isDuplicate ){
            Session::flash('message', 'Keyword and asin submitted successfully');
            Session::flash('alert-class', 'alert-info');
            return redirect('keywordTracker');
        } else {
            Session::flash('message', 'You are already tracking that keyword and asin!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('keywordTracker');
        }
    }

    public function removeProduct($id)
    {

        $user = Auth::user();

        \App\ProductKeywordPairs::where('user_id', $user->id)->where('product_keyword_list_id',$id)->firstOrFail()->delete();

        return 1;
    }

    public function addProductBulk()
    {
        return view('keyword_tool.add_product_bulk.index');
    }

    public function addProductBulkPost(Request $request)
    {
        $asin = trim(Input::get('asin'));
        $keywords = explode(PHP_EOL, Input::get('keywords'));

        // Validate input
        $this->validate($request, [
            'asin' => 'required|max:10|min:10',
            'keywords' => 'required'
        ]);

        $user = Auth::user();

        $results = [];
        foreach($keywords as $keyword) {
            $keyword = trim($keyword);
            if ($keyword == '') {continue; }

            $isDuplicate = $user->saveProductKeyword($asin, $keyword);

            if ( !$isDuplicate ){
                $results[$keyword] = 'Success';
            } else {
                $results[$keyword] = 'Duplicate';
            }

        }

        return view('keyword_tool.add_product_bulk.index')->with(['results' => $results]);
    }
}
