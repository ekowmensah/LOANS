<?php

namespace Modules\FieldAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Client;
use Modules\User\Entities\User;

class FieldAgentClientAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_agent_id',
        'client_id',
        'assigned_by_user_id',
        'assigned_date',
        'unassigned_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'unassigned_date' => 'date',
    ];

    /**
     * Get the field agent for this assignment
     */
    public function fieldAgent()
    {
        return $this->belongsTo(FieldAgent::class);
    }

    /**
     * Get the client for this assignment
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who made the assignment
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    /**
     * Scope to get active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive assignments
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to get assignments for a specific field agent
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('field_agent_id', $agentId);
    }

    /**
     * Scope to get assignments for a specific client
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Check if assignment is currently active
     */
    public function isActive()
    {
        return $this->status === 'active' && is_null($this->unassigned_date);
    }

    /**
     * Deactivate this assignment
     */
    public function deactivate()
    {
        $this->status = 'inactive';
        $this->unassigned_date = now();
        $this->save();
    }
}
