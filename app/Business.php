<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    //
    protected $table = 'businesses';
    protected $guarded = ['id'];

    public function topic_name(){
        return $this->belongsTo('App\Topic', 'topic_id');
    }








}
