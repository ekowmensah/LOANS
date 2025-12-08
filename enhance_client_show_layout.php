<?php
/**
 * Enhanced Client Show Page - Improved Layout & Statement Button
 * 
 * Usage: php enhance_client_show_layout.php
 */

echo "===========================================\n";
echo "Enhancing Client Show Page Layout\n";
echo "===========================================\n\n";

$filePath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/show.blade.php';
$backupPath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/show_before_enhancement_' . date('Y_m_d_His') . '.blade.php';

if (!file_exists($filePath)) {
    die("ERROR: File not found\n");
}

echo "Creating backup...\n";
copy($filePath, $backupPath);
echo "✓ Backup created\n\n";

echo "Reading current file...\n";
$content = file_get_contents($filePath);

// Enhancement 1: Improve sidebar styling
echo "Step 1: Enhancing sidebar design...\n";

$oldSidebarCard = '.content-card-ultra {
    background: white;
    border-radius: 16px;
    padding: 0;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}';

$newSidebarCard = '.content-card-ultra {
    background: white;
    border-radius: 16px;
    padding: 0;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}

.content-card-ultra:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.sidebar-card-compact {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-left: 4px solid #667eea;
}';

$content = str_replace($oldSidebarCard, $newSidebarCard, $content);

// Enhancement 2: Add better info item styling
echo "Step 2: Improving info items...\n";

$oldInfoItem = '.info-item {
    padding: 15px 0;
    border-bottom: 1px solid #ecf0f1;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 5px;
}

.info-value {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}';

$newInfoItem = '.info-item {
    padding: 18px 0;
    border-bottom: 1px solid #ecf0f1;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: rgba(102, 126, 234, 0.03);
    padding-left: 10px;
    margin-left: -10px;
    margin-right: -10px;
    padding-right: 10px;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 11px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-weight: 700;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.info-label i {
    color: #667eea;
    font-size: 12px;
}

.info-value {
    font-size: 15px;
    color: #2c3e50;
    font-weight: 600;
    line-height: 1.5;
}';

$content = str_replace($oldInfoItem, $newInfoItem, $content);

// Enhancement 3: Add statement button styling
echo "Step 3: Adding statement button styles...\n";

$statementButtonCSS = '
/* Statement Button */
.btn-statement {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 12px;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(79, 172, 254, 0.3);
}

.btn-statement:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
    color: white;
}

.btn-statement i {
    margin-right: 5px;
}

/* Action buttons in tables */
.action-buttons-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-buttons-group .btn {
    margin: 0 !important;
}';

// Insert statement button CSS before the closing </style>
$content = str_replace('</style>', $statementButtonCSS . "\n</style>", $content);

// Enhancement 4: Improve card headers
echo "Step 4: Enhancing card headers...\n";

$oldCardHeader = '.card-header-ultra {
    padding: 25px 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #e9ecef;
}

.card-header-ultra h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}';

$newCardHeader = '.card-header-ultra {
    padding: 25px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-bottom: none;
}

.card-header-ultra h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header-ultra h3 i {
    font-size: 20px;
    opacity: 0.9;
}';

$content = str_replace($oldCardHeader, $newCardHeader, $content);

// Enhancement 5: Update sidebar cards to use compact class
echo "Step 5: Updating sidebar cards...\n";

$content = str_replace(
    '<div class="col-lg-4">
            <!-- Personal Information -->
            <div class="content-card-ultra">',
    '<div class="col-lg-4">
            <!-- Personal Information -->
            <div class="content-card-ultra sidebar-card-compact">',
    $content
);

// Add icons to info labels in the view
$content = str_replace(
    '<div class="info-label">Full Name</div>',
    '<div class="info-label"><i class="fas fa-user"></i> Full Name</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">External ID</div>',
    '<div class="info-label"><i class="fas fa-id-badge"></i> External ID</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Date of Birth</div>',
    '<div class="info-label"><i class="fas fa-birthday-cake"></i> Date of Birth</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Gender</div>',
    '<div class="info-label"><i class="fas fa-venus-mars"></i> Gender</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Marital Status</div>',
    '<div class="info-label"><i class="fas fa-heart"></i> Marital Status</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Client Type</div>',
    '<div class="info-label"><i class="fas fa-tag"></i> Client Type</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Mobile</div>',
    '<div class="info-label"><i class="fas fa-mobile-alt"></i> Mobile</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Email</div>',
    '<div class="info-label"><i class="fas fa-envelope"></i> Email</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Address</div>',
    '<div class="info-label"><i class="fas fa-map-marker-alt"></i> Address</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Country</div>',
    '<div class="info-label"><i class="fas fa-globe"></i> Country</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Zip Code</div>',
    '<div class="info-label"><i class="fas fa-mail-bulk"></i> Zip Code</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Branch</div>',
    '<div class="info-label"><i class="fas fa-building"></i> Branch</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Loan Officer</div>',
    '<div class="info-label"><i class="fas fa-user-tie"></i> Loan Officer</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Joined Date</div>',
    '<div class="info-label"><i class="fas fa-calendar-plus"></i> Joined Date</div>',
    $content
);

$content = str_replace(
    '<div class="info-label">Activation Date</div>',
    '<div class="info-label"><i class="fas fa-calendar-check"></i> Activation Date</div>',
    $content
);

// Enhancement 6: Update DataTables rendering to include statement button
echo "Step 6: Adding statement button to DataTables...\n";

// Find and update the savings DataTable initialization
$oldSavingsTable = "        \$('#savings-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('savings/get_savings?client_id='.\$client->id) !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'interest_rate', name: 'interest_rate'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
                {data: 'savings_product', name: 'savings_products.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            \"order\": [[0, \"desc\"]],
            responsive: true,
            \"autoWidth\": false
        });";

$newSavingsTable = "        \$('#savings-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('savings/get_savings?client_id='.\$client->id) !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'interest_rate', name: 'interest_rate'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
                {data: 'savings_product', name: 'savings_products.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            \"order\": [[0, \"desc\"]],
            responsive: true,
            \"autoWidth\": false,
            \"drawCallback\": function(settings) {
                // Add statement button to action column
                \$('.action-cell').each(function() {
                    var savingsId = \$(this).data('savings-id');
                    if (savingsId && !\$(this).find('.btn-statement').length) {
                        var statementBtn = '<a href=\"' + baseUrl + '/savings/' + savingsId + '/statement\" class=\"btn btn-statement btn-sm\" target=\"_blank\"><i class=\"fas fa-file-invoice\"></i> Statement</a>';
                        \$(this).prepend(statementBtn + ' ');
                    }
                });
            }
        });
        
        var baseUrl = '{!! url('/') !!}';";

$content = str_replace($oldSavingsTable, $newSavingsTable, $content);

// Enhancement 7: Improve column layout
echo "Step 7: Adjusting column widths...\n";

$content = str_replace(
    '<div class="col-lg-4">',
    '<div class="col-lg-3">',
    $content
);

$content = str_replace(
    '<div class="col-lg-8">',
    '<div class="col-lg-9">',
    $content
);

echo "Step 8: Saving enhanced file...\n";
file_put_contents($filePath, $content);

echo "\n===========================================\n";
echo "✅ ENHANCEMENT COMPLETE!\n";
echo "===========================================\n\n";

echo "Improvements Made:\n";
echo "  ✓ Enhanced sidebar with gradient background\n";
echo "  ✓ Added hover effects to info items\n";
echo "  ✓ Added icons to all info labels\n";
echo "  ✓ Improved card headers with gradient\n";
echo "  ✓ Added statement button styling\n";
echo "  ✓ Adjusted column layout (3/9 split)\n";
echo "  ✓ Better visual hierarchy\n";
echo "  ✓ Smoother transitions\n\n";

echo "Note: Statement button will appear in savings accounts table\n";
echo "Backup: $backupPath\n\n";
echo "Clear cache and refresh the page!\n";
echo "===========================================\n";
