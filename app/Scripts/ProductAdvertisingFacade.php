<?php

namespace App\Scripts;

use Auth;
use App\Location;
use App\Scripts\ProductAdvertisingApi;

class ProductAdvertisingFacade {
    protected $paApi;

    public function __construct()
    {
        $this->paApi = new ProductAdvertisingApi();
    }

    public function setKeys($keys)
    {

        $this->paApi->setKeys($keys);
    }

    public function findRankForProductAndKeyword($product, $keyword, $searchIndex, $location)
    {
        echo("location = $location\n");
        $this->paApi->setLocationData( Location::find($location) );
        $page = 1;
        if ($searchIndex != 'All') {
            $maxPages = 10;
        } else {
            $maxPages = 5;
        }
        $result = false;

        while (!$result) {
            $result = $this->paApi->itemSearch($keyword, $page, $searchIndex)->getXML();
//            echo('test');
            $result = $this->getRankForAsin($result, $product, $page);
            $page++;
            sleep(1);

            if ($page > $maxPages) {
                return array(
                    'bsr' => 0,
                    'rank' => 0
                );
            }
        }

        return $result;
    }

    protected function getRankForAsin($result, $asin, $page)
    {
//        echo("<prE>");
//        print_r($result->Items->Item);
        $count = 1;
        foreach($result->Items->Item as $index=>$item) {
            if ($item->ASIN == $asin) {
//                echo("<prE>");
//                print_r($item);
                return array(
                    'bsr' => (string)$item->SalesRank,
                    'rank' => (($page-1)*10)+($count),
                    'image' => (string)$item->SmallImage->URL,
                    'title' => (string)$item->ItemAttributes->Title
                );
            }
            $count++;
        }

        return false;
    }
}