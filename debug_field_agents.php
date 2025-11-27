<?php

/**
 * Debug Field Agents
 * Check why field agents are not showing in dropdown
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\FieldAgent\Entities\FieldAgent;

echo "🔍 Debugging Field Agents\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check all field agents
echo "1️⃣  All Field Agents in Database:\n";
$allAgents = FieldAgent::all();
echo "   Total: " . $allAgents->count() . "\n\n";

foreach ($allAgents as $agent) {
    echo "   Agent ID: {$agent->id}\n";
    echo "   Agent Code: {$agent->agent_code}\n";
    echo "   User ID: {$agent->user_id}\n";
    echo "   Status: {$agent->status}\n";
    echo "   Full Name: {$agent->full_name}\n";
    echo "   ───────────────────────────────\n";
}

// Check active field agents
echo "\n2️⃣  Active Field Agents (using scope):\n";
$activeAgents = FieldAgent::active()->get();
echo "   Total Active: " . $activeAgents->count() . "\n\n";

foreach ($activeAgents as $agent) {
    echo "   ✅ {$agent->agent_code} - {$agent->full_name} (Status: {$agent->status})\n";
}

if ($activeAgents->count() === 0) {
    echo "   ⚠️  No active field agents found!\n";
    echo "   💡 This is why the dropdown is empty.\n\n";
    
    if ($allAgents->count() > 0) {
        echo "   🔧 SOLUTION: Update field agent status to 'active'\n";
        echo "   Run this in tinker:\n";
        echo "   \$agent = FieldAgent::first();\n";
        echo "   \$agent->status = 'active';\n";
        echo "   \$agent->save();\n\n";
    }
}

// Check what the controller would get
echo "\n3️⃣  What the Controller Gets:\n";
$fieldAgents = FieldAgent::active()->get();
echo "   Field Agents for dropdown: " . $fieldAgents->count() . "\n";

if ($fieldAgents->count() > 0) {
    echo "   ✅ Dropdown should populate with:\n";
    foreach ($fieldAgents as $agent) {
        echo "      - {$agent->agent_code} - {$agent->full_name}\n";
    }
} else {
    echo "   ❌ Dropdown will be EMPTY\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 DIAGNOSIS COMPLETE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
