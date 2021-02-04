<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Library extends Model
{
    public $table='library';
    public function staff(){
        return $this->belongsTo('App\Staff');
    }
    
}
