<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    public $table='topics';
    //protected $fillable = ['id','name','email'];
    public function chapter(){
        return $this->belongsTo('App\Chapter')->where('status',1);
    }
    public function document(){
        return $this->hasMany('App\Document')->where('status',1);
    }
}
