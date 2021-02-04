<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    protected $fillable = [
        'purchase_id', 'paid_amount','user_id','end_date','item_id',
        'item_type', 'discount_amount','item_price','payment_data',
    ];

    public function coupons(){
        return $this->hasMany('App\PlanCoupon','plan_purchase');
    }

    public function plan(){
        return $this->belongsTo('App\Plan','item_id');
    }

    public function getUserIdAttribute($value){
        $user = User::find($value);
        if(count((array)$user)>0){
        return $user->name;
        }
        return '';
    }

    public function getItemIdAttribute($value){
        $user = Plan::find($value);
        if(count((array)$user)>0){
            return $user->name;
            }
            return '';
    }
}
