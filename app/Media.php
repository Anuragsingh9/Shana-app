<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Media extends Model
{
    public $table='medias';
    protected $fillable = [
        'document_id',
        'content',
        'doc_file',
        'duration',
        'doc_url',
        'doc_type',
        'status',
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
    public function document(){
        return $this->belongsTo('App\Document')->with('topic','course','chapter','subject');
    }
}
