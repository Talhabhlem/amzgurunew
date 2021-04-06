<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class CalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $data = array();
        $productDetail = '';
        if(Input::all()){
            $input = Input::all();
            if(isset ($input['pricing']) || isset ($input['revenue'])){
                $Revenue = $input['pricing'] + $input['shipping'];
                if($input['pricing'] || $input['shipping']){
                    $referral_fees = $Revenue*15/100;
                }else{
                    $referral_fees = '1.00';
                }
                if($input['revenue']){
                    $data['amazon_referral_fee'] = $input['revenue']*15/100;
                }else{
                    $data['amazon_referral_fee'] = '1.00';
                }
                
                $data['referral_fees'] = $referral_fees;
                $data['fulfillment_cost_subtotal'] = $input['order-handling']+$input['pick-pack']+$input['outbound-delivery']+$input['weight-handling']+$input['storage']+$input['inbound-delivery']+$input['customer-service']+$input['prep-service'];
		$Cost = $input['order-handling']+$input['pick-pack']+$input['outbound-delivery']+$input['weight-handling']+$input['storage']+$input['inbound-delivery']+$input['customer-service']+$input['prep-service']+$referral_fees;
		$data['cost_subtotal'] = $Cost;
                $margin_impact = $Revenue - $Cost;
                $data['revenue'] = $Revenue;
                $data['amazon_revenue'] = $input['revenue'];
                $data['cost'] = $Cost;
                $data['margin_impact'] = $margin_impact;
                $productDetail = session('product_detail');
                $data['product_detail'] = $productDetail;
                $afnPriceStr = ($input['revenue']) ? $input['revenue'] : '0';
                $shipping = ($input['shipping']) ? $input['shipping'] :'0';
                $pricing = ($input['pricing']) ? $input['pricing'] :'0';
		$json = '{"productInfoMapping":{"asin":"'.$productDetail['data']['0']['asin'].'","binding":"'.$productDetail['data']['0']['binding'].'","dimensionUnit":"'.$productDetail['data']['0']['dimensionUnit'].'","dimensionUnitString":"'.$productDetail['data']['0']['dimensionUnitString'].'","encryptedMarketplaceId":"'.$productDetail['data']['0']['encryptedMarketplaceId'].'","gl":"'.$productDetail['data']['0']['gl'].'","height":'.$productDetail['data']['0']['height'].',"imageUrl":"'.$productDetail['data']['0']['imageUrl'].'","isWhiteGloveRequired":false,"length":'.$productDetail['data']['0']['length'].',"link":"'.$productDetail['data']['0']['link'].'","originalUrl":"'.$productDetail['data']['0']['originalUrl'].'","productGroup":"'.$productDetail['data']['0']['productGroup'].'","subCategory":"'.$productDetail['data']['0']['subCategory'].'","thumbStringUrl":"'.$productDetail['data']['0']['thumbStringUrl'].'","title":"'.$productDetail['data']['0']['title'].'","weight":'.$productDetail['data']['0']['weight'].',"weightUnit":"'.$productDetail['data']['0']['weightUnit'].'","weightUnitString":"'.$productDetail['data']['0']['weightUnitString'].'","width":'.$productDetail['data']['0']['width'].'},"afnPriceStr":'.$afnPriceStr.',"mfnPriceStr":'.$pricing.',"mfnShippingPriceStr":'.$shipping.',"currency":"USD","marketPlaceId":"ATVPDKIKX0DER","hasFutureFee":false,"futureFeeDate":"2015-08-06 00:00:00"}';
		
		$header = array(
			"Accept: application/json, text/javascript, */*; q=0.01",
			"Content-Type: application/json",
			'Cookie: x-wl-uid=1/rFBYfu2EUU8SJAH1GQ4Yh200yldpFdxNxI+olZ+5stAX2fSbYqZ/ms5gftO0XuF/xA0MRXxrMw=; session-id=188-0845010-7041431; session-id-time=2082787201l; session-token=AUWnjk8Xgyf81Wh50q05d67y5I1nkQugWR+IebR6h18AGuln3qBcVr5MliLKpqJTeFi+vV9rj4BAL0NKAO515OJZeHcz+le9jJusjX6WZ2Y3X2B2eQXAXoPsff3MMRNbdXXfjMJrDcwcW5c+JoadIkNam6WZjy2t7zRKZ0LKqVVCEn6Toh+X7mseGcdb82+47VNn2Vac6PEOQ7BWW7asBNXfa9SSzFbbY5j1pArX/vrWIdZfr2hkhYVBdG0Jxdvq; session-id-time-eu=1446620400l; session-id-eu=276-2397426-4871058; ubid-acbuk=278-8910564-3165513; ubid-main=182-6802864-3415319',
			"Referer: https://sellercentral.amazon.com/hz/fba/profitabilitycalculator/index?lang=en_US",
			"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2547.0 Safari/537.36",
			"Host: sellercentral.amazon.com",
			"Origin: https://sellercentral.amazon.com",
			"Content-Length: ". strlen($json),
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, 'https://sellercentral.amazon.com/hz/fba/profitabilitycalculator/getafnfee?profitcalcToken=j5NotR1Ubayl4WVpzzIKgu574eUj3D');

		$retValue = curl_exec($ch);
		$calulated_values = json_decode($retValue, true);
		$data['calulated_values'] = $calulated_values;
                $data['amazon_fulfillment_cost_subtotal'] = $calulated_values['data']['afnFees']['orderHandlingFee']+$calulated_values['data']['afnFees']['pickAndPackFee']+$calulated_values['data']['afnFees']['weightHandlingFee']+$calulated_values['data']['afnFees']['storageFee']+$input['amazon-inbound-delivery']+$input['amazon-prep-service'];
                $data['amazon_cost_subtotal'] = $data['amazon_fulfillment_cost_subtotal'] + $data['amazon_referral_fee'];
                $data['amazon_margin_impact'] = $input['revenue'] - $data['amazon_cost_subtotal'];
            }else if(isset($input['product_id'])){
                $header = array(
                    'Cookie: aws-target-static-id=1439990440953-98853; mkto_trk=id:810-GRW-452&token:_mch-amazon.com-1442603344654-21553; aws-business-metrics-last-visit=1443514013791; s_pers=%20s_fid%3D28B75CE613851765-1C2DBF4DE8638DBF%7C1499078821594%3B%20s_ev15%3D%255B%255B%2527Typed%252FBookmarked%2527%252C%25271435919790315%2527%255D%252C%255B%2527stackoverflow.com%2527%252C%25271435920421608%2527%255D%255D%7C1593773221608%3B%20s_dl%3D1%7C1435922516425%3B%20gpv_page%3DUS%253ASC%253A%2520SellerCentralLogin%7C1435922516436%3B%20s_vnum%3D1873271939320%2526vn%253D8%7C1873271939320%3B%20s_invisit%3Dtrue%7C1444641112955%3B%20s_nr%3D1444639312964-Repeat%7C1452415312964%3B; session-token="niETDdM6DFydSaiP5+ANCNs8jC3PKODsMDcAHDZWlPe+e/fsT7AgF+EWMGfFgMW0tQWy9DHncoqk29L+9QIpVcWkzSUtIbIy/wvqf50wrxZPs/tyrdJ9N8meo6WzokqX3AInrhyakSI5Lt4SvP/NP9yYpeGTWEaz8vO1b43pnkwCakptlRVutvnchRn645zwvzZwVWdRm+jS7Aslyu17SzLFIa6xtQLOX7pT+He8QM8="; x-main="wjchw29jpi7gIci42tvkIywBh?KSRO9ZbWh4g@L6avI5YrLSNMIwqjLIlpissDFt"; at-main=5|gxsfkO7KoZ7En11mXU58gWwYnu06rAi2C8dJRX0j9eYlBVY7yi+uRmnS9rYgmMqZHjUtQb2BQWHmIA4vM4/W9RKxv7G5DOplJvL5bwVrXnBrVjTLj0p8bhc6x5YWh7zxH0CDKOPDdyEsQ0/a9GcIAamx+TX3d4c2QbCulay/XTV3n3y/aXfx+IwtxiRxfcu9fDC5l3GK0ozPaCOtv47rMeFH6MqRxOhFX1L62ldeqnMU8IqMgWUpbWVVMew8KROWxYjHg1JH0RRkKPhbFqrWwSTed9jnPXhq; x-wl-uid=1Swpu2bRKUZTtz2+P+QMkLrzzrjDpchLOgg+j/516zniba89QZq8QDSRj4yDtkpFQDIYRUYjfj0iKPbIJ7UMUT/2LY/CLahH2tTRL4q8Rc+au0eIPVRXvjWCGT6XcpjGi3ldKPmW83xphS/W29P+crg==; session-id-time=2082787201l; session-id=189-1465449-1114840; aws-target-data=%7B%22support%22%3A%221%22%7D; s_vn=1466597092182%26vn%3D28; aws-target-visitor-id=1435061090384-624340.21_07; s_fid=054BA108F9CAE379-2196EB770EB4CC82; s_dslv=1445070978883; s_nr=1445070978887-Repeat; utmz=194891197.1445070987.51.32.utmccn=(referral)|utmcsr=console.aws.amazon.com|utmcct=/console/home|utmcmd=referral; aws-ubid-main=188-2446137-2649600; aws-x-main=pF7LmbhbN6dn3HMh5Pdqczvrf7D8nZHeVhlawTGEsTEjFJdiPlp5WUZV1qCXTUFD; aws-at-main="5|QkMJ17rOD6sbsZsASWDyq03r92P1V+0URMY+d3a4lJhxZ9gl98dr5PWI90bhGyNineZR+o+ViTzsSe/SSzZ8TH/S13BlSd7QBcNWWr5c832ntP9458JKBABcBJz5zE438x02DfGN1VTN/nwrlvirP3/kwB6cC1w8wc+F55ZEoAaYlwB2BbItwmHjruT4tAx6uoLkH+uA1JrSRcvMD1ReClwVdKDx6QaXnireTjH/Ru3rVq4u8WqighN74EzYoa3MSUCzx5y+4kXas7LXRj/xrz/1ycmb2V9AVwVlnbyAXheE4YSFdMQtUg=="; aws-userInfo=%7B%22arn%22%3A%22arn%3Aaws%3Aiam%3A%3A910212931994%3Aroot%22%2C%22alias%22%3A%22%22%2C%22username%22%3A%22Alazar%2520%2520Mersha%22%2C%22keybase%22%3A%22YlfA1J07GjAm%2F7o1nKr0iObraLOwOPnRiNd2f2K1ntY%5Cu003d%22%2C%22issuer%22%3A%22https%3A%2F%2Fwww.amazon.com%2Fap%2Fsignin%22%7D; regStatus=registered; utmv=194891197.pF7LmbhbN6dn3HMh5Pdqczvrf7D8nZHeVhlawTGEsTEjFJdiPlp5WUZV1qCXTUFD; _utma=194891197.1699046783.1435061165.1445070987.1445071022.52; ubid-main=184-9412905-2149054',
                    "Referer: https://sellercentral.amazon.com/hz/fba/profitabilitycalculator/index",
                    "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36",
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, "false");
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, 'https://sellercentral.amazon.com/hz/fba/profitabilitycalculator/productmatches?searchKey='.$input['product_id'].'&language=en_US&profitcalcToken=17j2Fnj2FMCxvNRYfLDECkJi0ZNK9mwj3D');

                $retValue = curl_exec($ch);
                $productDetail = json_decode($retValue, true);
                $data['product_detail'] = $productDetail;
                session(['product_detail' => $productDetail]);
                curl_close($ch);
                
            }
            
        }
        
        $data['userdata'] = $user;
        return view('calculator.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
}
