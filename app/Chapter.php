<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;

    public $table='chapters';
    //protected $fillable = ['id','name','email'];
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    public function topic(){
        return $this->hasMany('App\Topic');
    }
}
