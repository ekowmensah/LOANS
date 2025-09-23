<?php

namespace Modules\Savings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class SavingsFileType extends Model
{
    protected $fillable = [];
    public $table = "savings_file_types";

}
