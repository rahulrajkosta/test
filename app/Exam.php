<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //
    protected $table = 'exams';
    protected $guarded = ['id'];










     public function category_name(){
        return $this->belongsTo('App\Category', 'category_id');
    }

     public function course_name(){
        return $this->belongsTo('App\Course', 'course_id');
    }
     public function subject_name(){
        return $this->belongsTo('App\Subject', 'subject_id');
    }
     public function topic_name(){
        return $this->belongsTo('App\Topic', 'topic_id');
    }








}
