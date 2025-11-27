<?php

namespace Modules\FieldAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;
use Modules\Loan\Entities\Loan;
use Modules\User\Entities\User;

class FieldCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_agent_id',
        'collection_type',
        'reference_id',
        'client_id',
        'amount',
        'collection_date',
        'collection_time',
        'latitude',
        'longitude',
        'location_address',
        'receipt_number',
        'payment_method',
        'status',
        'verified_by_user_id',
        'verified_at',
        'posted_by_user_id',
        'posted_at',
        'notes',
        'photo_proof',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'collection_date' => 'date',
        'collection_time' => 'datetime',
        'verified_at' => 'datetime',
        'posted_at' => 'datetime',
    ];

    protected $appends = ['status_badge', 'collection_type_label'];

    /**
     * Get the field agent who made this collection
     */
    public function fieldAgent()
    {
        return $this->belongsTo(FieldAgent::class);
    }

    /**
     * Get the client who made the payment
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who verified this collection
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Get the user who posted this collection
     */
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }

    /**
     * Get the related savings account (if collection type is savings_deposit)
     */
    public function savings()
    {
        return $this->belongsTo(Savings::class, 'reference_id');
    }

    /**
     * Get the related loan (if collection type is loan_repayment)
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'reference_id');
    }

    /**
     * Get the reference (polymorphic relationship)
     */
    public function reference()
    {
        if ($this->collection_type === 'savings_deposit') {
            return $this->savings();
        } elseif ($this->collection_type === 'loan_repayment') {
            return $this->loan();
        }
        return null;
    }

    /**
     * Scope to get pending collections
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get verified collections
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope to get posted collections
     */
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    /**
     * Scope to get rejected collections
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to filter by collection type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('collection_type', $type);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('collection_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by field agent
     */
    public function scopeByAgent($query, $agentId)
    {
        return $query->where('field_agent_id', $agentId);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'verified' => '<span class="badge badge-info">Verified</span>',
            'posted' => '<span class="badge badge-success">Posted</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Get collection type label
     */
    public function getCollectionTypeLabelAttribute()
    {
        $labels = [
            'savings_deposit' => 'Savings Deposit',
            'loan_repayment' => 'Loan Repayment',
            'share_purchase' => 'Share Purchase',
        ];

        return $labels[$this->collection_type] ?? 'Unknown';
    }

    /**
     * Check if collection can be verified
     */
    public function canBeVerified()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if collection can be posted
     */
    public function canBePosted()
    {
        return $this->status === 'verified';
    }

    /**
     * Verify this collection
     */
    public function verify($userId)
    {
        $this->update([
            'status' => 'verified',
            'verified_by_user_id' => $userId,
            'verified_at' => now(),
        ]);
    }

    /**
     * Reject this collection
     */
    public function reject($userId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by_user_id' => $userId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Post this collection to accounting
     */
    public function post($userId)
    {
        $this->update([
            'status' => 'posted',
            'posted_by_user_id' => $userId,
            'posted_at' => now(),
        ]);
    }

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber()
    {
        $prefix = 'FC';
        $date = now()->format('Ymd');
        $lastReceipt = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReceipt ? (int)substr($lastReceipt->receipt_number, -4) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to auto-generate receipt number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->receipt_number)) {
                $collection->receipt_number = self::generateReceiptNumber();
            }
        });
    }
}
