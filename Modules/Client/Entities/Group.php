<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;
use Modules\User\Entities\User;

class Group extends Model
{
    protected $table = 'groups';
    
    protected $fillable = [
        'name',
        'description',
        'loan_officer_id',
        'branch_id',
        'meeting_frequency',
        'meeting_day',
        'status'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    // Relationships
    public function loan_officer()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id');
    }

    public function active_members()
    {
        return $this->hasMany(GroupMember::class, 'group_id')->where('status', 'active');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'group_members', 'group_id', 'client_id')
                    ->withPivot('role', 'joined_at', 'left_at', 'status');
    }

    public function active_clients()
    {
        return $this->belongsToMany(Client::class, 'group_members', 'group_id', 'client_id')
                    ->withPivot('role', 'joined_at', 'left_at', 'status')
                    ->wherePivot('status', 'active');
    }

    /**
     * Get the loans for this group
     */
    public function loans()
    {
        return $this->hasMany('Modules\Loan\Entities\Loan', 'group_id', 'id')
                    ->where('client_type', 'group');
    }

    /**
     * Get the group member loan allocations for this group
     */
    public function memberAllocations()
    {
        return $this->hasMany('Modules\Client\Entities\GroupMemberLoanAllocation');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors & Mutators
    public function getMemberCountAttribute()
    {
        return $this->active_members()->count();
    }

    public function getTotalLoansAttribute()
    {
        return $this->loans()->count();
    }

    public function getActiveLoanBalanceAttribute()
    {
        return $this->loans()->where('status', 'active')->sum('principal');
    }
}