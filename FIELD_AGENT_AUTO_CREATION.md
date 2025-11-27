# Field Agent Auto-Creation Feature

## 🎯 Overview

The Field Agent system now automatically creates and manages Field Agent records when users are assigned the `field_agent` role. This eliminates the need for manual field agent creation and ensures consistency.

---

## ✨ How It Works

### **Automatic Creation**
When you create or update a user and assign the `field_agent` role:
1. ✅ A Field Agent record is **automatically created**
2. ✅ Agent code is **auto-generated** (FA001, FA002, FA003, etc.)
3. ✅ Default values are set:
   - **Commission Rate:** 5%
   - **Target Amount:** 100,000
   - **Status:** Active
   - **Branch:** User's branch (or first available branch)
   - **Phone:** User's phone number

### **Automatic Deactivation**
When you remove the `field_agent` role from a user:
- ⚠️ The Field Agent record is **deactivated** (status changed to 'inactive')
- 📝 The record is **NOT deleted** (preserves historical data)
- 🔒 The agent will **not appear** in dropdowns (only active agents show)

### **Automatic Reactivation**
When you re-assign the `field_agent` role to a user who previously had it:
- ✅ The existing Field Agent record is **reactivated** (status changed to 'active')
- ✅ All previous data is **preserved** (agent code, commission rate, etc.)
- ✅ The agent **reappears** in dropdowns

---

## 📋 Step-by-Step Guide

### **Method 1: Create New User with Field Agent Role**

1. Go to `/user/create`
2. Fill in user details:
   - First Name
   - Last Name
   - Email
   - Password
   - Gender
   - Phone
   - Branch (optional - will use first branch if not set)
3. Select **"field_agent"** role
4. Click **Save**

**Result:**
- ✅ User created
- ✅ Field Agent automatically created
- ✅ Agent code auto-generated (e.g., FA001)
- ✅ Appears in `/field-agent/agent` list
- ✅ Available in collection dropdown

---

### **Method 2: Update Existing User to Field Agent**

1. Go to `/user/{id}/edit`
2. Add **"field_agent"** to the roles
3. Click **Save**

**Result:**
- ✅ Field Agent automatically created
- ✅ Agent code auto-generated
- ✅ User can now record collections

---

### **Method 3: Remove Field Agent Role**

1. Go to `/user/{id}/edit`
2. Remove **"field_agent"** from the roles
3. Click **Save**

**Result:**
- ⚠️ Field Agent status changed to 'inactive'
- 🔒 Agent no longer appears in dropdowns
- 📊 Historical data preserved

---

## 🔧 Technical Details

### **Observer Pattern**
The system uses a Laravel Observer (`UserRoleObserver`) that listens to User model updates:

```php
// Registered in FieldAgentServiceProvider
\Modules\User\Entities\User::observe(\Modules\FieldAgent\Observers\UserRoleObserver::class);
```

### **Trigger Points**
The observer is triggered when:
- User is updated (after `syncRoles()` is called)
- User model is touched/saved

### **Agent Code Generation**
Agent codes are auto-generated sequentially:
- Format: `FA` + 3-digit number
- Examples: FA001, FA002, FA003, ..., FA999
- Logic: Takes last agent code number and increments by 1

### **Default Values**
```php
[
    'commission_rate' => 5.00,      // 5%
    'target_amount' => 100000.00,   // 100,000
    'status' => 'active',
    'branch_id' => $user->branch_id ?? Branch::first()->id,
    'phone_number' => $user->phone,
]
```

---

## 🎓 Use Cases

### **Use Case 1: Onboarding New Field Agent**
**Scenario:** You hire a new field agent

**Steps:**
1. Create user account with field_agent role
2. System auto-creates field agent record
3. Agent can immediately start recording collections

**Benefits:**
- ⚡ Faster onboarding
- ✅ No manual field agent creation needed
- 🎯 Consistent data

---

### **Use Case 2: Promoting Existing User**
**Scenario:** You promote a teller to field agent

**Steps:**
1. Edit user and add field_agent role
2. System auto-creates field agent record
3. User gains field agent capabilities

**Benefits:**
- 🔄 Seamless role transition
- 📊 Maintains user history
- ✅ Automatic setup

---

### **Use Case 3: Temporary Suspension**
**Scenario:** You need to temporarily suspend a field agent

**Steps:**
1. Edit user and remove field_agent role
2. System deactivates field agent record
3. Agent cannot record new collections

**Benefits:**
- 🔒 Quick suspension
- 📝 Data preserved
- ♻️ Easy reactivation

---

### **Use Case 4: Reactivation**
**Scenario:** You reinstate a suspended field agent

**Steps:**
1. Edit user and re-add field_agent role
2. System reactivates existing field agent record
3. Agent resumes work with same agent code

**Benefits:**
- ⚡ Instant reactivation
- 🔢 Same agent code
- 📊 Historical continuity

---

## 📊 Comparison: Before vs After

### **Before (Manual Process)**
1. Create user
2. Assign field_agent role
3. Go to `/field-agent/agent/create`
4. Select the user
5. Enter agent code manually
6. Set commission rate
7. Set target amount
8. Save

**Total Steps:** 8
**Time:** ~3 minutes
**Risk:** Human error, duplicate codes

### **After (Automatic Process)**
1. Create user with field_agent role
2. Save

**Total Steps:** 2
**Time:** ~30 seconds
**Risk:** None - fully automated

---

## ⚙️ Configuration

### **Customize Default Values**
Edit `Modules/FieldAgent/Observers/UserRoleObserver.php`:

```php
protected function createFieldAgent(User $user)
{
    $fieldAgent = FieldAgent::create([
        'user_id' => $user->id,
        'agent_code' => $this->generateAgentCode(),
        'branch_id' => $user->branch_id ?? Branch::first()->id,
        'commission_rate' => 5.00,      // ← Change this
        'target_amount' => 100000.00,   // ← Change this
        'status' => 'active',
        'phone_number' => $user->phone,
    ]);
}
```

### **Customize Agent Code Format**
Edit the `generateAgentCode()` method:

```php
protected function generateAgentCode()
{
    $prefix = 'FA';  // ← Change prefix here
    // ... rest of logic
}
```

---

## 🐛 Troubleshooting

### **Issue: Field Agent Not Created**

**Possible Causes:**
1. Observer not registered
2. No branches in database
3. Role name mismatch

**Solution:**
```bash
# Clear cache
php artisan cache:clear

# Check if observer is registered
# Should see UserRoleObserver in FieldAgentServiceProvider

# Check if branches exist
php artisan tinker
>>> \Modules\Branch\Entities\Branch::count()
```

---

### **Issue: Duplicate Agent Code Error**

**Cause:** Trying to create field agent when one already exists

**Solution:**
The observer now handles this automatically. If you see this error:
1. Check if user already has a field agent
2. The observer will reactivate instead of creating new

---

### **Issue: Field Agent Not Appearing in Dropdown**

**Cause:** Field agent status is 'inactive'

**Solution:**
1. Edit the user
2. Ensure field_agent role is assigned
3. Save (this will reactivate the field agent)

---

## 📝 Activity Logging

All automatic actions are logged:

```php
// Creation
'Field Agent created automatically from user role'

// Deactivation
'Field Agent deactivated - role removed'

// Reactivation
'Field Agent reactivated - role assigned'
```

View logs in the activity log table.

---

## ✅ Benefits

1. **⚡ Faster:** Reduces onboarding time from 3 minutes to 30 seconds
2. **✅ Accurate:** Eliminates manual data entry errors
3. **🔢 Consistent:** Auto-generated codes prevent duplicates
4. **📊 Complete:** All field agents have proper records
5. **♻️ Flexible:** Easy activation/deactivation
6. **📝 Traceable:** All actions are logged

---

## 🚀 Best Practices

1. **Always use the field_agent role** - Don't manually create field agents
2. **Use role assignment for suspension** - Don't manually change status
3. **Review auto-generated codes** - Ensure they follow your numbering scheme
4. **Set user branch** - Ensures correct branch assignment
5. **Monitor activity logs** - Track all automatic actions

---

## 🎉 Summary

The auto-creation feature makes field agent management:
- **Simpler** - Just assign a role
- **Faster** - Automatic setup
- **Safer** - No manual errors
- **Smarter** - Handles activation/deactivation

**Just assign the `field_agent` role, and the system does the rest!** ✨
