<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiPaSettings extends Model
{
    protected $table = 'api_pa_settings';
    protected $fillable = ['user_id', 'access_key','associate_tag','secret_key','location', 'has_api_keys'];

    public function user()
    {
        return $this->hasOne('User','user_id','id');
    }
}
