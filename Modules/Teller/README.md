# Teller Module

## Overview
The Teller module provides a streamlined interface for cashiers/tellers to process deposits and withdrawals for savings accounts.

## Features
- **Account Search**: Search for savings accounts by account number using AJAX
- **Account Details Display**: View client information, account details, and current balance
- **Deposit Processing**: Process cash/check deposits with payment details
- **Withdrawal Processing**: Process withdrawals with balance validation
- **Real-time Balance Check**: Prevents overdrafts (unless allowed by product)
- **Journal Entries**: Automatic accounting entries for cash-based products
- **Activity Logging**: All transactions are logged for audit purposes

## Installation

### 1. Module Setup
The module has been created and is already enabled in `modules_statuses.json`.

### 2. Menu & Permissions
Run the following scripts to add menu items and assign permissions:

```bash
php add_teller_menu.php
php assign_teller_permissions.php
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

## Usage

### Accessing the Teller Interface
1. Navigate to `/teller` in your browser
2. The Teller menu item will appear in the main navigation (icon: cash register)

### Processing Transactions

#### Search for Account
1. Enter the savings account number in the search field
2. Click "Search" or press Enter
3. Account details will be displayed if found

#### Process Deposit
1. After searching for an account, select "Deposit" from transaction type
2. Enter the amount
3. Select payment type (Cash, Check, Bank Transfer, etc.)
4. Fill in optional payment details (receipt #, cheque #, bank details)
5. Click "Submit"

#### Process Withdrawal
1. After searching for an account, select "Withdrawal" from transaction type
2. Enter the amount (system will validate against available balance)
3. Select payment type
4. Fill in optional payment details
5. Click "Submit"

## Permissions

The module uses the following permissions:
- `teller.teller.index` - Access to teller dashboard
- `teller.teller.transactions.create` - Process deposits and withdrawals

## API Endpoints

### POST /teller/search
Search for savings account by account number

**Request:**
```json
{
  "account_number": "SAV001"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "savings_id": 1,
    "account_number": "SAV001",
    "client_name": "John Doe",
    "client_mobile": "0123456789",
    "product_name": "Regular Savings",
    "branch_name": "Main Branch",
    "balance": "1,000.00",
    "raw_balance": 1000.00,
    ...
  }
}
```

### POST /teller/transaction
Process deposit or withdrawal transaction

**Request:**
```json
{
  "savings_id": 1,
  "transaction_type": "deposit",
  "amount": 500.00,
  "date": "2025-11-26",
  "payment_type_id": 1,
  "receipt": "RCP001",
  ...
}
```

## Validation Rules

### Withdrawals
- Amount must not exceed available balance (unless overdraft is allowed)
- If overdraft is allowed, amount must not exceed overdraft limit
- Account must be in "active" status

### Deposits
- Amount must be greater than 0
- Account must be in "active" status

## Transaction Flow

1. **Teller searches account** → AJAX request validates account exists and is active
2. **Account details displayed** → Shows client info and current balance
3. **Teller enters transaction** → Form with amount, type, payment details
4. **Validation** → Server-side validation of amount, balance, permissions
5. **Transaction created** → SavingsTransaction record created
6. **Journal entries** → Automatic debit/credit entries (for cash accounting)
7. **Balance updated** → Event fired to update savings balance
8. **Success message** → Teller receives confirmation

## Security Features
- Permission-based access control
- CSRF protection on all forms
- Server-side validation
- Activity logging for audit trail
- Balance validation to prevent overdrafts

## Themes Supported
- AdminLTE (Bootstrap-based)
- DashLite (Modern UI)

## Dependencies
- Savings Module
- Core Module
- Accounting Module
- Client Module

## Troubleshooting

### Menu not appearing
- Run `php add_teller_menu.php`
- Clear cache: `php artisan cache:clear`
- Check user has `teller.teller.index` permission

### Account search not working
- Ensure savings account exists and has an account number
- Check account status is "active"
- Verify AJAX endpoint is accessible

### Transaction fails
- Check user has `teller.teller.transactions.create` permission
- Verify account is active
- For withdrawals, check sufficient balance
- Ensure payment type exists and is active

## Future Enhancements
- Print receipt functionality
- Transaction history view
- Bulk deposits
- Teller session management (opening/closing balance)
- Multi-currency support
- Biometric verification
