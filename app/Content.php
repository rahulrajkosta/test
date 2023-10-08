<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model{

    protected $table = 'contents';

    protected $guarded = ['id'];

    protected $fillable = [
        'image',
        'category_id',
        'course_id',
        'subject_id',
        'hls',
        'hls_type',
        
       

    ];


}