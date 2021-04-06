<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonSettings extends Model
{
    protected $table = 'api_settings';
    protected $fillable = ['merchant_id','marketplace_id','access_key','secret_key','user_id','region'];
    public function user()
    {
        return $this->hasOne('User','user_id','id');
    }
}

