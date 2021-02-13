<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function bank(){
    	return $this->belongsTo('App\FinancialOrganization','financial_organization_id');
    }
}
