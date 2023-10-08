<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model{

    protected $table = 'sub_categories';

    protected $guarded = ['id'];



    public function category(){
       return $this->belongsTo('App\Category', 'category_id');
    }


}