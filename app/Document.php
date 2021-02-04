<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table='documents';
    protected $fillable = [
        'topic_id',
        'course_id',
        'subject_id',
        'chapter_id',
        'content',
        'doc_file',
        'duration',
        'doc_url',
        'user_type',
        'title',
        'author_name',
        'description',
        'doc_type',
        'status',
        'preview_image'
    ];
    public function topic(){
        return $this->belongsTo('App\Topic');
    }
    public function course(){
        return $this->belongsTo('App\Course');
    }
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    public function chapter(){
        return $this->belongsTo('App\Chapter');
    }

    public function media(){
        return $this->hasMany('App\Media')->where('status',1);
    }
    public function mediaList(){
        return $this->hasMany('App\Media');
    }

}
