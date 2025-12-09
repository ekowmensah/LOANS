<?php
/**
 * Add Real-time Duplicate Checking to Client Create Page
 */

echo "===========================================\n";
echo "Adding Real-time Duplicate Checks\n";
echo "===========================================\n\n";

$filePath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/create.blade.php';

if (!file_exists($filePath)) {
    die("ERROR: File not found\n");
}

$content = file_get_contents($filePath);

// Find the scripts section and add duplicate checking logic
$scriptsSection = <<<'JS'
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                currentStep: 1,
                photoUploaded: false,
                photoPreviewUrl: '',
                formData: {
                    title_id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    gender: '',
                    dob: '',
                    ghana_card: '',
                    external_id: '',
                    marital_status: '',
                    mobile: '',
                    email: '',
                    address: '',
                    country_id: '',
                    branch_id: '',
                    loan_officer_id: '',
                    profession_id: '',
                    created_date: '{{date('Y-m-d')}}',
                    notes: ''
                },
                titles: @json($titles),
                countries: @json($countries),
                branches: @json($branches),
                users: @json($users),
                professions: @json($professions),
                duplicateChecks: {
                    mobile: { checking: false, available: true, message: '' },
                    email: { checking: false, available: true, message: '' },
                    ghana_card: { checking: false, available: true, message: '' },
                    external_id: { checking: false, available: true, message: '' }
                },
                checkTimeout: null
            },
            watch: {
                'formData.mobile': function(newVal) {
                    this.checkDuplicate('mobile', newVal);
                },
                'formData.email': function(newVal) {
                    if (newVal) this.checkDuplicate('email', newVal);
                },
                'formData.ghana_card': function(newVal) {
                    if (newVal) this.checkDuplicate('ghana_card', newVal);
                },
                'formData.external_id': function(newVal) {
                    if (newVal) this.checkDuplicate('external_id', newVal);
                }
            },
            methods: {
                checkDuplicate(field, value) {
                    if (!value) {
                        this.duplicateChecks[field] = { checking: false, available: true, message: '' };
                        return;
                    }

                    // Clear previous timeout
                    if (this.checkTimeout) {
                        clearTimeout(this.checkTimeout);
                    }

                    // Set checking state
                    this.duplicateChecks[field].checking = true;

                    // Debounce the check
                    this.checkTimeout = setTimeout(() => {
                        axios.post('{{url("client/check-duplicate")}}', {
                            field: field,
                            value: value,
                            client_id: null
                        })
                        .then(response => {
                            this.duplicateChecks[field] = {
                                checking: false,
                                available: response.data.available,
                                message: response.data.message
                            };
                        })
                        .catch(error => {
                            this.duplicateChecks[field].checking = false;
                            console.error('Duplicate check error:', error);
                        });
                    }, 500);
                },
                nextStep() {
                    if (this.validateStep(this.currentStep)) {
                        this.currentStep++;
                    }
                },
                prevStep() {
                    this.currentStep--;
                },
                validateStep(step) {
                    if (step === 1) {
                        if (!this.formData.first_name || !this.formData.last_name || !this.formData.gender || !this.formData.dob) {
                            alert('Please fill in all required fields');
                            return false;
                        }
                        // Check duplicates for ghana_card and external_id if filled
                        if (this.formData.ghana_card && !this.duplicateChecks.ghana_card.available) {
                            alert('Ghana Card already exists');
                            return false;
                        }
                        if (this.formData.external_id && !this.duplicateChecks.external_id.available) {
                            alert('External ID already exists');
                            return false;
                        }
                    }
                    if (step === 2) {
                        if (!this.formData.mobile || !this.formData.country_id) {
                            alert('Please fill in all required fields');
                            return false;
                        }
                        // Check duplicates
                        if (!this.duplicateChecks.mobile.available) {
                            alert('Mobile number already exists');
                            return false;
                        }
                        if (this.formData.email && !this.duplicateChecks.email.available) {
                            alert('Email already exists');
                            return false;
                        }
                    }
                    if (step === 4) {
                        if (!this.formData.branch_id || !this.formData.created_date) {
                            alert('Please fill in all required fields');
                            return false;
                        }
                    }
                    return true;
                },
                getTitleName() {
                    const title = this.titles.find(t => t.id == this.formData.title_id);
                    return title ? title.name : '';
                },
                getCountryName() {
                    const country = this.countries.find(c => c.id == this.formData.country_id);
                    return country ? country.name : '-';
                },
                getBranchName() {
                    const branch = this.branches.find(b => b.id == this.formData.branch_id);
                    return branch ? branch.name : '-';
                },
                getOfficerName() {
                    const officer = this.users.find(u => u.id == this.formData.loan_officer_id);
                    return officer ? `${officer.first_name} ${officer.last_name}` : '-';
                },
                getProfessionName() {
                    const profession = this.professions.find(p => p.id == this.formData.profession_id);
                    return profession ? profession.name : '-';
                }
            }
        });

        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    app.photoUploaded = true;
                    app.photoPreviewUrl = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
JS;

// Replace the scripts section
$content = preg_replace('/@section\(\'scripts\'\).*?@endsection/s', $scriptsSection, $content);

// Add duplicate check indicators in the form fields
// For mobile field
$mobileField = <<<'HTML'
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Mobile Number <span class="required">*</span></label>
                                    <input type="text" name="mobile" v-model="formData.mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           :class="{'is-invalid': !duplicateChecks.mobile.available, 'is-valid': formData.mobile && duplicateChecks.mobile.available && !duplicateChecks.mobile.checking}"
                                           placeholder="0XXXXXXXXX" required>
                                    <span v-if="duplicateChecks.mobile.checking" class="text-info" style="font-size: 13px;">
                                        <i class="fas fa-spinner fa-spin"></i> Checking...
                                    </span>
                                    <span v-if="!duplicateChecks.mobile.available" class="text-danger" style="font-size: 13px;">
                                        <i class="fas fa-times-circle"></i> @{{ duplicateChecks.mobile.message }}
                                    </span>
                                    <span v-if="formData.mobile && duplicateChecks.mobile.available && !duplicateChecks.mobile.checking" class="text-success" style="font-size: 13px;">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                    @error('mobile')
                                    <span class="text-danger" style="font-size: 13px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
HTML;

$content = preg_replace(
    '/<div class="col-md-6">\s*<div class="form-group-wizard">\s*<label>Mobile Number.*?<\/div>\s*<\/div>/s',
    $mobileField,
    $content,
    1
);

// For email field
$emailField = <<<'HTML'
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Email Address</label>
                                    <input type="email" name="email" v-model="formData.email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           :class="{'is-invalid': formData.email && !duplicateChecks.email.available, 'is-valid': formData.email && duplicateChecks.email.available && !duplicateChecks.email.checking}"
                                           placeholder="customer@example.com">
                                    <span v-if="duplicateChecks.email.checking" class="text-info" style="font-size: 13px;">
                                        <i class="fas fa-spinner fa-spin"></i> Checking...
                                    </span>
                                    <span v-if="formData.email && !duplicateChecks.email.available" class="text-danger" style="font-size: 13px;">
                                        <i class="fas fa-times-circle"></i> @{{ duplicateChecks.email.message }}
                                    </span>
                                    <span v-if="formData.email && duplicateChecks.email.available && !duplicateChecks.email.checking" class="text-success" style="font-size: 13px;">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                    @error('email')
                                    <span class="text-danger" style="font-size: 13px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
HTML;

$content = preg_replace(
    '/<div class="col-md-6">\s*<div class="form-group-wizard">\s*<label>Email Address.*?<\/div>\s*<\/div>/s',
    $emailField,
    $content,
    1
);

file_put_contents($filePath, $content);

echo "✅ Real-time duplicate checking added!\n\n";
echo "Features:\n";
echo "  ✓ Live checking as user types\n";
echo "  ✓ 500ms debounce delay\n";
echo "  ✓ Visual indicators (spinner, checkmark, X)\n";
echo "  ✓ Prevents form submission if duplicates found\n";
echo "  ✓ Checks: Mobile, Email, Ghana Card, External ID\n\n";
echo "===========================================\n";
