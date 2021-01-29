<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;

    public function createdBy(){
    	return $this->belongsTo('App\User','user_id');
    }
}
