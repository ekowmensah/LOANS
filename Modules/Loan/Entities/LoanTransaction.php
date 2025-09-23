<?php

namespace Modules\Loan\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\PaymentDetail;
use Modules\User\Entities\User;

class LoanTransaction extends Model
{
    protected $fillable = [];
    public $table = "loan_transactions";
    protected $casts = [
        'reversed' => 'boolean',
        'reversible' => 'boolean',
    ];

    public function payment_detail()
    {
        return $this->hasOne(PaymentDetail::class, 'id', 'payment_detail_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
