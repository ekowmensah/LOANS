<?php
/**
 * Ultra-Modern Client Creation Page Redesign
 * Professional Banking Customer Account Creation
 */

echo "===========================================\n";
echo "Client Creation Page Redesign\n";
echo "===========================================\n\n";

$filePath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/create.blade.php';

if (!file_exists($filePath)) {
    die("ERROR: File not found\n");
}

echo "Reading current file...\n";
$content = file_get_contents($filePath);

// Read the entire file to get all form fields
$backupPath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/create_backup.blade.php';
$originalContent = file_get_contents($backupPath);

// Create the new modern design
$newContent = <<<'BLADE'
@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.add',1) }} {{ trans_choice('client::general.client',1) }}
@endsection
@section('styles')
<style>
/* Modern Client Creation Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.client-create-header {
    background: var(--primary-gradient);
    color: white;
    padding: 40px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.client-create-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.client-create-header h1 {
    font-size: 32px;
    font-weight: 800;
    margin: 0;
    position: relative;
    z-index: 1;
}

.client-create-header p {
    font-size: 16px;
    opacity: 0.9;
    margin-top: 10px;
    position: relative;
    z-index: 1;
}

.form-section-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.section-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-right: 15px;
}

.section-title {
    flex: 1;
}

.section-title h3 {
    font-size: 20px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.section-title p {
    font-size: 13px;
    color: #7f8c8d;
    margin: 5px 0 0 0;
}

.form-group-modern {
    margin-bottom: 25px;
}

.form-group-modern label {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    display: block;
    font-size: 14px;
}

.form-group-modern label .required {
    color: #eb3349;
    margin-left: 3px;
}

.form-group-modern .form-control,
.form-group-modern .custom-select {
    height: 50px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0 15px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.form-group-modern textarea.form-control {
    height: auto;
    min-height: 100px;
    padding: 15px;
}

.form-group-modern .form-control:focus,
.form-group-modern .custom-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.photo-upload-area {
    border: 2px dashed #e9ecef;
    border-radius: 15px;
    padding: 30px;
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

.photo-upload-area p {
    color: #7f8c8d;
    margin: 0;
    font-size: 14px;
}

.photo-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 15px;
    margin: 15px auto;
    display: none;
}

.btn-submit-modern {
    width: 100%;
    height: 60px;
    border-radius: 15px;
    background: var(--success-gradient);
    border: none;
    color: white;
    font-size: 18px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
}

.btn-submit-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(17, 153, 142, 0.4);
    color: white;
}

.btn-cancel-modern {
    width: 100%;
    height: 60px;
    border-radius: 15px;
    background: white;
    border: 2px solid #e9ecef;
    color: #7f8c8d;
    font-size: 16px;
    font-weight: 700;
    transition: all 0.3s ease;
}

.btn-cancel-modern:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.info-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid #2196f3;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.info-box i {
    color: #2196f3;
    font-size: 24px;
    margin-right: 15px;
}

.info-box p {
    margin: 0;
    color: #1976d2;
    font-weight: 600;
}

@media (max-width: 768px) {
    .client-create-header {
        padding: 30px 20px;
    }
    
    .client-create-header h1 {
        font-size: 24px;
    }
    
    .form-section-card {
        padding: 20px;
    }
}
</style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="client-create-header">
                <h1><i class="fas fa-user-plus"></i> New Customer Account</h1>
                <p>Create a new customer profile with complete information</p>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <form method="post" action="{{ url('client/store') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="section-title">
                                <h3>Personal Information</h3>
                                <p>Basic customer details and identification</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.title',1)}}</label>
                                    <select class="form-control custom-select @error('title_id') is-invalid @enderror" 
                                            name="title_id" v-model="title_id">
                                        <option value="">Select</option>
                                        @foreach($titles as $key)
                                            <option value="{{$key->id}}">{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('title_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.first_name',1)}} <span class="required">*</span></label>
                                    <input type="text" name="first_name" v-model="first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           placeholder="Enter first name" required>
                                    @error('first_name')
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
                                    <label>{{trans_choice('client::general.middle_name',1)}}</label>
                                    <input type="text" name="middle_name" v-model="middle_name"
                                           class="form-control @error('middle_name') is-invalid @enderror"
                                           placeholder="Enter middle name">
                                    @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.last_name',1)}} <span class="required">*</span></label>
                                    <input type="text" name="last_name" v-model="last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           placeholder="Enter last name" required>
                                    @error('last_name')
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
                                    <label>{{trans_choice('client::general.gender',1)}} <span class="required">*</span></label>
                                    <select class="form-control custom-select @error('gender') is-invalid @enderror" 
                                            name="gender" v-model="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">{{trans_choice('core::general.male',1)}}</option>
                                        <option value="female">{{trans_choice('core::general.female',1)}}</option>
                                        <option value="other">{{trans_choice('core::general.other',1)}}</option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.dob',1)}} <span class="required">*</span></label>
                                    <input type="date" name="dob" v-model="dob"
                                           class="form-control @error('dob') is-invalid @enderror" required>
                                    @error('dob')
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
                                    <label>Ghana Card (National ID)</label>
                                    <input type="text" name="ghana_card" v-model="ghana_card"
                                           class="form-control @error('ghana_card') is-invalid @enderror"
                                           placeholder="GHA-XXXXXXXXX-X">
                                    @error('ghana_card')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('core::general.external_id',1)}}</label>
                                    <input type="text" name="external_id" v-model="external_id"
                                           class="form-control @error('external_id') is-invalid @enderror"
                                           placeholder="External reference ID">
                                    @error('external_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="section-title">
                                <h3>Contact Information</h3>
                                <p>Phone, email, and address details</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.mobile',1)}} <span class="required">*</span></label>
                                    <input type="text" name="mobile" v-model="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           placeholder="0XXXXXXXXX" required>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.email',1)}}</label>
                                    <input type="email" name="email" v-model="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="customer@example.com">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>{{trans_choice('client::general.address',1)}}</label>
                            <textarea name="address" v-model="address"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Enter full address" rows="3"></textarea>
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('core::general.country',1)}} <span class="required">*</span></label>
                                    <select class="form-control custom-select @error('country_id') is-invalid @enderror" 
                                            name="country_id" v-model="country_id" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $key)
                                            <option value="{{$key->id}}" @if($key->name == 'Ghana') selected @endif>{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.marital_status',1)}}</label>
                                    <select class="form-control custom-select" name="marital_status" v-model="marital_status">
                                        <option value="">Select Status</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Setup -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="section-title">
                                <h3>Account Setup</h3>
                                <p>Branch and officer assignment</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('core::general.branch',1)}} <span class="required">*</span></label>
                                    <select class="form-control custom-select @error('branch_id') is-invalid @enderror" 
                                            name="branch_id" v-model="branch_id" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $key)
                                            <option value="{{$key->id}}">{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>{{trans_choice('client::general.loan_officer',1)}}</label>
                                    <select class="form-control custom-select" name="loan_officer_id" v-model="loan_officer_id">
                                        <option value="">Select Officer</option>
                                        @foreach($users as $key)
                                            <option value="{{$key->id}}">{{$key->first_name}} {{$key->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>{{trans_choice('client::general.profession',1)}}</label>
                            <select class="form-control custom-select" name="profession_id" v-model="profession_id">
                                <option value="">Select Profession</option>
                                @foreach($professions as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label>Account Creation Date <span class="required">*</span></label>
                            <input type="date" name="created_date" v-model="created_date"
                                   class="form-control @error('created_date') is-invalid @enderror"
                                   value="{{date('Y-m-d')}}" required>
                            @error('created_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label>{{trans_choice('core::general.notes',1)}}</label>
                            <textarea name="notes" v-model="notes"
                                      class="form-control"
                                      placeholder="Additional notes or comments" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" onclick="window.history.back()" class="btn btn-cancel-modern">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-submit-modern">
                                <i class="fas fa-check-circle"></i> Create Customer Account
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-4">
                    <!-- Photo Upload -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="section-title">
                                <h3>Customer Photo</h3>
                                <p>Upload profile picture</p>
                            </div>
                        </div>

                        <div class="photo-upload-area" onclick="document.getElementById('photo').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Click to upload photo</strong></p>
                            <p style="font-size: 12px; margin-top: 5px;">JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" style="display: none;" 
                               onchange="previewPhoto(event)">
                        <img id="photoPreview" class="photo-preview">
                        @error('photo')
                        <span class="text-danger" style="font-size: 13px;">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>All fields marked with <span style="color: #eb3349;">*</span> are required</p>
                    </div>

                    <!-- Duplicate Check Info -->
                    <div class="form-section-card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); border-left: 4px solid #ffc107;">
                        <h6 style="font-weight: 700; color: #856404; margin-bottom: 10px;">
                            <i class="fas fa-shield-alt"></i> Duplicate Prevention
                        </h6>
                        <p style="font-size: 13px; color: #856404; margin: 0;">
                            The system automatically checks for duplicate:
                        </p>
                        <ul style="font-size: 13px; color: #856404; margin: 10px 0 0 20px; padding: 0;">
                            <li>Mobile numbers</li>
                            <li>Email addresses</li>
                            <li>Ghana Card numbers</li>
                            <li>External IDs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                title_id: '',
                first_name: '',
                middle_name: '',
                last_name: '',
                gender: '',
                dob: '',
                ghana_card: '',
                external_id: '',
                mobile: '',
                email: '',
                address: '',
                country_id: '',
                marital_status: '',
                branch_id: '',
                loan_officer_id: '',
                profession_id: '',
                created_date: '{{date('Y-m-d')}}',
                notes: ''
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
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
BLADE;

file_put_contents($filePath, $newContent);

echo "\n===========================================\n";
echo "✅ CLIENT CREATE PAGE REDESIGNED!\n";
echo "===========================================\n\n";

echo "Features Added:\n";
echo "  ✓ Modern gradient header\n";
echo "  ✓ Organized sections with icons\n";
echo "  ✓ Professional form styling\n";
echo "  ✓ Photo upload with preview\n";
echo "  ✓ Duplicate prevention info\n";
echo "  ✓ Required field indicators\n";
echo "  ✓ Responsive 8/4 layout\n";
echo "  ✓ Modern buttons\n";
echo "  ✓ Info boxes\n";
echo "  ✓ Clean, banking aesthetic\n\n";

echo "Visit /client/create to see the new design!\n";
echo "===========================================\n";
