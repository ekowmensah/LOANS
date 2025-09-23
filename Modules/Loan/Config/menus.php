<?php

return [
    ['name' => 'Loans', 'is_parent' => 1, 'module' => 'Loan', 'slug' => 'loans', 'parent_slug' => '', 'url' => 'loan', 'icon' => 'fas fa-money-bill', 'order' => 18, 'permissions' => 'loan'],
    ['name' => 'View Loans', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'view_loans', 'parent_slug' => 'loans', 'url' => 'loan', 'icon' => 'far fa-circle', 'order' => 19, 'permissions' => 'loan.loans.index'],
    ['name' => 'View Applications', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'view_applications', 'parent_slug' => 'loans', 'url' => 'loan/application', 'icon' => 'far fa-circle', 'order' => 20, 'permissions' => 'loan.loans.index'],
    ['name' => 'Loan Repayments', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'view_loan_repayments', 'parent_slug' => 'loans', 'url' => 'loan/repayment', 'icon' => 'far fa-circle', 'order' => 21, 'permissions' => 'loan.loans.transactions.index'],
    ['name' => 'Create Loan', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'create_loan', 'parent_slug' => 'loans', 'url' => 'loan/create', 'icon' => 'far fa-circle', 'order' => 21, 'permissions' => 'loan.loans.create'],
    ['name' => 'Manage Products', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'manage_products', 'parent_slug' => 'loans', 'url' => 'loan/product', 'icon' => 'far fa-circle', 'order' => 22, 'permissions' => 'loan.loans.products.index'],
    ['name' => 'Manage Charges', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'manage_charges', 'parent_slug' => 'loans', 'url' => 'loan/charge', 'icon' => 'far fa-circle', 'order' => 23, 'permissions' => 'loan.loans.charges.index'],
    ['name' => 'Loan Calculator', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'loan_calculator', 'parent_slug' => 'loans', 'url' => 'loan/calculator', 'icon' => 'far fa-circle', 'order' => 24, 'permissions' => 'loan.loans.index'],
    ['name' => 'Loan Provisioning', 'is_parent' => 0, 'module' => 'Loan', 'slug' => 'loan_provisioning', 'parent_slug' => 'loans', 'url' => 'loan/loan_provisioning', 'icon' => 'far fa-circle', 'order' => 25, 'permissions' => 'loan.loans.loan_provisioning.index'],
];