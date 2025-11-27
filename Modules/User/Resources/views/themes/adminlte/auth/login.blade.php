@extends('core::layouts.auth')
@section("title")
    {{trans_choice("user::general.login",1)}}
@endsection

@section('styles')
<style>
    body.login-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    /* Animated background shapes */
    body.login-page::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -250px;
        right: -250px;
        animation: float 20s infinite ease-in-out;
    }
    
    body.login-page::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        bottom: -200px;
        left: -200px;
        animation: float 15s infinite ease-in-out reverse;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-30px) translateX(30px); }
    }
    
    .modern-login-container {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 450px;
        padding: 20px;
    }
    
    .modern-login-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 50px 40px;
        animation: slideUp 0.5s ease;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .login-logo-wrapper {
        margin-bottom: 20px;
    }
    
    .login-logo-wrapper img {
        max-width: 180px;
        height: auto;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }
    
    .login-title {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }
    
    .login-subtitle {
        font-size: 15px;
        color: #718096;
        font-weight: 400;
    }
    
    .modern-form-group {
        margin-bottom: 25px;
    }
    
    .modern-form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }
    
    .modern-input-wrapper {
        position: relative;
    }
    
    .modern-input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 18px;
    }
    
    .modern-form-control {
        width: 100%;
        padding: 14px 15px 14px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f7fafc;
    }
    
    .modern-form-control:focus {
        outline: none;
        border-color: #667eea;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .modern-form-control.is-invalid {
        border-color: #fc8181;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #a0aec0;
        font-size: 18px;
        transition: color 0.3s;
    }
    
    .password-toggle:hover {
        color: #667eea;
    }
    
    .modern-checkbox-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
    }
    
    .modern-checkbox {
        display: flex;
        align-items: center;
    }
    
    .modern-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        cursor: pointer;
    }
    
    .modern-checkbox label {
        font-size: 14px;
        color: #4a5568;
        margin: 0;
        cursor: pointer;
    }
    
    .forgot-link {
        font-size: 14px;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .forgot-link:hover {
        color: #764ba2;
        text-decoration: none;
    }
    
    .modern-login-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .modern-login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }
    
    .modern-login-btn:active {
        transform: translateY(0);
    }
    
    .register-link-wrapper {
        text-align: center;
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #e2e8f0;
    }
    
    .register-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.3s;
    }
    
    .register-link:hover {
        color: #764ba2;
        text-decoration: none;
    }
    
    .invalid-feedback {
        display: block;
        color: #fc8181;
        font-size: 13px;
        margin-top: 6px;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .modern-login-card {
            padding: 40px 30px;
        }
        
        .login-title {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
    @php
        $logoSetting = \Modules\Setting\Entities\Setting::where('setting_key','core.company_logo')->first();
        $logo = $logoSetting ? $logoSetting->setting_value : '';
        $companyNameSetting = \Modules\Setting\Entities\Setting::where('setting_key','core.company_name')->first();
        $companyName = $companyNameSetting ? $companyNameSetting->setting_value : 'Ultimate Loan Manager';
    @endphp
    
    <div class="modern-login-container">
        <div class="modern-login-card">
            <div class="login-header">
                <div class="login-logo-wrapper">
                    @if(!empty($logo))
                        <img src="{{asset('storage/uploads/'.$logo)}}" alt="{{$companyName}}">
                    @else
                        <h2 class="login-title">{{$companyName}}</h2>
                    @endif
                </div>
                @if(!empty($logo))
                    <h2 class="login-title">Welcome Back</h2>
                @endif
                <p class="login-subtitle">{{trans_choice("user::general.login_msg",1)}}</p>
            </div>

            <form method="post" action="{{ route('login') }}">
                {{csrf_field()}}
                
                <div class="modern-form-group">
                    <label class="modern-form-label" for="email">
                        {{trans_choice("user::general.email",1)}}
                    </label>
                    <div class="modern-input-wrapper">
                        <i class="fas fa-envelope modern-input-icon"></i>
                        <input type="email" 
                               class="modern-form-control @error('email') is-invalid @enderror"
                               name="email"
                               placeholder="Enter your email address" 
                               value="{{ old('email') }}"
                               required
                               autocomplete="email" 
                               id="email" 
                               autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label" for="password">
                        {{trans_choice("user::general.password",1)}}
                    </label>
                    <div class="modern-input-wrapper">
                        <i class="fas fa-lock modern-input-icon"></i>
                        <input type="password" 
                               name="password"
                               class="modern-form-control @error('password') is-invalid @enderror"
                               placeholder="Enter your password" 
                               required
                               autocomplete="current-password" 
                               id="password">
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="modern-checkbox-wrapper">
                    <div class="modern-checkbox">
                        <input type="checkbox" 
                               name="remember"
                               {{ old('remember') ? 'checked' : '' }} 
                               id="remember">
                        <label for="remember">{{trans_choice("user::general.remember_me",1)}}</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        {{trans_choice("user::general.forgot_password",1)}}
                    </a>
                </div>

                <button type="submit" class="modern-login-btn">
                    <i class="fas fa-sign-in-alt"></i> {{trans_choice("user::general.login",1)}}
                </button>
            </form>

            @php
                $registrationSetting = \Modules\Setting\Entities\Setting::where('setting_key','user.enable_registration')->first();
                $enableRegistration = $registrationSetting ? $registrationSetting->setting_value : 'no';
            @endphp
            @if($enableRegistration == 'yes')
                <div class="register-link-wrapper">
                    <span style="color: #718096;">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="register-link">
                        {{trans_choice("user::general.register_msg",1)}}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
@endsection
