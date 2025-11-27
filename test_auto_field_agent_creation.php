<?php

/**
 * Test Automatic Field Agent Creation
 * Demonstrates automatic field agent creation when field_agent role is assigned
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\User\Entities\User;
use Modules\FieldAgent\Entities\FieldAgent;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "🧪 Testing Automatic Field Agent Creation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Ensure field_agent role exists
$fieldAgentRole = Role::where('name', 'field_agent')->first();
if (!$fieldAgentRole) {
    echo "❌ field_agent role not found!\n";
    echo "   Run: php seed_field_agent_permissions.php\n";
    exit(1);
}

echo "✅ field_agent role exists\n\n";

// Test 1: Create a new user with field_agent role
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: Create new user with field_agent role\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$testEmail = 'test.agent.' . time() . '@example.com';

$user = User::create([
    'first_name' => 'Test',
    'last_name' => 'Agent',
    'email' => $testEmail,
    'password' => Hash::make('password'),
    'gender' => 'male',
    'phone' => '0244000000',
    'email_verified_at' => now(),
]);

echo "✅ User created: {$user->first_name} {$user->last_name} (ID: {$user->id})\n";

// Check if field agent exists BEFORE role assignment
$fieldAgentBefore = FieldAgent::where('user_id', $user->id)->first();
echo "   Field Agent before role: " . ($fieldAgentBefore ? "EXISTS ❌" : "NONE ✅") . "\n\n";

// Assign field_agent role
echo "📝 Assigning field_agent role...\n";
$user->assignRole('field_agent');

// Trigger the observer by updating the user
$user->touch();

echo "✅ Role assigned\n\n";

// Check if field agent was created automatically
sleep(1); // Give it a moment
$fieldAgentAfter = FieldAgent::where('user_id', $user->id)->first();

if ($fieldAgentAfter) {
    echo "🎉 SUCCESS! Field Agent created automatically!\n";
    echo "   Agent Code: {$fieldAgentAfter->agent_code}\n";
    echo "   Status: {$fieldAgentAfter->status}\n";
    echo "   Commission Rate: {$fieldAgentAfter->commission_rate}%\n";
    echo "   Target Amount: " . number_format($fieldAgentAfter->target_amount, 2) . "\n";
} else {
    echo "❌ FAILED: Field Agent was NOT created automatically\n";
    echo "   This might be because the observer isn't triggered on create.\n";
    echo "   Let's test with role update...\n\n";
}

// Test 2: Remove and re-add role
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: Remove and re-add field_agent role\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📝 Removing field_agent role...\n";
$user->removeRole('field_agent');
$user->touch();
sleep(1);

$fieldAgentRemoved = FieldAgent::where('user_id', $user->id)->first();
if ($fieldAgentRemoved) {
    echo "   Field Agent status: {$fieldAgentRemoved->status}\n";
    if ($fieldAgentRemoved->status === 'inactive') {
        echo "   ✅ Field Agent deactivated (status changed to inactive)\n";
    } else {
        echo "   ⚠️  Field Agent still active\n";
    }
} else {
    echo "   ℹ️  No field agent record\n";
}

echo "\n📝 Re-assigning field_agent role...\n";
$user->assignRole('field_agent');
$user->touch();
sleep(1);

$fieldAgentReAdded = FieldAgent::where('user_id', $user->id)->first();
if ($fieldAgentReAdded) {
    echo "   Field Agent status: {$fieldAgentReAdded->status}\n";
    if ($fieldAgentReAdded->status === 'active') {
        echo "   ✅ Field Agent reactivated!\n";
    } else {
        echo "   ⚠️  Field Agent not reactivated\n";
    }
}

// Test 3: Update existing user with field_agent role
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: Update existing user (add field_agent role)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get a user without field_agent role
$existingUser = User::whereDoesntHave('roles', function($q) {
    $q->where('name', 'field_agent');
})->whereDoesntHave('fieldAgent')->first();

if ($existingUser) {
    echo "✅ Found existing user: {$existingUser->first_name} {$existingUser->last_name} (ID: {$existingUser->id})\n";
    
    echo "📝 Assigning field_agent role to existing user...\n";
    $existingUser->syncRoles(['field_agent']);
    sleep(1);
    
    $newFieldAgent = FieldAgent::where('user_id', $existingUser->id)->first();
    if ($newFieldAgent) {
        echo "🎉 SUCCESS! Field Agent created for existing user!\n";
        echo "   Agent Code: {$newFieldAgent->agent_code}\n";
        echo "   Status: {$newFieldAgent->status}\n";
    } else {
        echo "❌ Field Agent was NOT created\n";
    }
} else {
    echo "ℹ️  No suitable existing user found for testing\n";
}

// Summary
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 TEST SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$totalFieldAgents = FieldAgent::count();
echo "Total Field Agents in system: {$totalFieldAgents}\n\n";

echo "✨ How it works:\n";
echo "   1. When you create/update a user and assign 'field_agent' role\n";
echo "   2. The system automatically creates a FieldAgent record\n";
echo "   3. Agent code is auto-generated (FA001, FA002, etc.)\n";
echo "   4. Default commission rate: 5%\n";
echo "   5. Default target: 100,000\n";
echo "   6. Status: active\n\n";

echo "💡 To test via web interface:\n";
echo "   1. Go to /user/create\n";
echo "   2. Fill in user details\n";
echo "   3. Select 'field_agent' role\n";
echo "   4. Save\n";
echo "   5. Check /field-agent/agent - new agent should appear!\n\n";

// Cleanup test user
echo "🧹 Cleaning up test user...\n";
if ($fieldAgentAfter) {
    $fieldAgentAfter->delete();
}
$user->delete();
echo "✅ Cleanup complete\n\n";
