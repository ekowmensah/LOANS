@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.edit',1) }} {{ trans_choice('client::general.client',1) }}
@endsection
@section('styles')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<style>
/* Multi-Step Wizard Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.wizard-container {
    max-width: 900px;
    margin: 0 auto;
}

.wizard-header {
    background: var(--primary-gradient);
    color: white;
    padding: 40px;
    border-radius: 20px 20px 0 0;
    text-align: center;
}

.wizard-header h1 {
    font-size: 28px;
    font-weight: 800;
    margin: 0 0 10px 0;
}

.wizard-header p {
    opacity: 0.9;
    margin: 0;
}

/* Progress Steps */
.progress-steps {
    display: flex;
    justify-content: space-between;
    padding: 30px 40px;
    background: white;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 40px;
    right: 40px;
    height: 3px;
    background: #e9ecef;
    z-index: 0;
}

.step-item {
    position: relative;
    z-index: 1;
    text-align: center;
    flex: 1;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: 700;
    font-size: 18px;
    color: #95a5a6;
    transition: all 0.3s ease;
}

.step-item.active .step-circle {
    background: var(--primary-gradient);
    border-color: #667eea;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.step-item.completed .step-circle {
    background: #11998e;
    border-color: #11998e;
    color: white;
}

.step-item.completed .step-circle i {
    display: block;
}

.step-label {
    font-size: 13px;
    font-weight: 600;
    color: #95a5a6;
}

.step-item.active .step-label {
    color: #667eea;
}

.step-item.completed .step-label {
    color: #11998e;
}

/* Wizard Content */
.wizard-content {
    background: white;
    padding: 40px;
    min-height: 500px;
}

.wizard-step {
    display: none;
}

.wizard-step.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.step-description {
    color: #7f8c8d;
    margin-bottom: 30px;
}

.form-group-wizard {
    margin-bottom: 25px;
}

.form-group-wizard label {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    display: block;
    font-size: 14px;
}

.form-group-wizard label .required {
    color: #eb3349;
    margin-left: 3px;
}

.form-group-wizard .form-control,
.form-group-wizard .custom-select {
    height: 50px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0 15px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.form-group-wizard textarea.form-control {
    height: auto;
    min-height: 100px;
    padding: 15px;
}

.form-group-wizard .form-control:focus,
.form-group-wizard .custom-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

/* Photo Upload */
.photo-upload-area {
    border: 2px dashed #e9ecef;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.photo-upload-area:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.photo-upload-area i {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 15px;
}

.photo-preview {
    max-width: 250px;
    max-height: 250px;
    border-radius: 15px;
    margin: 20px auto;
    display: none;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

/* Summary Section */
.summary-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
}

.summary-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.summary-section h4 i {
    margin-right: 10px;
    color: #667eea;
}

.summary-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-label {
    flex: 0 0 180px;
    font-weight: 600;
    color: #7f8c8d;
    font-size: 14px;
}

.summary-value {
    flex: 1;
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
}

.summary-photo {
    text-align: center;
    padding: 20px;
}

.summary-photo img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

/* Wizard Navigation */
.wizard-navigation {
    background: white;
    padding: 30px 40px;
    border-radius: 0 0 20px 20px;
    display: flex;
    justify-content: space-between;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
}

.btn-wizard {
    height: 50px;
    padding: 0 30px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s ease;
    border: none;
    min-width: 150px;
}

.btn-prev {
    background: white;
    border: 2px solid #e9ecef;
    color: #7f8c8d;
}

.btn-prev:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.btn-next {
    background: var(--primary-gradient);
    color: white;
}

.btn-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-submit {
    background: var(--success-gradient);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(17, 153, 142, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .wizard-content {
        padding: 20px;
    }
    
    .progress-steps {
        padding: 20px;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .step-label {
        font-size: 11px;
    }
    
    .summary-row {
        flex-direction: column;
    }
    
    .summary-label {
        margin-bottom: 5px;
    }
}
</style>
@endsection
@section('content')
    <section class="content" id="app">
        <div class="wizard-container">
            <form method="post" action="{{ url('client/'.$client->id.'/update') }}" enctype="multipart/form-data" id="clientForm">
                {{csrf_field()}}
                
                <!-- Wizard Header -->
                <div class="wizard-header">
                    <h1><i class="fas fa-user-edit"></i> Edit Customer Account</h1>
                    <p>Update customer information</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step-item" :class="{active: currentStep === 1, completed: currentStep > 1}">
                        <div class="step-circle">
                            <span v-if="currentStep <= 1">1</span>
                            <i v-else class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Personal Info</div>
                    </div>
                    <div class="step-item" :class="{active: currentStep === 2, completed: currentStep > 2}">
                        <div class="step-circle">
                            <span v-if="currentStep <= 2">2</span>
                            <i v-else class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Contact Details</div>
                    </div>
                    <div class="step-item" :class="{active: currentStep === 3, completed: currentStep > 3}">
                        <div class="step-circle">
                            <span v-if="currentStep <= 3">3</span>
                            <i v-else class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Photo</div>
                    </div>
                    <div class="step-item" :class="{active: currentStep === 4, completed: currentStep > 4}">
                        <div class="step-circle">
                            <span v-if="currentStep <= 4">4</span>
                            <i v-else class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Account Setup</div>
                    </div>
                    <div class="step-item" :class="{active: currentStep === 5, completed: currentStep > 5}">
                        <div class="step-circle">5</div>
                        <div class="step-label">Review & Submit</div>
                    </div>
                </div>

                <!-- Wizard Content -->
                <div class="wizard-content">
                    <!-- Step 1: Personal Information -->
                    <div class="wizard-step" :class="{active: currentStep === 1}">
                        <h2 class="step-title">Personal Information</h2>
                        <p class="step-description">Enter the customer's basic personal details</p>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-wizard">
                                    <label>Title</label>
                                    <select class="form-control custom-select @error('title_id') is-invalid @enderror" 
                                            name="title_id" v-model="formData.title_id">
                                        <option value="">Select</option>
                                        @foreach($titles as $key)
                                            <option value="{{$key->id}}">{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group-wizard">
                                    <label>First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name" v-model="formData.first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           placeholder="Enter first name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Middle Name</label>
                                    <input type="text" name="middle_name" v-model="formData.middle_name"
                                           class="form-control" placeholder="Enter middle name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name" v-model="formData.last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           placeholder="Enter last name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Gender <span class="required">*</span></label>
                                    <select class="form-control custom-select @error('gender') is-invalid @enderror" 
                                            name="gender" v-model="formData.gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Date of Birth <span class="required">*</span></label>
                                    <input type="date" name="dob" v-model="formData.dob"
                                           class="form-control @error('dob') is-invalid @enderror" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Ghana Card (National ID)</label>
                                    <input type="text" name="ghana_card" v-model="formData.ghana_card"
                                           class="form-control @error('ghana_card') is-invalid @enderror"
                                           placeholder="GHA-XXXXXXXXX-X">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>External ID</label>
                                    <input type="text" name="external_id" v-model="formData.external_id"
                                           class="form-control @error('external_id') is-invalid @enderror"
                                           placeholder="External reference ID">
                                </div>
                            </div>
                        </div>

                        <div class="form-group-wizard">
                            <label>Marital Status</label>
                            <select class="form-control custom-select" name="marital_status" v-model="formData.marital_status">
                                <option value="">Select Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Step 2: Contact Details -->
                    <div class="wizard-step" :class="{active: currentStep === 2}">
                        <h2 class="step-title">Contact Information</h2>
                        <p class="step-description">Provide contact details and address</p>

                        <div class="row">
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
                        </div>

                        <div class="form-group-wizard">
                            <label>Address</label>
                            <textarea name="address" v-model="formData.address"
                                      class="form-control" placeholder="Enter full address" rows="3"></textarea>
                        </div>

                        <div class="form-group-wizard">
                            <label>Country <span class="required">*</span></label>
                            <select class="form-control custom-select @error('country_id') is-invalid @enderror" 
                                    name="country_id" v-model="formData.country_id" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $key)
                                    <option value="{{$key->id}}" @if($key->name == 'Ghana') selected @endif>{{$key->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Step 3: Photo Upload -->
                    <div class="wizard-step" :class="{active: currentStep === 3}">
                        <h2 class="step-title">Customer Photo</h2>
                        <p class="step-description">Upload a profile picture (optional)</p>

                        @if($client->photo)
                        <div class="photo-preview" style="display: block; margin-bottom: 15px;">
                            <img src="{{asset('storage/'.$client->photo)}}" style="max-width: 100%; border-radius: 15px;">
                            <p style="text-align: center; margin-top: 10px; font-size: 13px; color: #7f8c8d;">Current Photo</p>
                        </div>
                        @endif
                        <div class="photo-upload-area" onclick="document.getElementById('photo').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Click to upload new photo</strong></p>
                            <p style="font-size: 13px; margin-top: 5px; color: #95a5a6;">JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" style="display: none;" 
                               onchange="previewPhoto(event)">
                        <img id="photoPreview" class="photo-preview">
                    </div>

                    <!-- Step 4: Account Setup -->
                    <div class="wizard-step" :class="{active: currentStep === 4}">
                        <h2 class="step-title">Account Setup</h2>
                        <p class="step-description">Configure branch and officer assignment</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Branch <span class="required">*</span></label>
                                    <select class="form-control custom-select @error('branch_id') is-invalid @enderror" 
                                            name="branch_id" v-model="formData.branch_id" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $key)
                                            <option value="{{$key->id}}">{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-wizard">
                                    <label>Loan Officer</label>
                                    <select class="form-control custom-select" name="loan_officer_id" v-model="formData.loan_officer_id">
                                        <option value="">Select Officer</option>
                                        @foreach($users as $key)
                                            <option value="{{$key->id}}">{{$key->first_name}} {{$key->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-wizard">
                            <label>Profession</label>
                            <select class="form-control custom-select" name="profession_id" v-model="formData.profession_id">
                                <option value="">Select Profession</option>
                                @foreach($professions as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-wizard">
                            <label>Account Creation Date <span class="required">*</span></label>
                            <input type="date" name="created_date" v-model="formData.created_date"
                                   class="form-control @error('created_date') is-invalid @enderror" required>
                        </div>

                        <div class="form-group-wizard">
                            <label>Notes</label>
                            <textarea name="notes" v-model="formData.notes"
                                      class="form-control" placeholder="Additional notes or comments" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Step 5: Review & Submit -->
                    <div class="wizard-step" :class="{active: currentStep === 5}">
                        <h2 class="step-title">Review & Confirm</h2>
                        <p class="step-description">Please review all information before submitting</p>

                        <div class="summary-section">
                            <h4><i class="fas fa-user"></i> Personal Information</h4>
                            <div class="summary-row">
                                <div class="summary-label">Full Name:</div>
                                <div class="summary-value">@{{ getTitleName() }} @{{ formData.first_name }} @{{ formData.middle_name }} @{{ formData.last_name }}</div>
                            </div>
                            <div class="summary-row">
                                <div class="summary-label">Gender:</div>
                                <div class="summary-value">@{{ formData.gender ? formData.gender.charAt(0).toUpperCase() + formData.gender.slice(1) : '-' }}</div>
                            </div>
                            <div class="summary-row">
                                <div class="summary-label">Date of Birth:</div>
                                <div class="summary-value">@{{ formData.dob || '-' }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.ghana_card">
                                <div class="summary-label">Ghana Card:</div>
                                <div class="summary-value">@{{ formData.ghana_card }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.external_id">
                                <div class="summary-label">External ID:</div>
                                <div class="summary-value">@{{ formData.external_id }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.marital_status">
                                <div class="summary-label">Marital Status:</div>
                                <div class="summary-value">@{{ formData.marital_status ? formData.marital_status.charAt(0).toUpperCase() + formData.marital_status.slice(1) : '-' }}</div>
                            </div>
                        </div>

                        <div class="summary-section">
                            <h4><i class="fas fa-phone"></i> Contact Information</h4>
                            <div class="summary-row">
                                <div class="summary-label">Mobile:</div>
                                <div class="summary-value">@{{ formData.mobile || '-' }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.email">
                                <div class="summary-label">Email:</div>
                                <div class="summary-value">@{{ formData.email }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.address">
                                <div class="summary-label">Address:</div>
                                <div class="summary-value">@{{ formData.address }}</div>
                            </div>
                            <div class="summary-row">
                                <div class="summary-label">Country:</div>
                                <div class="summary-value">@{{ getCountryName() }}</div>
                            </div>
                        </div>

                        <div class="summary-section">
                            <h4><i class="fas fa-building"></i> Account Setup</h4>
                            <div class="summary-row">
                                <div class="summary-label">Branch:</div>
                                <div class="summary-value">@{{ getBranchName() }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.loan_officer_id">
                                <div class="summary-label">Loan Officer:</div>
                                <div class="summary-value">@{{ getOfficerName() }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.profession_id">
                                <div class="summary-label">Profession:</div>
                                <div class="summary-value">@{{ getProfessionName() }}</div>
                            </div>
                            <div class="summary-row">
                                <div class="summary-label">Creation Date:</div>
                                <div class="summary-value">@{{ formData.created_date }}</div>
                            </div>
                            <div class="summary-row" v-if="formData.notes">
                                <div class="summary-label">Notes:</div>
                                <div class="summary-value">@{{ formData.notes }}</div>
                            </div>
                        </div>

                        <div class="summary-section" v-if="photoUploaded">
                            <h4><i class="fas fa-camera"></i> Customer Photo</h4>
                            <div class="summary-photo">
                                <img :src="photoPreviewUrl" alt="Customer Photo">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Navigation -->
                <div class="wizard-navigation">
                    <button type="button" class="btn btn-wizard btn-prev" @click="prevStep" v-show="currentStep > 1">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-wizard btn-next" @click="nextStep" v-show="currentStep < 5">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-wizard btn-submit" v-show="currentStep === 5">
                        <i class="fas fa-save"></i> Update Account
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                currentStep: 1,
                photoUploaded: false,
                photoPreviewUrl: '',
                formData: {
                    title_id: '{{$client->title_id ?? ''}}',
                    first_name: '{{$client->first_name ?? ''}}',
                    middle_name: '{{$client->middle_name ?? ''}}',
                    last_name: '{{$client->last_name ?? ''}}',
                    gender: '{{$client->gender ?? ''}}',
                    dob: '{{$client->dob ?? ''}}',
                    ghana_card: '{{$client->ghana_card ?? ''}}',
                    external_id: '{{$client->external_id ?? ''}}',
                    marital_status: '{{$client->marital_status ?? ''}}',
                    mobile: '{{$client->mobile ?? ''}}',
                    email: '{{$client->email ?? ''}}',
                    address: '{{$client->address ?? ''}}',
                    country_id: '{{$client->country_id ?? ''}}',
                    branch_id: '{{$client->branch_id ?? ''}}',
                    loan_officer_id: '{{$client->loan_officer_id ?? ''}}',
                    profession_id: '{{$client->profession_id ?? ''}}',
                    created_date: '{{$client->created_date ?? date('Y-m-d')}}',
                    notes: '{{$client->notes ?? ''}}'
                },
                originalData: {
                    mobile: '{{$client->mobile ?? ''}}',
                    email: '{{$client->email ?? ''}}',
                    ghana_card: '{{$client->ghana_card ?? ''}}',
                    external_id: '{{$client->external_id ?? ''}}'
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

                    // If value hasn't changed from original, mark as available without checking
                    if (value === this.originalData[field]) {
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
                            client_id: '{{$client->id}}'
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