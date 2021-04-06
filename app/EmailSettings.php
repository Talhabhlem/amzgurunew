<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    //
    protected $table = 'email_setting';

    protected $fillable = ['package','email'];
    public function user()
    {
        return $this->hasOne('User','user_id','id');
    }

}
