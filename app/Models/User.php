<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

#class User extends Authenticatable
class User extends Model
{
    //use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function genLoginToken($appId, $openId){
        $hashKey = config("consts.LOGIN_TOKEN_HASH_KEY");
        $uniqId  = uniqid();
        $zmToken = sha1(md5("{$appId}-{$openId}-{$hashKey}-{$uniqId}"));
        while( $this->where('token', $zmToken)->count() ){
            $uniqId  = uniqid();
            $zmToken = sha1(md5("{$appId}-{$openId}-{$hashKey}-{$uniqId}"));
        }
        return $zmToken;
    }
}
