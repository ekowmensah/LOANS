# Field Agent System - Quick Start Guide

## 🚀 Getting Started in 5 Minutes

### Step 1: Enable the Module (30 seconds)

The module is already created. Just ensure it's recognized:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 2: Seed Permissions (2 minutes)

Create a seeder or run in tinker:

```bash
php artisan tinker
```

```php
use Spatie\Permission\Models\Permission;

$permissions = [
    'field_agent.agents.index',
    'field_agent.agents.create',
    'field_agent.agents.edit',
    'field_agent.agents.destroy',
    'field_agent.agents.view',
    'field_agent.collections.index',
    'field_agent.collections.create',
    'field_agent.collections.view',
    'field_agent.collections.verify',
    'field_agent.collections.post',
    'field_agent.collections.reject',
    'field_agent.collections.view_own',
    'field_agent.reports.index',
    'field_agent.reports.create',
    'field_agent.reports.view',
    'field_agent.reports.approve',
    'field_agent.reports.reject',
    'field_agent.reports.view_own',
    'field_agent.dashboard.view',
    'field_agent.analytics.view',
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
}

echo "Permissions created!\n";
```

### Step 3: Assign Permissions to Your Role (1 minute)

```php
use Spatie\Permission\Models\Role;

// For admin role
$role = Role::where('name', 'admin')->first();
if ($role) {
    $role->givePermissionTo([
        'field_agent.agents.index',
        'field_agent.agents.create',
        'field_agent.agents.edit',
        'field_agent.agents.view',
        'field_agent.collections.index',
        'field_agent.collections.create',
        'field_agent.collections.verify',
        'field_agent.collections.post',
        'field_agent.reports.index',
        'field_agent.reports.approve',
    ]);
    echo "Permissions assigned to admin!\n";
}
```

### Step 4: Create Your First Field Agent (1 minute)

```php
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\User\Entities\User;
use Modules\Branch\Entities\Branch;

// Get a user (or create one)
$user = User::first(); // Or create a new user

// Get a branch
$branch = Branch::first();

// Create field agent
$agent = FieldAgent::create([
    'user_id' => $user->id,
    'agent_code' => 'FA001',
    'branch_id' => $branch->id,
    'commission_rate' => 5.00, // 5%
    'target_amount' => 100000.00, // Monthly target
    'status' => 'active',
    'phone_number' => '0244123456',
]);

echo "Field agent created: {$agent->agent_code}\n";
```

### Step 5: Test Collection Recording (1 minute)

```php
use Modules\FieldAgent\Entities\FieldCollection;
use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;

// Get a client and their savings account
$client = Client::first();
$savings = Savings::where('client_id', $client->id)->first();

// Record a collection
$collection = FieldCollection::create([
    'field_agent_id' => $agent->id,
    'collection_type' => 'savings_deposit',
    'reference_id' => $savings->id,
    'client_id' => $client->id,
    'amount' => 500.00,
    'collection_date' => now(),
    'collection_time' => now(),
    'payment_method' => 'cash',
    'latitude' => 5.6037,
    'longitude' => -0.1870,
    'location_address' => 'Accra, Ghana',
]);

echo "Collection recorded! Receipt: {$collection->receipt_number}\n";
```

---

## 📋 Common Tasks

### Verify a Collection

```php
$collection = FieldCollection::find(1);
$collection->verify(auth()->id());
echo "Collection verified!\n";
```

### Reject a Collection

```php
$collection = FieldCollection::find(1);
$collection->reject(auth()->id(), 'Invalid amount');
echo "Collection rejected!\n";
```

### Post to Accounting

```php
$collection = FieldCollection::find(1);
$collection->post(auth()->id());
echo "Collection posted!\n";
```

### Create Daily Report

```php
use Modules\FieldAgent\Entities\FieldAgentDailyReport;

$report = FieldAgentDailyReport::create([
    'field_agent_id' => $agent->id,
    'report_date' => now(),
    'opening_cash_balance' => 1000.00,
    'closing_cash_balance' => 500.00,
    'cash_deposited_to_branch' => 1000.00,
    'total_clients_visited' => 10,
]);

// Auto-calculates totals from collections
$report->calculateTotalsFromCollections();
$report->save();

echo "Daily report created!\n";
echo "Variance: {$report->variance}\n";
```

---

## 🔍 Quick Queries

### Get Agent Performance

```php
$agent = FieldAgent::find(1);
echo "Performance: {$agent->performance_percentage}%\n";
echo "Target met: " . ($agent->hasMetTargetThisMonth() ? 'Yes' : 'No') . "\n";
```

### Get Pending Collections

```php
$pending = FieldCollection::pending()->count();
echo "Pending collections: {$pending}\n";
```

### Get Today's Collections for Agent

```php
$today = FieldCollection::where('field_agent_id', 1)
    ->whereDate('collection_date', today())
    ->sum('amount');
echo "Today's collections: " . number_format($today, 2) . "\n";
```

### Get Cash Variance

```php
$report = FieldAgentDailyReport::find(1);
if ($report->hasVariance()) {
    echo "WARNING: Cash variance of {$report->variance}\n";
}
```

---

## 🌐 Access URLs

Once views are complete, access at:

- **Field Agents:** `/field-agent/agent`
- **Collections:** `/field-agent/collection`
- **Verify Collections:** `/field-agent/collection/verify`
- **Daily Reports:** `/field-agent/daily-report`

---

## 🎯 Testing Checklist

- [ ] Create field agent
- [ ] Record savings deposit
- [ ] Record loan repayment
- [ ] Verify collection
- [ ] Reject collection
- [ ] Post collection
- [ ] Create daily report
- [ ] Submit report
- [ ] Approve report
- [ ] Check cash variance
- [ ] View agent performance

---

## 🐛 Troubleshooting

### "Permission denied"
```bash
# Assign permissions to your role
php artisan tinker
$role = Role::where('name', 'admin')->first();
$role->givePermissionTo('field_agent.agents.index');
```

### "Table doesn't exist"
```bash
# Run migrations
php artisan migrate
```

### "Class not found"
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

---

## 📞 Support

**Files to check:**
- Models: `Modules/FieldAgent/Entities/`
- Controllers: `Modules/FieldAgent/Http/Controllers/`
- Routes: `Modules/FieldAgent/Routes/web.php`
- Migrations: `database/migrations/2025_11_27_*`

**Documentation:**
- Full implementation: `FIELD_AGENT_IMPLEMENTATION_SUMMARY.md`
- Phase 1 complete: `FIELD_AGENT_PHASE1_COMPLETE.md`

---

**You're ready to go! 🚀**
