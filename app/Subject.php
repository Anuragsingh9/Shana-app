<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    public $table='subjects';
    //protected $fillable = ['id','name','email'];

    public function scopeWithChapters($query)
    {
        return $query->with('chapters');
    }

    public function course(){
        return $this->belongsTo('App\Course');
    }

    public function chapters(){
        return $this->hasMany('App\Chapter')->where('status',1);
    }
    
}
