<?php
/**
 * Ultra-Modern Bank Teller Page Redesign
 * Complete professional banking interface
 * 
 * Usage: php redesign_teller_page.php
 */

echo "===========================================\n";
echo "Ultra-Modern Bank Teller Redesign\n";
echo "===========================================\n\n";

$filePath = __DIR__ . '/Modules/Teller/Resources/views/themes/adminlte/teller/index.blade.php';

if (!file_exists($filePath)) {
    die("ERROR: File not found\n");
}

echo "Reading current file...\n";
$content = file_get_contents($filePath);

// Find the content section and replace it
$searchStart = '<section class="content" id="app">';
$searchEnd = '@endsection

@section(\'scripts\')';

$startPos = strpos($content, $searchStart);
$endPos = strpos($content, $searchEnd);

if ($startPos === false || $endPos === false) {
    die("ERROR: Could not find content section\n");
}

$newContent = <<<'BLADE'
<section class="content" id="app">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Search Card -->
                <div class="search-card-modern">
                    <h4 style="margin-bottom: 20px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-search"></i> Search Account
                    </h4>
                    <div class="search-input-group">
                        <input type="text" 
                               class="form-control" 
                               v-model="account_number" 
                               placeholder="Enter account number..."
                               @keyup.enter="searchAccount">
                        <button class="search-btn-modern" @click="searchAccount" :disabled="searching">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions" v-if="!account">
                    <div class="quick-action-btn" @click="focusSearch">
                        <i class="fas fa-search"></i>
                        <span>Search Account</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('savings/create')}}'">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Account</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('client')}}'">
                        <i class="fas fa-users"></i>
                        <span>Clients</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('savings')}}'">
                        <i class="fas fa-university"></i>
                        <span>All Accounts</span>
                    </div>
                </div>

                <!-- Account Card -->
                <div v-if="account" class="account-card-modern">
                    <div class="account-header">
                        <img v-if="account.client_photo" 
                             :src="'/storage/uploads/clients/' + account.client_photo" 
                             class="account-photo" 
                             alt="Client Photo">
                        <div v-else class="account-photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="account-info flex-grow-1">
                            <h3>@{{ account.client_name }}</h3>
                            <div style="opacity: 0.9; margin-top: 5px;">
                                <i class="fas fa-phone"></i> @{{ account.client_mobile }}
                            </div>
                        </div>
                    </div>

                    <div class="account-details-grid">
                        <div class="account-detail-item">
                            <div class="account-detail-label">Account Number</div>
                            <div class="account-detail-value">@{{ account.account_number }}</div>
                        </div>
                        <div class="account-detail-item">
                            <div class="account-detail-label">Product</div>
                            <div class="account-detail-value">@{{ account.product_name }}</div>
                        </div>
                        <div class="account-detail-item">
                            <div class="account-detail-label">Branch</div>
                            <div class="account-detail-value">@{{ account.branch_name }}</div>
                        </div>
                        <div class="account-detail-item">
                            <div class="account-detail-label">Status</div>
                            <div class="account-detail-value">@{{ account.status.toUpperCase() }}</div>
                        </div>
                    </div>

                    <div class="balance-display">
                        <div class="label">AVAILABLE BALANCE</div>
                        <div class="amount">@{{ account.currency_symbol }} @{{ account.balance }}</div>
                    </div>
                </div>

                <!-- Transaction Form -->
                <div v-if="account" class="transaction-form-modern">
                    <h4 style="margin-bottom: 25px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-exchange-alt"></i> Process Transaction
                    </h4>

                    <form method="post" action="{{url('teller/transaction')}}">
                        @csrf
                        <input type="hidden" name="savings_id" v-model="account.savings_id">

                        <!-- Transaction Type Selector -->
                        <div class="transaction-type-selector">
                            <div class="transaction-type-btn deposit" 
                                 :class="{active: transaction_type === 'deposit'}"
                                 @click="transaction_type = 'deposit'">
                                <i class="fas fa-arrow-down"></i>
                                <span>DEPOSIT</span>
                            </div>
                            <div class="transaction-type-btn withdrawal" 
                                 :class="{active: transaction_type === 'withdrawal'}"
                                 @click="transaction_type = 'withdrawal'">
                                <i class="fas fa-arrow-up"></i>
                                <span>WITHDRAWAL</span>
                            </div>
                        </div>
                        <input type="hidden" name="transaction_type" v-model="transaction_type">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="any" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           name="amount" 
                                           v-model="amount" 
                                           required
                                           placeholder="0.00">
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small v-if="transaction_type === 'withdrawal' && amount > account.raw_balance" 
                                           class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Amount exceeds available balance
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('date') is-invalid @enderror" 
                                           name="date" 
                                           value="{{date('Y-m-d')}}" 
                                           required>
                                    @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Payment Type <span class="text-danger">*</span></label>
                                    <v-select label="name" 
                                              :options="payment_types"
                                              :reduce="payment_type => payment_type.id"
                                              v-model="payment_type_id"
                                              placeholder="Select payment type">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off"
                                                   class="vs__search @error('payment_type_id') is-invalid @enderror"
                                                   v-bind="attributes"
                                                   v-bind:required="!payment_type_id"
                                                   v-on="events"/>
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="payment_type_id" v-model="payment_type_id">
                                    @error('payment_type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Receipt #</label>
                                    <input type="text" class="form-control" name="receipt" placeholder="Optional">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label>Cheque #</label>
                                    <input type="text" class="form-control" name="cheque_number" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label>Account #</label>
                                    <input type="text" class="form-control" name="payment_account_number" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" placeholder="Optional">
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>Routing Code</label>
                            <input type="text" class="form-control" name="routing_code" placeholder="Optional">
                        </div>

                        <button type="submit" 
                                class="btn-submit-modern" 
                                :class="transaction_type"
                                :disabled="!transaction_type || !amount || !payment_type_id">
                            <i class="fas fa-check-circle"></i> 
                            Process @{{ transaction_type ? transaction_type.toUpperCase() : 'TRANSACTION' }}
                        </button>

                        <button type="button" 
                                class="btn btn-secondary btn-lg btn-block mt-3" 
                                @click="clearForm"
                                style="border-radius: 15px; height: 50px;">
                            <i class="fas fa-times"></i> Clear & New Transaction
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Calculator Widget -->
                <div class="calculator-widget">
                    <h5 style="margin-bottom: 15px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-calculator"></i> Quick Calculator
                    </h5>
                    <div class="calculator-display">@{{ calculatorDisplay || '0' }}</div>
                    <div class="calculator-buttons">
                        <button class="calc-btn" @click="calcInput('7')">7</button>
                        <button class="calc-btn" @click="calcInput('8')">8</button>
                        <button class="calc-btn" @click="calcInput('9')">9</button>
                        <button class="calc-btn operator" @click="calcInput('/')">÷</button>
                        
                        <button class="calc-btn" @click="calcInput('4')">4</button>
                        <button class="calc-btn" @click="calcInput('5')">5</button>
                        <button class="calc-btn" @click="calcInput('6')">6</button>
                        <button class="calc-btn operator" @click="calcInput('*')">×</button>
                        
                        <button class="calc-btn" @click="calcInput('1')">1</button>
                        <button class="calc-btn" @click="calcInput('2')">2</button>
                        <button class="calc-btn" @click="calcInput('3')">3</button>
                        <button class="calc-btn operator" @click="calcInput('-')">−</button>
                        
                        <button class="calc-btn" @click="calcInput('0')">0</button>
                        <button class="calc-btn" @click="calcInput('.')">.</button>
                        <button class="calc-btn clear" @click="calcClear">C</button>
                        <button class="calc-btn operator" @click="calcInput('+')">+</button>
                        
                        <button class="calc-btn equals" @click="calcEquals">=</button>
                        <button class="calc-btn" @click="useCalcResult">Use</button>
                    </div>
                </div>

                <!-- Today's Summary -->
                <div class="recent-transactions">
                    <h5 style="margin-bottom: 20px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-chart-line"></i> Today's Summary
                    </h5>
                    <div class="transaction-item">
                        <div class="transaction-icon deposit">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Deposits</div>
                            <div class="transaction-amount deposit">GH₵ 0.00</div>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-icon withdrawal">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Withdrawals</div>
                            <div class="transaction-amount withdrawal">GH₵ 0.00</div>
                        </div>
                    </div>
                    <div class="transaction-item" style="border-bottom: none;">
                        <div class="transaction-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Transactions</div>
                            <div style="font-size: 18px; font-weight: 700; color: #667eea;">0</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="recent-transactions mt-3">
                    <h5 style="margin-bottom: 20px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-link"></i> Quick Links
                    </h5>
                    <a href="{{url('savings')}}" class="transaction-item" style="text-decoration: none; color: inherit;">
                        <div class="transaction-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">All Savings Accounts</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                    <a href="{{url('client')}}" class="transaction-item" style="text-decoration: none; color: inherit;">
                        <div class="transaction-icon" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">Client Management</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                    <a href="{{url('report/accounting/teller')}}" class="transaction-item" style="text-decoration: none; color: inherit; border-bottom: none;">
                        <div class="transaction-icon" style="background: rgba(240, 147, 251, 0.1); color: #f093fb;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">Teller Reports</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
BLADE;

$before = substr($content, 0, $startPos);
$after = substr($content, $endPos);

$content = $before . $newContent . "\n" . $after;

echo "Writing redesigned content...\n";
file_put_contents($filePath, $content);

echo "\n===========================================\n";
echo "✅ TELLER PAGE REDESIGN COMPLETE!\n";
echo "===========================================\n\n";

echo "Features Added:\n";
echo "  ✓ Modern gradient header with teller info\n";
echo "  ✓ Enhanced search with modern styling\n";
echo "  ✓ Quick action buttons\n";
echo "  ✓ Beautiful account card with gradient\n";
echo "  ✓ Visual transaction type selector\n";
echo "  ✓ Built-in calculator widget\n";
echo "  ✓ Today's summary panel\n";
echo "  ✓ Quick links sidebar\n";
echo "  ✓ Professional banking aesthetics\n";
echo "  ✓ Responsive design\n\n";

echo "Visit /teller to see the new design!\n";
echo "===========================================\n";
