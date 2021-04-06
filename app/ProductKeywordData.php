<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductKeywordData extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_keyword_datas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bsr', 'rank', 'title', 'image'];

    protected $touches = ['product_keyword_list'];

    public function product_keyword_list()
    {
        return $this->belongsTo('App\ProductKeywordList');
    }

    public static function getArrayProductKeywordListIds()
    {
        $output = array();
        $results = ProductKeywordData::all()->toArray();

        foreach ($results as $result) {
            $output[] = $result['product_keyword_list_id'];
        }

        return $output;
    }

}
