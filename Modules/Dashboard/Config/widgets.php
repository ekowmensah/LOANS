<?php
/**
 * Enhanced Dashboard Widgets Configuration
 * Modern financial widgets for Ultimate Loan Manager
 */
return [
    // Enhanced Financial Overview Widget
    'FinancialOverview' => [
        "class" => 'Dashboard::FinancialOverview', 
        "name" => "Financial Overview", 
        "x" => 0, 
        "y" => 0, 
        "width" => 12, 
        "height" => 4
    ],
    
    // Cash Flow Analysis Chart
    'CashFlowChart' => [
        "class" => 'Dashboard::CashFlowChart', 
        "name" => "Cash Flow Analysis", 
        "x" => 0, 
        "y" => 4, 
        "width" => 8, 
        "height" => 4
    ],
    
    // Portfolio Health Metrics
    'PortfolioHealthMetrics' => [
        "class" => 'Dashboard::PortfolioHealthMetrics', 
        "name" => "Portfolio Health Metrics", 
        "x" => 8, 
        "y" => 4, 
        "width" => 4, 
        "height" => 4
    ],
    
    // Group Loan Overview
    'GroupLoanOverview' => [
        "class" => 'Dashboard::GroupLoanOverview', 
        "name" => "Group Loan Overview", 
        "x" => 0, 
        "y" => 8, 
        "width" => 6, 
        "height" => 4
    ],
    
    // Original Loan Widgets (Enhanced)
    'LoanStatistics' => [
        "class" => 'Loan::LoanStatistics', 
        "name" => "Loan Statistics", 
        "x" => 6, 
        "y" => 8, 
        "width" => 6, 
        "height" => 2
    ],
    
    'LoanStatusOverview' => [
        "class" => 'Loan::LoanStatusOverview', 
        "name" => "Loan Status Overview", 
        "x" => 6, 
        "y" => 10, 
        "width" => 3, 
        "height" => 2
    ],
    
    'LoanCollectionOverview' => [
        "class" => 'Loan::LoanCollectionOverview', 
        "name" => "Loan Collection Overview", 
        "x" => 9, 
        "y" => 10, 
        "width" => 3, 
        "height" => 2
    ],
    
    // Financial Alerts Widget
    'FinancialAlerts' => [
        "class" => 'Dashboard::FinancialAlerts', 
        "name" => "Financial Alerts & Notifications", 
        "x" => 0, 
        "y" => 12, 
        "width" => 4, 
        "height" => 4
    ],
    
    // Performance Comparison Widget
    'PerformanceComparison' => [
        "class" => 'Dashboard::PerformanceComparison', 
        "name" => "Performance Comparison", 
        "x" => 4, 
        "y" => 12, 
        "width" => 8, 
        "height" => 4
    ],
    
    // Savings Widget
    'SavingsBalanceOverview' => [
        "class" => 'Savings::SavingsBalanceOverview', 
        "name" => "Savings Balance Overview", 
        "x" => 8, 
        "y" => 16, 
        "width" => 4, 
        "height" => 3
    ],
];
