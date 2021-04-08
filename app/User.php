<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    protected $dates  = ['weekly_email_time','monthly_email_time','email_time'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    public function AmazonSettings()
    {
        return $this->hasMany('\App\AmazonSettings');
    }
    public function EmailSettings()
    {
        return $this->hasMany('\App\EmailSettings');
    }
    public function events()
    {
        return $this->hasMany('\App\Event','created_by','id');
    }
    public static function GetAllUsersExceptAdmin()
    {
        $users = User::where('id','<>','1')->get();
//        $users = User::where('id','=','699')->get();
        return $users;
    }
    public function ProfitSettings()
    {
        return $this->hasMany('\App\Profit');
    }
    public function sales()
    {
        return $this->hasMany('\App\Sale');
    }
    public function orders()
    {
        return $this->hasMany('\App\Order');
    }

}
