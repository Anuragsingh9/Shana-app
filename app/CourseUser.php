<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{

    public $table='course_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id'
    ];
}
