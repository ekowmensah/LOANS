<?php

namespace Modules\FieldAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Entities\Branch;
use Modules\User\Entities\User;

class FieldAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_code',
        'branch_id',
        'commission_rate',
        'target_amount',
        'status',
        'phone_number',
        'national_id',
        'photo',
        'notes',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'target_amount' => 'decimal:2',
    ];

    /**
     * Get the user associated with this field agent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch this field agent is assigned to
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get all collections made by this field agent
     */
    public function collections()
    {
        return $this->hasMany(FieldCollection::class);
    }

    /**
     * Get all daily reports submitted by this field agent
     */
    public function dailyReports()
    {
        return $this->hasMany(FieldAgentDailyReport::class);
    }

    /**
     * Get collections for a specific date
     */
    public function collectionsForDate($date)
    {
        return $this->collections()->whereDate('collection_date', $date);
    }

    /**
     * Get pending collections
     */
    public function pendingCollections()
    {
        return $this->collections()->where('status', 'pending');
    }

    /**
     * Get verified collections
     */
    public function verifiedCollections()
    {
        return $this->collections()->where('status', 'verified');
    }

    /**
     * Get total collections for a period
     */
    public function totalCollectionsForPeriod($startDate, $endDate)
    {
        return $this->collections()
            ->whereBetween('collection_date', [$startDate, $endDate])
            ->where('status', '!=', 'rejected')
            ->sum('amount');
    }

    /**
     * Scope to get active field agents
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get field agents by branch
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Get the full name of the field agent
     */
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->first_name . ' ' . $this->user->last_name : 'N/A';
    }

    /**
     * Get the agent's performance percentage against target
     */
    public function getPerformancePercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }

        $currentMonth = now()->format('Y-m');
        $monthlyTotal = $this->collections()
            ->whereRaw("DATE_FORMAT(collection_date, '%Y-%m') = ?", [$currentMonth])
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        return ($monthlyTotal / $this->target_amount) * 100;
    }

    /**
     * Check if agent has met their target this month
     */
    public function hasMetTargetThisMonth()
    {
        return $this->performance_percentage >= 100;
    }
}
