<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id', 'message','event','doc_id'
    ];
    public function user()
    {
    	return $this->belongsTo('App\User','receiver_id','id');
    }
}
