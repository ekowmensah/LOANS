# Field Agent System - Phase 1 Implementation Progress

## ✅ Completed Tasks

### 1. Database Migrations Created

**Three core tables have been created:**

#### A. `field_agents` Table
**Location:** `database/migrations/2025_11_27_101516_create_field_agents_table.php`

**Fields:**
- `id` - Primary key
- `user_id` - Link to users table
- `agent_code` - Unique agent identifier
- `branch_id` - Assigned branch
- `commission_rate` - Commission percentage (0-100%)
- `target_amount` - Monthly collection target
- `status` - active, suspended, inactive
- `phone_number` - Contact number
- `national_id` - National ID/SSN
- `photo` - Profile photo path
- `notes` - Additional notes
- `timestamps` - Created/Updated dates

**Indexes:**
- Unique on `agent_code`
- Index on `status`
- Foreign keys to `users` and `branches`

#### B. `field_collections` Table
**Location:** `database/migrations/2025_11_27_101525_create_field_collections_table.php`

**Fields:**
- `id` - Primary key
- `field_agent_id` - Agent who collected
- `collection_type` - savings_deposit, loan_repayment, share_purchase
- `reference_id` - ID of savings/loan account
- `client_id` - Client who paid
- `amount` - Collection amount
- `collection_date` - Date of collection
- `collection_time` - Time of collection
- `latitude` - GPS latitude
- `longitude` - GPS longitude
- `location_address` - Address where collected
- `receipt_number` - Unique receipt number
- `payment_method` - cash, mobile_money, cheque, bank_transfer
- `status` - pending, verified, rejected, posted
- `verified_by_user_id` - Who verified
- `verified_at` - When verified
- `posted_by_user_id` - Who posted to accounting
- `posted_at` - When posted
- `notes` - Additional notes
- `photo_proof` - Receipt/proof photo path
- `rejection_reason` - Why rejected (if applicable)
- `timestamps` - Created/Updated dates

**Indexes:**
- Composite index on `field_agent_id` + `collection_date`
- Index on `status`
- Index on `collection_type`
- Foreign keys to `field_agents`, `clients`, `users`

#### C. `field_agent_daily_reports` Table
**Location:** `database/migrations/2025_11_27_101533_create_field_agent_daily_reports_table.php`

**Fields:**
- `id` - Primary key
- `field_agent_id` - Agent submitting report
- `report_date` - Date of report
- `total_collections` - Number of collections
- `total_amount_collected` - Total amount collected
- `total_clients_visited` - Clients visited
- `total_clients_paid` - Clients who paid
- `opening_cash_balance` - Cash at start of day
- `closing_cash_balance` - Cash at end of day
- `cash_deposited_to_branch` - Amount deposited
- `deposited_by_user_id` - Teller who received
- `deposit_receipt_number` - Deposit receipt
- `status` - pending, submitted, approved, rejected
- `submitted_at` - When submitted
- `approved_by_user_id` - Who approved
- `approved_at` - When approved
- `notes` - Additional notes
- `rejection_reason` - Why rejected (if applicable)
- `timestamps` - Created/Updated dates

**Indexes:**
- Unique constraint on `field_agent_id` + `report_date` (one report per day)
- Index on `status`
- Foreign keys to `field_agents` and `users`

---

## 🔄 Next Steps

### 2. Run Migrations
**Note:** There's a pending migration issue that needs to be resolved first:
- Error in `2024_01_15_000002_add_group_menus_and_permissions` migration
- Column 'permission' not found in 'menus' table
- **Action Required:** Fix the existing migration before running field agent migrations

**To run migrations after fix:**
```bash
php artisan migrate
```

### 3. Create Models (Next Task)

Need to create three Eloquent models:

**A. FieldAgent Model**
```php
// app/Models/FieldAgent.php or Modules/FieldAgent/Entities/FieldAgent.php
```
**Relationships:**
- belongsTo User
- belongsTo Branch
- hasMany FieldCollection
- hasMany FieldAgentDailyReport

**B. FieldCollection Model**
```php
// Modules/FieldAgent/Entities/FieldCollection.php
```
**Relationships:**
- belongsTo FieldAgent
- belongsTo Client
- belongsTo User (verifier)
- belongsTo User (poster)
- morphTo Reference (savings or loan)

**C. FieldAgentDailyReport Model**
```php
// Modules/FieldAgent/Entities/FieldAgentDailyReport.php
```
**Relationships:**
- belongsTo FieldAgent
- belongsTo User (deposited_by)
- belongsTo User (approved_by)
- hasMany FieldCollection (for that date)

### 4. Create FieldAgent Module Structure

**Directory structure to create:**
```
Modules/FieldAgent/
├── Config/
│   ├── config.php
│   ├── permissions.php
│   └── menus.php
├── Entities/
│   ├── FieldAgent.php
│   ├── FieldCollection.php
│   └── FieldAgentDailyReport.php
├── Http/
│   └── Controllers/
│       ├── FieldAgentController.php
│       ├── FieldCollectionController.php
│       └── DailyReportController.php
├── Resources/
│   └── views/
│       ├── field_agent/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── collection/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── verify.blade.php
│       │   └── show.blade.php
│       └── daily_report/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── show.blade.php
└── Routes/
    ├── web.php
    └── api.php
```

### 5. Implement Controllers

**A. FieldAgentController**
- index() - List all field agents
- create() - Show create form
- store() - Save new field agent
- edit() - Show edit form
- update() - Update field agent
- destroy() - Delete field agent
- show() - View field agent details

**B. FieldCollectionController**
- index() - List collections (with filters)
- create() - Record new collection
- store() - Save collection
- show() - View collection details
- verify() - Verify collection
- reject() - Reject collection
- post() - Post to accounting

**C. DailyReportController**
- index() - List daily reports
- create() - Create daily report
- store() - Save daily report
- show() - View report details
- approve() - Approve report
- reject() - Reject report

### 6. Create Views

**Priority views:**
1. Field Agent Management (CRUD)
2. Collection Recording Form
3. Collection Verification Dashboard
4. Daily Report Submission Form
5. Daily Report Approval Interface

### 7. Add Permissions

**Permissions to add:**
```php
'field_agent.agents.view',
'field_agent.agents.create',
'field_agent.agents.edit',
'field_agent.agents.delete',
'field_agent.collections.view',
'field_agent.collections.create',
'field_agent.collections.verify',
'field_agent.collections.post',
'field_agent.collections.reject',
'field_agent.reports.view',
'field_agent.reports.create',
'field_agent.reports.approve',
'field_agent.reports.reject',
```

### 8. Create Roles

**New roles:**
- `field_agent` - For field agents
- `field_agent_manager` - For managers overseeing field agents

---

## 📋 Implementation Checklist

- [x] Create field_agents migration
- [x] Create field_collections migration
- [x] Create field_agent_daily_reports migration
- [ ] Fix existing migration issue
- [ ] Run migrations
- [ ] Create FieldAgent model
- [ ] Create FieldCollection model
- [ ] Create FieldAgentDailyReport model
- [ ] Create module structure
- [ ] Create FieldAgentController
- [ ] Create FieldCollectionController
- [ ] Create DailyReportController
- [ ] Create field agent views
- [ ] Create collection views
- [ ] Create daily report views
- [ ] Add permissions
- [ ] Create roles
- [ ] Add menu items
- [ ] Test field agent creation
- [ ] Test collection recording
- [ ] Test collection verification
- [ ] Test daily report submission
- [ ] Test daily report approval

---

## 🎯 Current Status

**Phase 1 Progress: 15%**

✅ **Completed:**
- Database schema design
- Migration files created

⏳ **In Progress:**
- Waiting for existing migration fix

🔜 **Next:**
- Create models
- Build module structure
- Implement controllers

---

## 🚨 Blockers

1. **Migration Error:** 
   - File: `2024_01_15_000002_add_group_menus_and_permissions`
   - Issue: Column 'permission' not found in 'menus' table
   - **Resolution needed before proceeding**

---

## 💡 Quick Start After Migration Fix

Once migrations are fixed and run successfully:

```bash
# 1. Create module structure
php artisan module:make FieldAgent

# 2. Create models
php artisan make:model Modules/FieldAgent/Entities/FieldAgent
php artisan make:model Modules/FieldAgent/Entities/FieldCollection
php artisan make:model Modules/FieldAgent/Entities/FieldAgentDailyReport

# 3. Create controllers
php artisan make:controller Modules/FieldAgent/Http/Controllers/FieldAgentController --resource
php artisan make:controller Modules/FieldAgent/Http/Controllers/FieldCollectionController --resource
php artisan make:controller Modules/FieldAgent/Http/Controllers/DailyReportController --resource
```

---

**Ready to proceed once the migration blocker is resolved!**
