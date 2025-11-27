<?php

/**
 * Create Test Field Agent
 * Creates a field agent for testing
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\FieldAgent\Entities\FieldAgent;
use Modules\User\Entities\User;
use Modules\Branch\Entities\Branch;

echo "🚀 Creating Test Field Agent\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get first user
$user = User::first();
if (!$user) {
    echo "❌ No users found in database!\n";
    exit(1);
}

echo "✅ Found user: {$user->first_name} {$user->last_name} (ID: {$user->id})\n";

// Get first branch
$branch = Branch::first();
if (!$branch) {
    echo "❌ No branches found in database!\n";
    exit(1);
}

echo "✅ Found branch: {$branch->name} (ID: {$branch->id})\n\n";

// Check if user already has a field agent
$existingAgent = FieldAgent::where('user_id', $user->id)->first();
if ($existingAgent) {
    echo "⚠️  User already has a field agent (Code: {$existingAgent->agent_code})\n";
    echo "   Status: {$existingAgent->status}\n\n";
    
    if ($existingAgent->status !== 'active') {
        echo "🔧 Updating status to 'active'...\n";
        $existingAgent->status = 'active';
        $existingAgent->save();
        echo "✅ Status updated!\n\n";
    }
    
    echo "Field Agent Details:\n";
    echo "  - ID: {$existingAgent->id}\n";
    echo "  - Code: {$existingAgent->agent_code}\n";
    echo "  - Name: {$existingAgent->full_name}\n";
    echo "  - Status: {$existingAgent->status}\n";
    echo "  - Branch: {$existingAgent->branch->name}\n";
    echo "  - Commission Rate: {$existingAgent->commission_rate}%\n";
    echo "  - Target: " . number_format($existingAgent->target_amount, 2) . "\n\n";
    
    exit(0);
}

// Create new field agent
echo "📝 Creating new field agent...\n";

try {
    $agent = FieldAgent::create([
        'user_id' => $user->id,
        'agent_code' => 'FA001',
        'branch_id' => $branch->id,
        'commission_rate' => 5.00,
        'target_amount' => 100000.00,
        'status' => 'active',
        'phone_number' => $user->phone ?? '0244123456',
    ]);

    echo "✅ Field agent created successfully!\n\n";
    
    echo "Field Agent Details:\n";
    echo "  - ID: {$agent->id}\n";
    echo "  - Code: {$agent->agent_code}\n";
    echo "  - Name: {$agent->full_name}\n";
    echo "  - Status: {$agent->status}\n";
    echo "  - Branch: {$agent->branch->name}\n";
    echo "  - Commission Rate: {$agent->commission_rate}%\n";
    echo "  - Target: " . number_format($agent->target_amount, 2) . "\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎉 SUCCESS!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "Now try accessing:\n";
    echo "  - Field Agents List: /field-agent/agent\n";
    echo "  - Record Collection: /field-agent/collection/create\n\n";
    
    echo "The dropdown should now show: {$agent->agent_code} - {$agent->full_name}\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating field agent:\n";
    echo "   " . $e->getMessage() . "\n\n";
    
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo "💡 Agent code 'FA001' already exists. Try a different code.\n";
    }
    
    exit(1);
}
