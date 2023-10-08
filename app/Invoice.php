<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model{
    protected $table = 'invoice';
    protected $guarded = ['id'];
    protected $fillable =[];

}