<?php

namespace Modules\Loan\Entities;

use Illuminate\Database\Eloquent\Model;

class LoanProvisioning extends Model
{
    protected $fillable = [];
    public $table = "loan_provisioning";
    public $timestamps = false;
}
