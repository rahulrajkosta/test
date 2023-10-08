<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model{

    protected $table = 'subjects';

    protected $guarded = ['id'];

    protected $fillable = [
        'image',
        'category_id',
        'course_id',
        'subject_name',
        
       

    ];


}