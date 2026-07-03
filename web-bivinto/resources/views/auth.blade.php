@extends('layouts.app')

@section('title', 'Bivinto - Tài Khoản')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-page">
        <div class="auth-container" id="authContainer">
            
            <!-- Sign Up Form (Left Panel in code, but z-indexed) -->
            <div class="form-container sign-up-container">
                <div class="form-content">
                    <form id="registerForm" action="#" method="POST">
                        <h1 class="auth-title">ĐĂNG KÝ</h1>
                        <p class="auth-subtitle mb-4">Trở thành thành viên của Bivinto.</p>

                        <div class="mb-3 w-100">
                            <input type="text" class="form-control custom-input" id="reg_name" placeholder="Họ và tên*" required>
                        </div>
                        <div class="mb-3 w-100">
                            <input type="email" class="form-control custom-input" id="reg_email" placeholder="Email*" required>
                        </div>
                        <div class="mb-3 w-100">
                            <input type="tel" class="form-control custom-input" id="reg_phone" placeholder="Số điện thoại*" required>
                        </div>
                        <div class="mb-4 w-100">
                            <input type="password" class="form-control custom-input" id="reg_password" placeholder="Mật khẩu*" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 rounded-pill btn-auth fw-medium">Tạo Tài Khoản</button>
                    </form>
                </div>
            </div>

            <!-- Sign In Form (Right Panel in code) -->
            <div class="form-container sign-in-container">
                <div class="form-content">
                    <form id="loginForm" action="#" method="POST">
                        <h1 class="auth-title">ĐĂNG NHẬP</h1>
                        <p class="auth-subtitle mb-4">Chào mừng bạn quay lại với Bivinto.</p>

                        <div class="mb-3 w-100">
                            <input type="email" class="form-control custom-input" id="login_email" placeholder="Email hoặc số điện thoại*" required>
                        </div>
                        <div class="mb-3 w-100">
                            <input type="password" class="form-control custom-input" id="login_password" placeholder="Mật khẩu*" required>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 w-100">
                            <div class="form-check text-start">
                                <input type="checkbox" class="form-check-input border-secondary" id="rememberMe">
                                <label class="form-check-label text-muted" for="rememberMe" style="font-size: 0.9rem;">Ghi nhớ</label>
                            </div>
                            <a href="#" class="text-muted text-decoration-none" style="font-size: 0.9rem;">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 rounded-pill btn-auth fw-medium">Đăng Nhập</button>
                    </form>
                </div>
            </div>

            <!-- Overlay Container (The Image Panel) -->
            <div class="overlay-container d-none d-md-block">
                <div class="overlay">
                    <!-- The Panel visible when Sign In is active -->
                    <div class="overlay-panel overlay-left">
                        <h1 class="overlay-title">Chào Mừng Quay Lại!</h1>
                        <p class="overlay-subtitle">Để giữ liên lạc với chúng tôi, vui lòng đăng nhập bằng thông tin cá nhân của bạn.</p>
                        <button class="btn btn-overlay" id="signIn">Đăng Nhập</button>
                    </div>
                    
                    <!-- The Panel visible when Sign Up is active -->
                    <div class="overlay-panel overlay-right">
                        <h1 class="overlay-title">Chào Bạn,</h1>
                        <p class="overlay-subtitle">Nhập thông tin cá nhân của bạn và bắt đầu hành trình mua sắm cùng Bivinto.</p>
                        <button class="btn btn-overlay" id="signUp">Đăng Ký Ngay</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const authContainer = document.getElementById('authContainer');

        if(signUpButton && signInButton && authContainer) {
            signUpButton.addEventListener('click', () => {
                authContainer.classList.add("right-panel-active");
            });

            signInButton.addEventListener('click', () => {
                authContainer.classList.remove("right-panel-active");
            });
        }

        // --- Handle Register ---
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const name = document.getElementById('reg_name').value;
                const email = document.getElementById('reg_email').value;
                const phone = document.getElementById('reg_phone').value;
                const password = document.getElementById('reg_password').value;

                try {
                    const response = await fetch('/api/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name, email, phone, password })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Đăng ký thành công! Vui lòng đăng nhập.');
                        // Switch to login panel
                        authContainer.classList.remove("right-panel-active");
                        registerForm.reset();
                    } else {
                        // Show validation errors
                        let errorMsg = 'Lỗi đăng ký:\n';
                        if (data.error && typeof data.error === 'object') {
                            for (let key in data.error) {
                                errorMsg += `- ${data.error[key][0]}\n`;
                            }
                        } else {
                            errorMsg += data.error || 'Đã có lỗi xảy ra.';
                        }
                        alert(errorMsg);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Lỗi kết nối máy chủ!');
                }
            });
        }

        // --- Handle Login ---
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const email = document.getElementById('login_email').value;
                const password = document.getElementById('login_password').value;

                try {
                    const response = await fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email, password })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Đăng nhập thành công!');
                        
                        // Save tokens to localStorage
                        localStorage.setItem('access_token', data.access_token);
                        localStorage.setItem('refresh_token', data.refresh_token);
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Redirect to home page or user dashboard
                        window.location.href = '/';
                    } else {
                        alert(data.error || 'Email hoặc mật khẩu không đúng!');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Lỗi kết nối máy chủ!');
                }
            });
        }
    });
</script>
@endpush
