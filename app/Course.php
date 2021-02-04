<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;
    public $table='courses';
    //protected $fillable = ['id','name','email'];


    public function scopeWithChaptersAndVideos($query)
    {
        return $query->with(['subject' => function($q)
        {
            $q->WithChapters();
        }]);
    }

    public function subject(){
        return $this->hasMany('App\Subject')->where('status',1);
    }

    public function documents(){
        return $this->hasMany('App\Document')->where('status',1);
    }

}
