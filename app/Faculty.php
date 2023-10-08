<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model{

    protected $table = 'faculties';

    protected $guarded = ['id'];

    protected $fillable = [

        'name',
        'email',
        'phone',
        'password',
        'image',
        'total_exp',
        'education',
        'speciality',


    ];


}