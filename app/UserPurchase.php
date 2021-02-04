<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    protected $fillable = [
        'purchase_id', 'paid_amount', 'user_id','end_date','item_id','item_type','discount_amount','item_price','payment_data'
    ];

    public function getUserIdAttribute($value){
        $user = User::find($value);
        return $user->name;
    }

    protected function getSubjectCourse($id,$type){
    if($type==0){
      $course=Course::find($id);
      return $course->course;
    }else{
        $subject=Subject::find($id);
        return $subject->subject;
    }
    }

}
