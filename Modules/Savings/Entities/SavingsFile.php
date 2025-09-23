<?php

namespace Modules\Savings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class SavingsFile extends Model
{
    protected $fillable = [];
    public $table = "savings_files";

    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }
}
