<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_members';
    public $timestamps = false;
    
    protected $fillable = [
        'group_id',
        'client_id',
        'role',
        'joined_at',
        'left_at',
        'status'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    // Relationships
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
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

    public function scopeLeaders($query)
    {
        return $query->where('role', 'leader');
    }

    public function scopeTreasurers($query)
    {
        return $query->where('role', 'treasurer');
    }

    // Accessors & Mutators
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getIsLeaderAttribute()
    {
        return $this->role === 'leader';
    }

    public function getMembershipDurationAttribute()
    {
        $start = $this->joined_at;
        $end = $this->left_at ?? now();
        
        return $start->diffInDays($end);
    }
}