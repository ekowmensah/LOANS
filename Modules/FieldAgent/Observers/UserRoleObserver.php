<?php

namespace Modules\FieldAgent\Observers;

use Modules\User\Entities\User;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\Branch\Entities\Branch;

class UserRoleObserver
{
    /**
     * Handle the User "updated" event.
     * This is triggered after roles are synced
     *
     * @param  \Modules\User\Entities\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Only process if roles were actually changed
        if (!$user->wasChanged()) {
            return;
        }
        
        // Check if user has field_agent role
        $hasFieldAgentRole = $user->hasRole('field_agent');
        
        // Check if user already has a field agent record
        $fieldAgent = FieldAgent::where('user_id', $user->id)->first();
        
        // If user has field_agent role but no field agent record, create one
        if ($hasFieldAgentRole && !$fieldAgent) {
            $this->createFieldAgent($user);
            return;
        }
        
        // If user has field_agent role and has an inactive field agent record, reactivate it
        if ($hasFieldAgentRole && $fieldAgent && $fieldAgent->status !== 'active') {
            $fieldAgent->update(['status' => 'active']);
            activity()->on($fieldAgent)
                ->withProperties(['id' => $fieldAgent->id])
                ->log('Field Agent reactivated - role assigned');
            return;
        }
        
        // If user doesn't have field_agent role but has a field agent record, deactivate it
        if (!$hasFieldAgentRole && $fieldAgent && $fieldAgent->status === 'active') {
            $fieldAgent->update(['status' => 'inactive']);
            activity()->on($fieldAgent)
                ->withProperties(['id' => $fieldAgent->id])
                ->log('Field Agent deactivated - role removed');
            return;
        }
    }
    
    /**
     * Create a field agent for the user
     *
     * @param  \Modules\User\Entities\User  $user
     * @return void
     */
    protected function createFieldAgent(User $user)
    {
        // Generate unique agent code
        $agentCode = $this->generateAgentCode();
        
        // Get user's branch or default to first branch
        $branchId = $user->branch_id ?? Branch::first()->id ?? null;
        
        if (!$branchId) {
            \Log::warning("Cannot create field agent for user {$user->id}: No branch available");
            return;
        }
        
        // Create field agent
        $fieldAgent = FieldAgent::create([
            'user_id' => $user->id,
            'agent_code' => $agentCode,
            'branch_id' => $branchId,
            'commission_rate' => 5.00, // Default commission rate
            'target_amount' => 100000.00, // Default target
            'status' => 'active',
            'phone_number' => $user->phone,
        ]);
        
        activity()->on($fieldAgent)
            ->withProperties(['id' => $fieldAgent->id])
            ->log('Field Agent created automatically from user role');
        
        \Log::info("Field Agent created automatically for user {$user->id} with code {$agentCode}");
    }
    
    /**
     * Generate a unique agent code
     *
     * @return string
     */
    protected function generateAgentCode()
    {
        $prefix = 'FA';
        $lastAgent = FieldAgent::orderBy('id', 'desc')->first();
        
        if ($lastAgent) {
            // Extract number from last agent code (e.g., FA001 -> 001)
            $lastNumber = (int) substr($lastAgent->agent_code, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format with leading zeros (e.g., FA001, FA002, etc.)
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
