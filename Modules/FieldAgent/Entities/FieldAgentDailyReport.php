<?php

namespace Modules\FieldAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\User;

class FieldAgentDailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_agent_id',
        'report_date',
        'total_collections',
        'total_amount_collected',
        'total_clients_visited',
        'total_clients_paid',
        'opening_cash_balance',
        'closing_cash_balance',
        'cash_deposited_to_branch',
        'deposited_by_user_id',
        'deposit_receipt_number',
        'status',
        'submitted_at',
        'approved_by_user_id',
        'approved_at',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_amount_collected' => 'decimal:2',
        'opening_cash_balance' => 'decimal:2',
        'closing_cash_balance' => 'decimal:2',
        'cash_deposited_to_branch' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $appends = ['status_badge', 'variance'];

    /**
     * Get the field agent who submitted this report
     */
    public function fieldAgent()
    {
        return $this->belongsTo(FieldAgent::class);
    }

    /**
     * Get the teller who received the deposit
     */
    public function depositedBy()
    {
        return $this->belongsTo(User::class, 'deposited_by_user_id');
    }

    /**
     * Get the user who approved this report
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get all collections for this report date
     */
    public function collections()
    {
        return $this->hasMany(FieldCollection::class, 'field_agent_id', 'field_agent_id')
            ->whereDate('collection_date', $this->report_date);
    }

    /**
     * Scope to get pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get submitted reports
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get approved reports
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected reports
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to filter by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
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
            'pending' => '<span class="badge badge-secondary">Pending</span>',
            'submitted' => '<span class="badge badge-warning">Submitted</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Calculate variance between expected and actual cash
     */
    public function getVarianceAttribute()
    {
        $expectedCash = $this->opening_cash_balance + $this->total_amount_collected;
        $actualCash = $this->closing_cash_balance + $this->cash_deposited_to_branch;
        
        return $actualCash - $expectedCash;
    }

    /**
     * Check if there's a cash variance
     */
    public function hasVariance()
    {
        return abs($this->variance) > 0.01; // Allow for rounding differences
    }

    /**
     * Check if report can be submitted
     */
    public function canBeSubmitted()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if report can be approved
     */
    public function canBeApproved()
    {
        return $this->status === 'submitted';
    }

    /**
     * Submit this report
     */
    public function submit()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    /**
     * Approve this report
     */
    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by_user_id' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject this report
     */
    public function reject($userId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by_user_id' => $userId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Auto-calculate totals from collections
     */
    public function calculateTotalsFromCollections()
    {
        $collections = $this->collections()->where('status', '!=', 'rejected')->get();

        $this->total_collections = $collections->count();
        $this->total_amount_collected = $collections->sum('amount');
        $this->total_clients_paid = $collections->pluck('client_id')->unique()->count();

        return $this;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            // Auto-calculate totals if not provided
            if ($report->total_collections === 0) {
                $report->calculateTotalsFromCollections();
            }
        });
    }
}
