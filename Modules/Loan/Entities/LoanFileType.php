<?php

namespace Modules\Loan\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class LoanFileType extends Model
{
    protected $fillable = [];
    public $table = "loan_file_types";
    public $timestamps = false;
}
