@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi - Bivinto')

@push('styles')
<style>
    .profile-container {
        max-width: 800px;
        margin: 3rem auto;
        padding: 0 1rem;
    }
    
    .profile-form label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #100F0F;
        font-size: 0.95rem;
    }

    .profile-form .form-text {
        font-size: 0.85rem;
        color: #616161;
        font-style: italic;
        margin-bottom: 1.5rem;
    }

    .profile-form .form-control {
        border-radius: 0;
        border: 1px solid #e5e5e5;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        box-shadow: none;
    }

    .profile-form .form-control:focus {
        border-color: #100F0F;
        outline: none;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        margin: 2.5rem 0 1.5rem 0;
        color: #100F0F;
    }

    .btn-save {
        background-color: #100F0F;
        color: #ffffff;
        border: none;
        padding: 0.8rem 2rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        margin-top: 2rem;
        border-radius: 0;
        transition: background-color 0.2s;
    }

    .btn-save:hover {
        background-color: #333;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <form id="profileForm" class="profile-form">
        <!-- Thông tin cơ bản -->
        <div>
            <label for="profile_name">Họ tên</label>
            <input type="text" class="form-control" id="profile_name" value="{{ auth()->user()->name }}" required>
        </div>
        
        <div class="mt-4">
            <label for="profile_email">Địa chỉ email</label>
            <input type="email" class="form-control" id="profile_email" value="{{ auth()->user()->email }}" required disabled style="background-color: #f9f9f9;">
        </div>

        <div class="mt-4">
            <label for="profile_phone">Số điện thoại</label>
            <input type="tel" class="form-control" id="profile_phone" value="{{ auth()->user()->phone ?? '' }}" required>
        </div>

        <!-- Đổi mật khẩu -->
        <h3 class="section-title">THAY ĐỔI MẬT KHẨU</h3>
        
        <div class="mb-3">
            <label for="current_password">Mật khẩu hiện tại</label>
            <div class="position-relative">
                <input type="password" class="form-control" id="current_password">
                <i class="fa-regular fa-eye toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #616161;"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="new_password">Mật khẩu mới</label>
            <div class="position-relative">
                <input type="password" class="form-control" id="new_password">
                <i class="fa-regular fa-eye toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #616161;"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="confirm_password">Xác nhận mật khẩu mới</label>
            <div class="position-relative">
                <input type="password" class="form-control" id="confirm_password">
                <i class="fa-regular fa-eye toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #616161;"></i>
            </div>
        </div>

        <button type="submit" class="btn btn-save">LƯU THAY ĐỔI</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Xử lý Form Submit
        const profileForm = document.getElementById('profileForm');
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.querySelector('.btn-save');
            btn.innerHTML = 'ĐANG LƯU...';
            btn.disabled = true;

            const name = document.getElementById('profile_name').value;
            const phone = document.getElementById('profile_phone').value;
            const current_password = document.getElementById('current_password').value;
            const new_password = document.getElementById('new_password').value;
            const confirm_password = document.getElementById('confirm_password').value;

            if (new_password && new_password !== confirm_password) {
                showToast('Mật khẩu xác nhận không khớp!', 'error');
                btn.innerHTML = 'LƯU THAY ĐỔI';
                btn.disabled = false;
                return;
            }

            try {
                const response = await fetch('/api/profile', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: name,
                        phone: phone,
                        current_password: current_password,
                        new_password: new_password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Reset password fields
                    document.getElementById('current_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('confirm_password').value = '';

                    showToast('Cập nhật thông tin thành công!', 'success');
                    btn.innerHTML = 'LƯU THAY ĐỔI';
                    btn.disabled = false;

                    // Reload page to update header name
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    let errorMsg = data.error || 'Lỗi cập nhật:\n';
                    if (data.errors && typeof data.errors === 'object') {
                        errorMsg = 'Lỗi cập nhật:\n';
                        for (let key in data.errors) {
                            errorMsg += `- ${data.errors[key][0]}\n`;
                        }
                    }
                    showToast(errorMsg, 'error');
                    btn.innerHTML = 'LƯU THAY ĐỔI';
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                showToast('Lỗi kết nối máy chủ!', 'error');
                btn.innerHTML = 'LƯU THAY ĐỔI';
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
