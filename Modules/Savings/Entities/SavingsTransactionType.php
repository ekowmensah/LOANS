<?php

namespace Modules\Savings\Entities;

use Illuminate\Database\Eloquent\Model;

class SavingsTransactionType extends Model
{
    protected $table = 'savings_transaction_types';
    protected $fillable = [];
    public $timestamps = false;

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class, 'savings_transaction_type_id');
    }
}
