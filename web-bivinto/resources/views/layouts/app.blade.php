<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bivinto')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'public/css/style.css'])
    @stack('styles')
</head>

<body>

    <!-- Desktop Header -->
    <header class="custom-header d-none d-xl-flex sticky-top">
        <div class="logo-area">
            <!-- Placeholder logo, replace with actual logo -->
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid">
            </a>
        </div>
        <div class="nav-area">
            <a href="/ve-chung-toi">VỀ BIVINTO</a>
            <a href="/san-pham">SẢN PHẨM</a>
            <a href="/hop-tac">HỢP TÁC</a>
            <a href="/chinh-sach">CHÍNH SÁCH</a>
            <a href="/blogs">BLOGS</a>
        </div>
        <div class="search-area d-flex align-items-center">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Tìm kiếm">
        </div>
        @if(auth()->check() && !auth()->user()->isAdmin())
        <div class="user-area d-flex align-items-center justify-content-center dropdown hover-dropdown">
            <a href="#" class="text-decoration-none d-flex align-items-center justify-content-center w-100 h-100">
                <i class="fa-regular fa-user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow mt-2">
                <li><a class="dropdown-item" href="/ho-so">Tài khoản của tôi</a></li>
                <li><a class="dropdown-item" href="#">Đơn mua</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#" onclick="logout(event)">Đăng xuất</a></li>
            </ul>
        </div>
        @else
        <a href="/tai-khoan" class="user-area d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fa-regular fa-user"></i>
        </a>
        @endif
        <a href="/gio-hang" class="cart-area d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fa-solid fa-bag-shopping"></i>
        </a>
    </header>

    <!-- Mobile Header -->
    <header
        class="mobile-header d-flex d-xl-none justify-content-between align-items-center px-3 border-bottom flex-shrink-0 sticky-top">
        <div class="logo-area-mobile">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid d-block">
            </a>
        </div>
        <div class="mobile-actions d-flex align-items-center gap-2">
            <a href="#" class="text-dark p-2"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="/gio-hang" class="text-dark p-2"><i class="fa-solid fa-bag-shopping"></i></a>
            <button class="btn border-0 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fa-solid fa-bars fs-4 text-dark"></i>
            </button>
        </div>
    </header>

    <!-- Fullscreen Mobile Menu -->
    <div class="offcanvas offcanvas-end w-100 mobile-menu-offcanvas" tabindex="-1" id="mobileMenu">
        <div
            class="d-flex justify-content-between align-items-center px-3 border-bottom flex-shrink-0 mobile-menu-header">
            <div class="logo-area-mobile">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid d-block">
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="nav flex-column font-google-sans">
                @if(auth()->check() && !auth()->user()->isAdmin())
                    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <div class="text-muted small">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/ho-so">TÀI KHOẢN CỦA TÔI</a>
                    <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="#">ĐƠN MUA</a>
                @else
                    <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/tai-khoan">ĐĂNG NHẬP / ĐĂNG KÝ</a>
                @endif
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/ve-chung-toi">VỀ BIVINTO</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/san-pham">SẢN PHẨM</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/hop-tac">HỢP TÁC</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/chinh-sach">CHÍNH SÁCH</a>
                <a class="nav-link text-dark py-3 fw-semibold" href="/blogs">BLOGS</a>
                @if(auth()->check() && !auth()->user()->isAdmin())
                    <a class="nav-link text-danger border-top py-3 fw-semibold" href="#" onclick="logout(event)">ĐĂNG XUẤT</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer text-white" style="padding-top: 4.5rem;">
        <div class="container-fluid px-3 px-md-4 px-xl-5 pb-2">
            <div class="row gx-5 gy-4 footer-content-row">
                <!-- Column 1: Company Info -->
                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-3 pe-lg-4">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="footer-logo filter-white">
                    </a>
                    <div class="footer-contact d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1"></i>
                            <span>Số 82 Phố Dịch Vọng Hậu, Phường Cầu Giấy, Thành Phố Hà Nội, Việt Nam</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-phone"></i>
                            <span>+84 345 677 395</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-regular fa-envelope"></i>
                            <span>bivinto@gmai.com</span>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Categories -->
                <div class="col-6 col-md-3 col-lg-2 ms-lg-auto">
                    <h5 class="footer-title">DANH MỤC</h5>
                    <ul class="list-unstyled footer-links d-flex flex-column gap-2">
                        <li><a href="/ve-bivinto">Về Bivinto</a></li>
                        <li><a href="/san-pham">Sản Phẩm</a></li>
                        <li><a href="#">Hợp Tác</a></li>
                        <li><a href="/chinh-sach">Chính Sách</a></li>
                        <li><a href="/blogs">Blogs</a></li>
                    </ul>
                </div>

                <!-- Column 3: Connect -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h5 class="footer-title">KẾT NỐI</h5>
                    <ul class="list-unstyled footer-links d-flex flex-column gap-2">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Zalo</a></li>
                        <li><a href="#">Tiktok</a></li>
                        <li><a href="#">Shopee</a></li>
                    </ul>
                </div>

                <!-- Column 4: Map -->
                <div class="col-12 col-lg-4 mt-4 mt-lg-0 text-center text-lg-end">
                    <iframe
                        src="https://maps.google.com/maps?q=S%E1%BB%91%2082%20Ph%E1%BB%91%20D%E1%BB%8Bch%20V%E1%BB%8Dng%20H%E1%BA%ADu%2C%20C%E1%BA%A7u%20Gi%E1%BA%A5y%2C%20H%C3%A0%20N%E1%BB%99i&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        class="footer-map edge-to-edge-mobile mt-2"
                        style="border:0; width: 100%; max-width: 400px; height: 245px;" allowfullscreen=""
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="footer-bottom text-center">
                <p class="mb-0">Copyright © Bivinto, Ltd. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- Cấu hình chung cho Toast ---
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Hàm hiển thị Toast (Tự ẩn)
        window.showToast = function(message, type = 'success') {
            return Toast.fire({
                icon: type,
                title: message
            });
        };

        // Hàm hiển thị Dialog Xác Nhận (Có nút Đồng ý / Hủy)
        window.showConfirm = function(title, text = '', confirmButtonText = 'Đồng ý', cancelButtonText = 'Hủy') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#100F0F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                reverseButtons: true
            }).then((result) => {
                return result.isConfirmed;
            });
        };

        // Hàm hiển thị Dialog Thông Báo (Chỉ có nút OK)
        window.showAlert = function(title, text = '', icon = 'info') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#100F0F',
                confirmButtonText: 'OK'
            });
        };

        // Hàm Đăng xuất
        window.logout = async function(e) {
            if (e) e.preventDefault();
            
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            } catch (err) {}

            // Dọn dẹp local storage nếu còn
            localStorage.removeItem('user');
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            
            window.location.href = '/tai-khoan';
        };


        document.addEventListener("DOMContentLoaded", function() {
            const headers = document.querySelectorAll("header");
            let lastScrollY = window.scrollY;

            window.addEventListener("scroll", function() {
                const currentScrollY = window.scrollY;

                // Add shadow if not at the top
                if (currentScrollY > 10) {
                    headers.forEach(header => header.classList.add("header-shadow"));
                } else {
                    headers.forEach(header => header.classList.remove("header-shadow"));
                }

                // Hide on scroll down, show on scroll up
                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    headers.forEach(header => header.classList.add("header-hidden"));
                } else {
                    headers.forEach(header => header.classList.remove("header-hidden"));
                }

                lastScrollY = currentScrollY;
            });

            // Global Password Toggle
            const toggleIcons = document.querySelectorAll('.toggle-password');
            toggleIcons.forEach(icon => {
                icon.style.display = 'none'; // Hide by default
                const input = icon.previousElementSibling;
                if (input && input.tagName === 'INPUT') {
                    input.addEventListener('focus', () => {
                        icon.style.display = 'block';
                    });
                    
                    input.addEventListener('blur', () => {
                        setTimeout(() => {
                            // If we didn't just click the icon to refocus, hide it
                            if (document.activeElement !== input) {
                                icon.style.display = 'none';
                            }
                        }, 150);
                    });

                    icon.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent focus loss if possible
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                        input.focus(); // Keep focus on input
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
