<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Staff extends Model
{
    public $table='staffs';
    //protected $fillable = ['id','name','email'];
    protected $fillable = ['*'];
    public function course(){
        return $this->belongsTo('App\Course');
    }
    public function library(){
    	return $this->hasOne('App\Library');
    }

}
