<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model{

    protected $table = 'cities';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [
        'name',
        'state_id'
    ];


    public function cityState(){
        return $this->belongsTo('App\State', 'state_id');
    }

     public function cityCountry(){
        return $this->belongsTo('App\Country', 'country_id');
    }
}