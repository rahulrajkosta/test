<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model{

    protected $table = 'courses';

    protected $guarded = ['id'];

    protected $fillable = [
        'image',
        'course_name',
        'course_description',
        'fees',
        'duration',
        'student_capacity',
       

    ];


}