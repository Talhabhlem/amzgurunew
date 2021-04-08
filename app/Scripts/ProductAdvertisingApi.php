<?php

namespace App\Scripts;

class ProductAdvertisingApi {
    protected $accessKeyId;
    protected $associateTag;
    protected $secretAccessKey;

    protected $operation;
    protected $version;

    protected $xml;
    protected $searchIndexNodeList;
    protected $searchIndexNodeListByCountry;

    protected $locationData;

    public function setLocationData($locationData)
    {
        $this->locationData = $locationData;
    }

    function setKeys($keys) {
        $this->access_key    = $keys['access_key'];
        $this->associate_tag = $keys['associate_tag'];
        $this->secret_key    = $keys['secret_key'];

        $this->searchIndexNodeList = [
            2858778011 => 'UnboxVideo',
            2619526011 => 'Appliances',
            2350150011 => 'MobileApps',
            2617942011 => 'ArtsAndCrafts',
            15690151 => 'Automotive',
            165797011 => 'Baby',
            11055981 => 'Beauty',
            1000 => 'Books',
            301668 => 'Music',
            2335753011 => 'Wireless',
            7141124011 => 'Fashion',
            7147444011 => 'FashionBaby',
            7147443011 => 'FashionBoys',
            7147442011 => 'FashionGirls',
            7147441011 => 'FashionMen',
            7147440011 => 'FashionWomen',
            4991426011 => 'Collectibles',
            541966 => 'PCHardware',
            624868011 => 'MP3Downloads',
            493964 => 'Electronics',
            2864120011 => 'GiftCards',
            16310211 => 'Grocery',
            3760931 => 'HealthPersonalCare',
            1063498 => 'HomeGarden',
            16310161 => 'Industrial',
            133141011 => 'KindleStore',
            9479199011 => 'Luggage',
            599872 => 'Magazines',
            2625374011 => 'Movies',
            11965861 => 'MusicalInstruments',
            1084128 => 'OfficeProducts',
            3238155011 => 'LawnAndGarden',
            2619534011 => 'PetSupplies',
            409488 => 'Software',
            3375301 => 'SportingGoods',
            468240 => 'Tools',
            165795011 => 'Toys',
            11846801 => 'VideoGames',
            2983386011 => 'Wine'
        ];

        $this->searchIndexNodeListByCountry['US'] = [
            2858778011 => 'UnboxVideo',
            2619526011 => 'Appliances',
            2350150011 => 'MobileApps',
            2617942011 => 'ArtsAndCrafts',
            15690151 => 'Automotive',
            165797011 => 'Baby',
            11055981 => 'Beauty',
            1000 => 'Books',
            301668 => 'Music',
            2335753011 => 'Wireless',
            7141124011 => 'Fashion',
            7147444011 => 'FashionBaby',
            7147443011 => 'FashionBoys',
            7147442011 => 'FashionGirls',
            7147441011 => 'FashionMen',
            7147440011 => 'FashionWomen',
            4991426011 => 'Collectibles',
            541966 => 'PCHardware',
            624868011 => 'MP3Downloads',
            493964 => 'Electronics',
            2864120011 => 'GiftCards',
            16310211 => 'Grocery',
            3760931 => 'HealthPersonalCare',
            1063498 => 'HomeGarden',
            16310161 => 'Industrial',
            133141011 => 'KindleStore',
            9479199011 => 'Luggage',
            599872 => 'Magazines',
            2625374011 => 'Movies',
            11965861 => 'MusicalInstruments',
            1084128 => 'OfficeProducts',
            3238155011 => 'LawnAndGarden',
            2619534011 => 'PetSupplies',
            409488 => 'Software',
            3375301 => 'SportingGoods',
            468240 => 'Tools',
            165795011 => 'Toys',
            11846801 => 'VideoGames',
            2983386011 => 'Wine'
        ];

        $this->searchIndexNodeListByCountry['CA'] = [
            6386372011 => 'MobileApps',
            6948389011 => 'Automotive',
            3561347011 => 'Baby',
            6205125011 => 'Beauty',
            927726 => 'Books',
            8604904011 => 'Apparel',
            677211011 => 'Electronics',
            9230167011 => 'GiftCards',
            6967216011 => 'Grocery',
            6205178011 => 'HealthPersonalCare',
            2206276011 => 'Kitchen',
            9674384011 => 'Jewelry',
            2972706011 => 'KindleStore',
            6205506011 => 'Luggage',
            14113311 => 'DVD',
            962454 => 'Music',
            6916845011 => 'MusicalInstruments',
            6205512011 => 'OfficeProducts',
            6299024011 => 'LawnAndGarden',
            6291628011 => 'PetSupplies',
            8604916011 => 'Shoes',
            3234171 => 'Software',
            2242990011 => 'SportingGoods',
            3006903011 => 'Tools',
            6205517011 => 'Toys',
            110218011 => 'VideoGames',
            2235621011 => 'Watches'
        ];

        $this->searchIndexNodeListByCountry['MX'] = [
            9482651011 => 'Baby',
            9482661011 => 'SportingGoods',
            9482559011 => 'Electronics',
            9482671011 => 'HomeImprovement',
            9482594011 => 'Kitchen',
            9298577011 => 'Books',
            9482621011 => 'Music',
            9482631011 => 'DVD',
            9482681011 => 'Watches',
            9482611011 => 'HealthPersonalCare',
            9482691011 => 'Software',
            6446440011 => 'KindleStore',
            9482641011 => 'VideoGames'
        ];

        $this->searchIndexNodeListByCountry['DE'] = [
            3010076031 => 'UnboxVideo',
            1661650031 => 'MobileApps',
            78193031 => 'Automotive',
            357577011 => 'Baby',
            235002011 => 'Tools',
//            64257031 => 'Beauty',
            78689031 => 'Apparel',
            213084031 => 'Lighting',
            541686 => 'Books',
            192417031 => 'OfficeProducts',
//            569604 => 'PCHardware',
            547664 => 'DVD',
            64257031 => 'HealthPersonalCare',
            931573031 => 'Appliances',
            569604 => 'Electronics',
            54071011 => 'ForeignBooks',
            541708 => 'VideoGames',
            10925241 => 'HomeGarden',
            1571257031 => 'GiftCards',
            427727031 => 'PetSupplies',
            571860 => 'Photo',
            530485031 => 'KindleStore',
//            542676 => 'Classical',
            2454119031 => 'Luggage',
            3169011 => 'Kitchen',
            344162031 => 'Grocery',
            542676 => 'Music',
            180529031 => 'MP3Downloads',
            340850031 => 'MusicalInstruments',
            327473011 => 'Jewelry',
            362995011 => 'Shoes',
            542064 => 'Software',
            12950661 => 'Toys',
            16435121 => 'SportingGoods',
            193708031 => 'Watches',
            1161660 => 'Magazines',

        ];

        $this->searchIndexNodeListByCountry['ES'] = [
            1661651031 => 'MobileApps',
            1703496031 => 'Baby',
            6198055031 => 'Beauty',
            2454134031 => 'Tools',
            3564280031 => 'GiftCards',
            1951052031 => 'Automotive',
            2665403031 => 'SportingGoods',
            667050031 => 'Electronics',
            2454130031 => 'Luggage',
            599392031 => 'Kitchen',
            3564290031 => 'Lighting',
//            667050031 => 'PCHardware',
            3628867031 => 'MusicalInstruments',
            1571260031 => 'LawnAndGarden',
            2454127031 => 'Jewelry',
            599386031 => 'Toys',
            599365031 => 'Books',
            599368031 => 'ForeignBooks',
            1748201031 => 'MP3Downloads',
            599374031 => 'Music',
            3628729031 => 'OfficeProducts',
            599380031 => 'DVD',
            599389031 => 'Watches',
            2846221031 => 'Apparel',
            3677431031 => 'HealthPersonalCare',
            599377031 => 'Software',
            818938031 => 'KindleStore',
            599383031 => 'VideoGames',
            1571263031 => 'Shoes'
        ];

        $this->searchIndexNodeListByCountry['FR'] = [
            1571269031 => 'PetSupplies',
            1661655031 => 'MobileApps',
            2454146031 => 'Luggage',
            197859031 => 'Beauty',
            193711031 => 'Jewelry',
            672109031 => 'KindleStore',
            2524128031 => 'GiftCards',
            590749031 => 'HomeImprovement',
            206618031 => 'Baby',
            248812031 => 'Shoes',
            57686031 => 'Kitchen',
            578608 => 'DVD',
            192420031 => 'OfficeProducts',
            908827031 => 'Appliances',
            14011561 => 'Electronics',
            197862031 => 'HealthPersonalCare',
            340859031 => 'PCHardware',
            340862031 => 'MusicalInstruments',
            3557028031 => 'LawnAndGarden',
            548014 => 'Toys',
//548014 => 'VideoGames',
            69633011 => 'ForeignBooks',
            468256 => 'Books',
            548012 => 'Software',
            213081031 => 'Lighting',
            60937031 => 'Watches',
            537366 => 'Music',
//537366 => 'Classical',
            325615031 => 'SportingGoods',
            206442031 => 'MP3Downloads',
            340856031 => 'Apparel'
        ];

        $this->searchIndexNodeListByCountry['IN'] = [
            1571275031 => 'Baby',
            1355017031 => 'Beauty',
            976390031 => 'Books',
            4772061031 => 'Automotive',
            1571272031 => 'Apparel',
            976393031 => 'PCHardware',
            976420031 => 'Electronics',
            3704983031 => 'GiftCards',
            2454179031 => 'Grocery',
            1350385031 => 'HealthPersonalCare',
            2454176031 => 'HomeGarden',
            1951049031 => 'Jewelry',
            1571278031 => 'KindleStore',
            2454170031 => 'Luggage',
            976417031 => 'DVD',
            976446031 => 'Music',
            3677698031 => 'MusicalInstruments',
            2454173031 => 'OfficeProducts',
            4740420031 => 'PetSupplies',
            1571284031 => 'Shoes',
            976452031 => 'Software',
            1984444031 => 'SportingGoods',
            1350381031 => 'Toys',
            976461031 => 'VideoGames',
            1350388031 => 'Watches',
        ];

        $this->searchIndexNodeListByCountry['IT'] = [
            2844434031 => 'Apparel',
            6198093031 => 'Grocery',
            1661661031 => 'MobileApps',
            1571281031 => 'Automotive',
            6198083031 => 'Beauty',
            3557018031 => 'GiftCards',
            412601031 => 'Music',
            3606311031 => 'OfficeProducts',
            524016031 => 'Kitchen',
            1571290031 => 'HealthPersonalCare',
            412610031 => 'Electronics',
            2454161031 => 'Tools',
            412607031 => 'DVD',
            635017031 => 'Garden',
            523998031 => 'Toys',
            2454164031 => 'Jewelry',
            1571293031 => 'Lighting',
            425917031 => 'PCHardware',
            1331141031 => 'KindleStore',
            411664031 => 'Books',
            433843031 => 'ForeignBooks',
            1748204031 => 'MP3Downloads',
            524010031 => 'Watches',
            1571287031 => 'Baby',
            524007031 => 'Shoes',
            412613031 => 'Software',
            524013031 => 'SportingGoods',
            3628630031 => 'MusicalInstruments',
            2454149031 => 'Luggage',
            412604031 => 'VideoGames',
        ];

        $this->searchIndexNodeListByCountry['UK'] = [
            3010086031 => 'UnboxVideo',
            1661658031 => 'MobileApps',
            60032031 => 'Baby',
//            66280031 => 'Beauty',
            1025612 => 'Books',
            520920 => 'Music',
            248878031 => 'Automotive',
            505510 => 'Classical',
            83451031 => 'Apparel',
            340832031 => 'PCHardware',
//            11052591 => 'Tools',
            573406 => 'DVD',
            77925031 => 'MP3Downloads',
            560800 => 'Electronics',
//            11052591 => 'HomeGarden',
            1571305031 => 'GiftCards',
            344155031 => 'Grocery',
            66280031 => 'HealthPersonalCare',
            193717031 => 'Jewelry',
            341677031 => 'KindleStore',
            11052591 => 'Kitchen',
            908799031 => 'Appliances',
            213078031 => 'Lighting',
            2454167031 => 'Luggage',
            340837031 => 'MusicalInstruments',
            1025616 => 'VideoGames',
            340841031 => 'PetSupplies',
            362350011 => 'Shoes',
            1025614 => 'Software',
            319530011 => 'SportingGoods',
//            560800 => 'OfficeProducts',
            712832 => 'Toys',
            125556011 => 'VHS',
            328229011 => 'Watches',

        ];

        $this->searchIndexNodeListByCountry['JP'] = [
            2351650051 => 'VideoDownload',
            2381131051 => 'MobileApps',
            2016930051 => 'HomeImprovement',
            561972 => 'Video',
            2250739051 => 'KindleStore',
            637630 => 'Software',
            637872 => 'VideoGames',
//            13331821 => 'Toys',
            2017305051 => 'Automotive',
            2351653051 => 'GiftCards',
//            562032 => 'Classical',
            2320456051 => 'CreditCards',
            52391051 => 'Beauty',
            2016926051 => 'Shoes',
            85896051 => 'Jewelry',
            14315361 => 'SportingGoods',
            2129039051 => 'MP3Downloads',
            2127210051 => 'PCHardware',
            161669011 => 'HealthPersonalCare',
            13331821 => 'Baby',
            2127213051 => 'PetSupplies',
//            13331821 => 'Hobbies',
            3839151 => 'Kitchen',
            562032 => 'Music',
            2277725051 => 'Appliances',
            3210991 => 'Electronics',
            86732051 => 'OfficeProducts',
            361299011 => 'Apparel',
            465610 => 'Books',
            2123630051 => 'MusicalInstruments',
            388316011 => 'ForeignBooks',
            3445394051 => 'Industrial',
            331952011 => 'Watches',
            57240051 => 'Grocery',
            562002 => 'DVD',
        ];

        $this->searchIndexNodeListByCountry['CN'] = [
            80208071 => 'Appliances',
            116088071 => 'KindleStore',
            852804051 => 'HealthPersonalCare',
            2127219051 => 'MusicalInstruments',
            2127222051 => 'OfficeProducts',
            2016126051 => 'Kitchen',
            658391051 => 'Books',
            118864071 => 'PetSupplies',
            1952921051 => 'HomeImprovement',
            2016127051 => 'Home',
            146629071 => 'MobileApps',
            755653051 => 'Photo',
            2016157051 => 'Apparel',
            42693071 => 'Baby',
            1947900051 => 'Automotive',
            897416051 => 'VideoGames',
            647071051 => 'Toys',
            816483051 => 'Jewelry',
            2016117051 => 'Electronics',
            42690071 => 'PCHardware',
            311868071 => 'GiftCards',
            746777051 => 'Beauty',
            863873051 => 'Software',
            836313051 => 'SportingGoods',
            1953165051 => 'Watches',
            2029190051 => 'Shoes',
            754387051 => 'Music',
            2016137051 => 'Video',
            2127216051 => 'Grocery',
        ];

        $this->searchIndexNodeListByCountry['BR'] = [
            6563510011 => 'MobileApps',
            7841278011 => 'Books',
            5308308011 => 'KindleStore'
        ];
//        $countries = array();
        $countries = [
            'US' => [
                'domain' => 'www.amazon.com',
                'marketplace_id' => 'ATVPDKIKX0DER',
                'name' => 'United States'
            ],
            'CA' => [
                'domain' => 'www.amazon.ca',
                'marketplace_id' => 'A2EUQ1WTGCTBG2',
                'name' => 'Canada'
            ],
            'MX' => [
                'domain' => 'www.amazon.com.mx',
                'marketplace_id' => 'A1AM78C64UM0Y8',
                'name' => 'Mexico'
            ],
            'DE' => [
                'domain' => 'www.amazon.de',
                'marketplace_id' => 'A1PA6795UKMFR9',
                'name' => 'Germany'
            ],
            'ES' => [
                'domain' => 'www.amazon.es',
                'marketplace_id' => 'A1RKKUPIHCS9HS',
                'name' => 'Spain'
            ],
            'FR' => [
                'domain' => 'www.amazon.fr',
                'marketplace_id' => 'A13V1IB3VIYZZH',
                'name' => 'France'
            ],
            'IN' => [
                'domain' => 'www.amazon.in',
                'marketplace_id' => 'A21TJRUUN4KGV',
                'name' => 'India'
            ],
            'IT' => [
                'domain' => 'www.amazon.it',
                'marketplace_id' => 'APJ6JRA9NG5V4',
                'name' => 'Italy'
            ],
            'UK' => [
                'domain' => 'www.amazon.co.uk',
                'marketplace_id' => 'A1F83G8C2ARO7P',
                'name' => 'United Kingdom'
            ],
            'JP' => [
                'domain' => 'www.amazon.co.jp',
                'marketplace_id' => 'A1VC38T7YXB528',
                'name' => 'Japan'
            ],
            'CN' => [
                'domain' => 'www.amazon.cn',
                'marketplace_id' => 'AAHKV2X7AFYLWven',
                'name' => 'China'
            ]
        ];
        return $this;
    }

    public function defaultLocation()
    {
        if (!isset($this->locationData)) {
            $this->locationData = (object)[
                'id' => "1",
                'location' => "United States",
                'location_abbr' => "US",
                'endpnt' => "webservices.amazon.com",
                'marketplace_id' => "ATVPDKIKX0DER",
                'domain' => "www.amazon.com"
            ];
        }
    }

    public static function isValidKeys($keys)
    {
        $paApi = new ProductAdvertisingApi();
        $paApi->defaultLocation();
        $xml = $paApi->setKeys( $keys )->itemSearch('test')->getXML();

        if (isset($xml->Error)) {
            return false;
        } else {
            return true;
        }
    }

    function getXML() {
        return $this->xml;
    }

    function itemSearch($keyword, $page=1, $searchIndex = 'All') {

        //Set the values for some of the parameters
        $Operation = "ItemSearch";
        $Version = "2013-08-01";

        $params = array(
            'Keywords'      => $keyword,
            'Operation'		=> $Operation,
            'Service'		=> 'AWSECommerceService',
            'Timestamp'		=> gmdate("Y-m-d\TH:i:s\Z"),
            'ResponseGroup' => 'OfferFull,Large,BrowseNodes',
            'SearchIndex'   => $searchIndex,
            'ItemPage'      => $page,
            'Version'		=> $Version
        );

        $this->xml = $this->performQuery($params);
        return $this;
    }

    function performQuery($params) {

        $params['AWSAccessKeyId'] = $this->access_key;
        $params['AssociateTag']   = $this->associate_tag;

        // Builds an array of strings to be sorted and combined for the signature string
        $url_parts = array();
        foreach($params as $key=>$value) {
            $url_parts[] = $key . '=' . str_replace('%7E', '~', rawurlencode($params[$key]));
        }
        sort($url_parts);

        // Builds the string for the signature
        $signatureParamString = "";
        foreach($url_parts as $value) {
            $signatureParamString .= $value . "&";
        }

        $signatureParamString = rtrim($signatureParamString,'&');
        
        $string_to_sign = "GET\n" . $this->locationData->endpnt . "\n/onca/xml\n" . $signatureParamString;

        // Sign the request
        $signature = hash_hmac("sha256", $string_to_sign, $this->secret_key, TRUE);

        // Base64 encode the signature and make it URL safe
        $signature = urlencode(base64_encode($signature));

        // Builds the URL
        $url = 'http://' . $this->locationData->endpnt . '/onca/xml?' . $signatureParamString . '&Signature=' . $signature;

//        echo(" url = $url\n");

        // Catch the url response
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);

        if(!curl_errno($ch)){
            $response = $data;
        }else{
            echo 'Curl error: ' . curl_error($ch);
            exit;
        }
        curl_close($ch);

        $parsed_xml = simplexml_load_string($response);

        return $parsed_xml;
    }

    function ItemLookup($asin, $amazon = false) {
        //Set the values for some of the parameters
        $Operation = "ItemLookup";
        $Version = "2013-08-01";
        $ResponseGroup = "Offers,OfferFull,SalesRank,ItemAttributes,BrowseNodes";
        //User interface provides values
        //for $SearchIndex and $Keywords

        if (is_array($asin)) {
            $tmp = "";

            // Check to make sure we aren't passing more than 10 asins
            if (count($asin) > 10) {
                echo("Watch out! ItemLookup can only get 10 asins at a time and there were more than that passed to it.<br>");
            }

            foreach($asin as $a) {
                $tmp .= $a . ",";
            }
            $asin = rtrim($tmp,',');
        }

        $params = array(
            'IdType'		=> 'ASIN',
            'ItemId'		=> $asin,
            'Operation'		=> $Operation,
            'ResponseGroup'	=> $ResponseGroup,
            'Service'		=> 'AWSECommerceService',
            'Timestamp'		=> gmdate("Y-m-d\TH:i:s\Z"),
            'Version'		=> $Version
        );
        if ($amazon == true) {$params['MerchantId'] = 'Amazon';}

        $this->xml = $this->performQuery($params);
//        echo('test');
        return $this;
    }

    function getSearchIndexes() {
        $output = array();

//        echo('trying to loop through items');
//        echo("<pre>");
//        print_r($this->xml);

        foreach($this->xml->Items->Item as $item) {
//            echo('made it past');
            $tmp = array();

            $nodes = $this->parseBrowseNodes($item->BrowseNodes);

            $tmp['asin'] = (string)$item->ASIN;
//            echo('looping through nodes');
            foreach($nodes as $node) {
                foreach($this->searchIndexNodeListByCountry[ $this->locationData->location_abbr ] as $searchNode => $searchIndex) {
//                foreach($this->searchIndexNodeList as $searchNode => $searchIndex) {
                    if ($node == $searchNode) {
                        $tmp['searchIndex'] = $searchIndex;
                    }
                }
            }

            if (!isset($tmp['searchIndex'])) { $tmp['searchIndex'] = 'All'; }

            $output[] = $tmp;
        }

        return $output;
    }

    function cleanItemLookup($xml, $responseGroup = false) {
        $return = array();

        // echo("<prE>");
        // print_r((array)$xml->Items->Item->children());
        foreach($xml->Items->Item as $key=>$value) {
            $tmp = array();
            $tmp['asin'] = (string)$value->ASIN;
            $tmp['Total_New_Offers'] = (string)$value->OfferSummary->TotalNew;
            $tmp['Buy_Box_Seller'] = (string)$value->Offers->Offer->Merchant->Name;
            $tmp['Buy_Box_Price'] = (string)round($value->Offers->Offer->OfferListing->Price->Amount / 100,2);

            $upcElement = $value->ItemAttributes->UPCList->UPCListElement;
            // echo("<pre>");
            // print_r($upcElement);

            if ( count($upcElement) > 1 ) {
                // echo("is array!<BR>");
                $tmp['upc'] = (string)$upcElement[0];
                $tmp['upcs'] = (array)$upcElement;
            } else {
                $tmp['upc'] = (string)$upcElement;

            }

            if ($responseGroup == 'SalesRank') {
                $tmp['SalesRank'] = (string)$value->SalesRank;
            }

            $return[] = $tmp;
        }

        return $return;
    }

    protected function parseBrowseNodes($nodes)
    {
        $output = array();


        // Recursively find browse node ids
        if (isset($nodes->BrowseNode)) {
            foreach ($nodes->BrowseNode as $node) {
                $output[] = (string)$node->BrowseNodeId;

                $working = $node;
                while ($working = $working->Ancestors->BrowseNode) {
                    $output[] = (string)$working->BrowseNodeId;
                }

            }
        }

        // Remove any duplicates
        $output = array_unique($output);

        return $output;

    }
}
