<?php

namespace App;

use App\ProductKeywordData;
use Illuminate\Database\Eloquent\Model;

class ProductKeywordList extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_keyword_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product', 'keyword', 'productKeyword', 'location'];

    public function product_keyword_pairs()
    {
        return $this->hasMany('App\ProductKeywordPairs');
    }

    public function product_keyword_data()
    {
        return $this->hasMany('App\ProductKeywordData');
    }

    public static function findUnranAsinsAndKeywords() {
//        return ProductKeywordList::whereNotIn('product_keyword_list_id', ProductKeywordData::all() )->get();
        return ProductKeywordList::whereNotIn('id', ProductKeywordData::getArrayProductKeywordListIds() )->get();

    }

    public function saveApiResults($results)
    {
        $this->product_keyword_data()->save( ProductKeywordData::create( $results )   );
    }

    public static function getAsinsWithoutSearchIndex()
    {
        $pkList = ProductKeywordList::whereNull('search_index')->orWhere('search_index','=','')->groupBy('product', 'location')->take(10)->get();
        $asins = array();
        foreach($pkList as $pk) {
            $asins[] = $pk->product;
        }

        return ['asins' => $asins, 'raw' => $pkList];
    }

    public static function findSearchIndexForAsin($asin, $location)
    {
        try {
            $tmp = ProductKeywordList::where('product', '=', $asin)->where('location', $location)->where('search_index','!=','')->first();

            if (isset($tmp->search_index)) {
                return $tmp->search_index;
            } else {
                return false;
            }

        } catch (ErrorException $e) {
            return false;
        }
    }

    public static function saveSearchIndexResults($results)
    {
        echo("<pre>");
        print_r($results);

        foreach ($results as $result) {
            ProductKeywordList::where('product', '=', $result['asin'])->whereNull('search_index')->update( array('search_index' => $result['searchIndex']) );
        }
    }
}
