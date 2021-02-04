<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanCoupon extends Model
{
    protected $fillable = [
        'plan_purchase', 'ref_code','status','activated_on','salt','hash','user_purchase_id'
    ];

    public function planPurchase(){
        return $this->belongsTo('App\PlanPurchase','plan_purchase');
    }

    public function userPurchase(){
        return $this->belongsTo('App\UserPurchase');
    }


}
