<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductKeywordPairs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_keyword_pairs';

    public function users()
    {
        return $this->belongsTo('App\User');
    }

    public function product_keyword_list()
    {
        return $this->belongsTo('App\ProductKeywordList');
    }
}
