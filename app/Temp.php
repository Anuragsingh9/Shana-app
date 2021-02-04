<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Temp extends Model
{
    public $table='temp';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'otp', 'password', 'email','valid_til','verified'
    ];

}
