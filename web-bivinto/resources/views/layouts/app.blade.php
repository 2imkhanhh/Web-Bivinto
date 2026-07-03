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
        <a href="#" class="user-area d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fa-regular fa-user"></i>
        </a>
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
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/ve-chung-toi">VỀ BIVINTO</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/san-pham">SẢN PHẨM</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/hop-tac">HỢP TÁC</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="/chinh-sach">CHÍNH SÁCH</a>
                <a class="nav-link text-dark py-3 fw-semibold" href="/blogs">BLOGS</a>
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
    
    <script>
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
        });
    </script>
    
    @stack('scripts')
</body>

</html>
