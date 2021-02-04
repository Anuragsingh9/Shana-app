<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
  

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','mobile','city','age','ref_code','institute','self_ref_code','photo','device_token'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function scopeWithCoursesSubjectAndChapters($query)
    {
        return $query->with(['courses' => function($q)
        {
           // $q->WithChaptersAndVideos();
        }]);
    }
    public function courses()
    {
        return $this->belongsToMany('App\Course')->where("status", 1);
    }

    public function userPaid()
    {
        return $this->hasMany('App\UserPurchase');
    }

    public function library(){
        return $this->hasOne('App\Library','staff_id');
    }
    public function isAdmin()
    {
        if(count(Auth::user()->roles)>0 && Auth::user()->roles[0]->name=='Admin'){
            return true;
        }else{
            return false;
        }
    }
}
