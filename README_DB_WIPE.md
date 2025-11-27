# Database Wipe Scripts

This directory contains SQL scripts to clean the database while preserving core data.

## Available Scripts

### 1. WIPE_DB_PRESERVE_CORE.sql
**Quick wipe script** - Fast execution using TRUNCATE commands.

**Preserves:**
- ✅ Branches
- ✅ Users
- ✅ Clients (base records)
- ✅ Roles & Permissions
- ✅ Settings & Menus
- ✅ Product configurations
- ✅ System reference data

**Deletes:**
- ❌ All loans and transactions
- ❌ All savings and transactions
- ❌ All shares and transactions
- ❌ All groups and memberships
- ❌ All client relationships
- ❌ All payments and accounting
- ❌ All logs and communications

### 2. WIPE_DB_DETAILED.sql
**Detailed wipe script** - Shows progress with step-by-step messages.

Same preservation/deletion as above, but with:
- Progress messages for each step
- Verification queries at the end
- Counts of preserved data
- Confirmation of deleted data

## Usage

### Option 1: MySQL Command Line
```bash
mysql -u username -p database_name < WIPE_DB_PRESERVE_CORE.sql
```

### Option 2: phpMyAdmin
1. Open phpMyAdmin
2. Select your database
3. Go to "SQL" tab
4. Copy and paste the script content
5. Click "Go"

### Option 3: MySQL Workbench
1. Open MySQL Workbench
2. Connect to your database
3. File → Open SQL Script
4. Select the script file
5. Execute

### Option 4: Laravel Artisan (if you create a command)
```bash
php artisan db:wipe --preserve=branches,users,clients
```

## What Gets Preserved

### Core Tables
- `branches` - All branch records
- `users` - All user accounts
- `clients` - All client base records

### Configuration Tables
- `roles`, `permissions`, `role_has_permissions`
- `model_has_permissions`, `model_has_roles`
- `settings`, `menus`, `widgets`

### Product Configuration
- `loan_products`, `loan_product_linked_charges`
- `savings_products`, `savings_product_linked_charges`
- `share_products`, `share_product_linked_charges`

### Type/Option Tables
- `payment_types`
- `loan_transaction_types`
- `savings_transaction_types`
- `charge_types`, `charge_options`
- `loan_purposes`
- `loan_disbursement_channels`
- `loan_transaction_processing_strategies`

### Reference Data
- `countries`, `timezones`
- `titles`, `professions`
- `tax_rates`
- `sms_gateways`

### System Tables
- `migrations`
- `oauth_clients`, `oauth_personal_access_clients`

## What Gets Deleted

### Transactional Data
- All loan records and transactions
- All savings accounts and transactions
- All share accounts and transactions
- All payment details
- All journal entries

### Relationship Data
- All groups and group memberships
- All client files and identifications
- All client relationships
- All guarantors and collaterals

### Operational Data
- All communication logs
- All activity logs
- All notifications
- All payroll records
- All wallet transactions

### Temporary Data
- All OAuth tokens
- All password reset tokens
- All personal access tokens
- All failed jobs

## Safety Notes

⚠️ **IMPORTANT WARNINGS:**

1. **Backup First**: Always backup your database before running these scripts
   ```bash
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Test Environment**: Test the script in a development environment first

3. **Foreign Keys**: Scripts disable foreign key checks temporarily - ensure they're re-enabled

4. **No Undo**: TRUNCATE and DELETE operations cannot be rolled back

5. **Permissions**: Ensure you have sufficient database privileges

## Verification

After running the script, verify the results:

```sql
-- Check preserved data
SELECT COUNT(*) FROM branches;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM clients;

-- Verify deleted data (should all be 0)
SELECT COUNT(*) FROM loans;
SELECT COUNT(*) FROM savings;
SELECT COUNT(*) FROM groups;
SELECT COUNT(*) FROM loan_transactions;
SELECT COUNT(*) FROM savings_transactions;
```

## Restoration

If you need to restore after wiping:

```bash
mysql -u username -p database_name < backup_file.sql
```

## Common Use Cases

### 1. Fresh Start for Testing
Use when you want to test the system with clean data but keep your configuration.

### 2. Production Cleanup
Use when migrating to production and want to remove test data.

### 3. Demo Reset
Use when resetting a demo environment between presentations.

### 4. Development Reset
Use when you want to start fresh but keep your user accounts and branch structure.

## Customization

To preserve additional tables, comment out the relevant TRUNCATE/DELETE lines:

```sql
-- TRUNCATE TABLE `table_name`;  -- Commented out to preserve
```

To delete additional tables, add new lines:

```sql
TRUNCATE TABLE `new_table_name`;
```

## Support

If you encounter issues:
1. Check MySQL error logs
2. Verify foreign key constraints
3. Ensure sufficient privileges
4. Check table names match your schema

## Version History

- **v1.0** - Initial release with core preservation
- Preserves: branches, users, clients
- Deletes: all transactional data
