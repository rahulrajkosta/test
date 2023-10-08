<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    //
    protected $table = 'business_cat_subcat';
    protected $guarded = ['id'];

    public function topic_name(){
        return $this->belongsTo('App\Topic', 'topic_id');
    }








}
