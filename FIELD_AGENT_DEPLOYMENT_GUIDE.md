# Field Agent System - Deployment Guide

## 🚀 Quick Deployment (15 Minutes)

---

## Step 1: Run Permission Seeder (2 minutes)

### Option A: Using Artisan Command (Recommended)

```bash
php artisan db:seed --class=Modules\\FieldAgent\\Database\\Seeders\\FieldAgentPermissionsSeeder
```

### Option B: Using Tinker

```bash
php artisan tinker
```

Then run:

```php
(new Modules\FieldAgent\Database\Seeders\FieldAgentPermissionsSeeder)->run();
exit
```

**Expected Output:**
```
Field Agent permissions created successfully!
Permissions assigned to admin role!
Field Agent Manager role created!
Field Agent role created!
✅ All Field Agent permissions and roles created successfully!
```

---

## Step 2: Clear Cache (1 minute)

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## Step 3: Create Upload Directories (1 minute)

```bash
# Windows (PowerShell)
New-Item -ItemType Directory -Force -Path public/uploads/field_agents
New-Item -ItemType Directory -Force -Path public/uploads/field_collections

# Or manually create:
# public/uploads/field_agents/
# public/uploads/field_collections/
```

---

## Step 4: Verify Installation (2 minutes)

### Check Tables

```bash
php artisan tinker
```

```php
// Check if tables exist
DB::select("SHOW TABLES LIKE 'field_agents'");
DB::select("SHOW TABLES LIKE 'field_collections'");
DB::select("SHOW TABLES LIKE 'field_agent_daily_reports'");

// Should return results for all three
exit
```

### Check Permissions

```bash
php artisan tinker
```

```php
use Spatie\Permission\Models\Permission;

// Count field agent permissions
Permission::where('name', 'LIKE', 'field_agent.%')->count();
// Should return: 20

// List all permissions
Permission::where('name', 'LIKE', 'field_agent.%')->pluck('name');

exit
```

---

## Step 5: Create Your First Field Agent (3 minutes)

### Option A: Via Tinker

```bash
php artisan tinker
```

```php
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\User\Entities\User;
use Modules\Branch\Entities\Branch;

// Get first user (or create one)
$user = User::first();

// Get first branch
$branch = Branch::first();

// Create field agent
$agent = FieldAgent::create([
    'user_id' => $user->id,
    'agent_code' => 'FA001',
    'branch_id' => $branch->id,
    'commission_rate' => 5.00,
    'target_amount' => 100000.00,
    'status' => 'active',
    'phone_number' => '0244123456',
]);

echo "✅ Field agent created: {$agent->agent_code}\n";
echo "Agent ID: {$agent->id}\n";
echo "Agent Name: {$agent->full_name}\n";

exit
```

### Option B: Via Web Interface

1. Login to your application
2. Navigate to `/field-agent/agent`
3. Click "Add Field Agent"
4. Fill in the form
5. Submit

---

## Step 6: Test Collection Recording (3 minutes)

```bash
php artisan tinker
```

```php
use Modules\FieldAgent\Entities\FieldCollection;
use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;

// Get a client
$client = Client::first();

// Get their savings account
$savings = Savings::where('client_id', $client->id)->first();

// Get the field agent we created
$agent = \Modules\FieldAgent\Entities\FieldAgent::first();

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

echo "✅ Collection recorded!\n";
echo "Receipt Number: {$collection->receipt_number}\n";
echo "Amount: {$collection->amount}\n";
echo "Status: {$collection->status}\n";

exit
```

---

## Step 7: Test Verification (2 minutes)

```bash
php artisan tinker
```

```php
use Modules\FieldAgent\Entities\FieldCollection;

// Get the collection
$collection = FieldCollection::first();

// Verify it
$collection->verify(auth()->id() ?? 1);

echo "✅ Collection verified!\n";
echo "Status: {$collection->status}\n";

// Post to accounting
$collection->post(auth()->id() ?? 1);

echo "✅ Collection posted!\n";
echo "Status: {$collection->status}\n";

exit
```

---

## Step 8: Test Daily Report (2 minutes)

```bash
php artisan tinker
```

```php
use Modules\FieldAgent\Entities\FieldAgentDailyReport;

$agent = \Modules\FieldAgent\Entities\FieldAgent::first();

$report = FieldAgentDailyReport::create([
    'field_agent_id' => $agent->id,
    'report_date' => now(),
    'opening_cash_balance' => 1000.00,
    'closing_cash_balance' => 500.00,
    'cash_deposited_to_branch' => 1000.00,
    'total_clients_visited' => 10,
]);

// Auto-calculate totals
$report->calculateTotalsFromCollections();
$report->save();

echo "✅ Daily report created!\n";
echo "Total Collections: {$report->total_collections}\n";
echo "Total Amount: {$report->total_amount_collected}\n";
echo "Variance: {$report->variance}\n";

if ($report->hasVariance()) {
    echo "⚠️ WARNING: Cash variance detected!\n";
} else {
    echo "✅ No variance - Perfect!\n";
}

exit
```

---

## 🎯 Access URLs

Once deployed, access the system at:

### Main Pages
- **Field Agents List:** `http://your-domain/field-agent/agent`
- **Collections List:** `http://your-domain/field-agent/collection`
- **Verify Collections:** `http://your-domain/field-agent/collection/verify`
- **Daily Reports:** `http://your-domain/field-agent/daily-report`

### Quick Actions
- **Add Field Agent:** `http://your-domain/field-agent/agent/create`
- **Record Collection:** `http://your-domain/field-agent/collection/create`
- **Submit Daily Report:** `http://your-domain/field-agent/daily-report/create`

---

## 🔐 User Roles & Access

### Admin Role
**Has access to:**
- All field agent management
- All collections
- All reports
- All analytics

### Field Agent Manager Role
**Has access to:**
- View all agents
- Create/edit agents
- Verify collections
- Approve reports
- View analytics

### Field Agent Role
**Has access to:**
- Record collections (own only)
- Submit daily reports (own only)
- View own performance

---

## ✅ Verification Checklist

After deployment, verify:

- [ ] All 3 tables created
- [ ] 20 permissions created
- [ ] 3 roles created (admin, field_agent_manager, field_agent)
- [ ] Upload directories exist
- [ ] Can access `/field-agent/agent`
- [ ] Can create field agent
- [ ] Can record collection
- [ ] Receipt number auto-generated
- [ ] Can verify collection
- [ ] Can submit daily report
- [ ] Variance calculation works

---

## 🐛 Troubleshooting

### Issue: "Permission denied" when accessing pages

**Solution:**
```bash
php artisan tinker
```

```php
$user = \Modules\User\Entities\User::find(YOUR_USER_ID);
$user->givePermissionTo('field_agent.agents.index');
// Or assign admin role
$user->assignRole('admin');
exit
```

### Issue: "Table doesn't exist"

**Solution:**
```bash
php artisan migrate
```

### Issue: "Class not found"

**Solution:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue: "Upload failed"

**Solution:**
```bash
# Check directory permissions
# Windows: Right-click > Properties > Security
# Ensure write permissions for public/uploads/
```

### Issue: "Receipt number not generating"

**Solution:**
The receipt number is auto-generated in the FieldCollection model's boot method. Check:
```php
// Should be in FieldCollection.php
protected static function boot()
{
    parent::boot();
    static::creating(function ($collection) {
        if (empty($collection->receipt_number)) {
            $collection->receipt_number = self::generateReceiptNumber();
        }
    });
}
```

---

## 📊 Test Data Script

Want to create test data quickly? Run this:

```bash
php artisan tinker
```

```php
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\FieldAgent\Entities\FieldCollection;
use Modules\User\Entities\User;
use Modules\Branch\Entities\Branch;
use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;

// Create 3 field agents
$branch = Branch::first();
$users = User::take(3)->get();

foreach ($users as $index => $user) {
    FieldAgent::create([
        'user_id' => $user->id,
        'agent_code' => 'FA00' . ($index + 1),
        'branch_id' => $branch->id,
        'commission_rate' => 5.00,
        'target_amount' => 100000.00,
        'status' => 'active',
    ]);
}

echo "✅ 3 field agents created!\n";

// Create 10 test collections
$agents = FieldAgent::all();
$clients = Client::take(10)->get();

foreach ($clients as $client) {
    $savings = Savings::where('client_id', $client->id)->first();
    if ($savings) {
        FieldCollection::create([
            'field_agent_id' => $agents->random()->id,
            'collection_type' => 'savings_deposit',
            'reference_id' => $savings->id,
            'client_id' => $client->id,
            'amount' => rand(100, 1000),
            'collection_date' => now()->subDays(rand(0, 7)),
            'collection_time' => now(),
            'payment_method' => ['cash', 'mobile_money'][rand(0, 1)],
        ]);
    }
}

echo "✅ Test collections created!\n";
echo "Total collections: " . FieldCollection::count() . "\n";

exit
```

---

## 🎓 Training Users

### For Field Agents

**Daily Workflow:**
1. Login to system
2. Go to "Record Collection"
3. Select client and account
4. Enter amount
5. Click "Get Current Location"
6. Upload receipt photo
7. Submit
8. At end of day, submit daily report

### For Managers

**Daily Workflow:**
1. Login to system
2. Go to "Verify Collections"
3. Review pending collections
4. Check GPS location and photo
5. Verify or reject
6. Review daily reports
7. Approve or reject

### For Accountants

**Daily Workflow:**
1. Login to system
2. Go to "Collections"
3. Filter by "Verified" status
4. Post to accounting
5. Reconcile with teller deposits

---

## 📈 Monitoring & Maintenance

### Daily Checks
- [ ] Pending collections count
- [ ] Cash variances
- [ ] Unsubmitted reports

### Weekly Checks
- [ ] Agent performance vs targets
- [ ] Collection trends
- [ ] Rejection rates

### Monthly Checks
- [ ] Commission calculations
- [ ] Target achievements
- [ ] System performance

---

## 🚀 You're Ready!

Your Field Agent System is now:
- ✅ Fully deployed
- ✅ Permissions configured
- ✅ Tested and verified
- ✅ Ready for production use

**Next Steps:**
1. Train your field agents
2. Set up their mobile devices
3. Configure GPS settings
4. Start collecting!

**Need help?** Check the documentation files:
- FIELD_AGENT_QUICK_START.md
- FIELD_AGENT_COMPLETE_SUMMARY.md
- FIELD_AGENT_IMPLEMENTATION_PLAN.md

---

**Congratulations! Your Field Agent System is live!** 🎉
