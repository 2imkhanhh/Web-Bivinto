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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <!-- Desktop Header -->
    <header class="custom-header d-none d-xl-flex">
        <div class="logo-area">
            <!-- Placeholder logo, replace with actual logo -->
            <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid">
        </div>
        <div class="nav-area">
            <a href="#">VỀ BIVINTO</a>
            <a href="#">SẢN PHẨM</a>
            <a href="#">HỢP TÁC</a>
            <a href="#">CHÍNH SÁCH</a>
            <a href="#">BLOGS</a>
        </div>
        <div class="search-area d-flex align-items-center">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Tìm kiếm">
        </div>
        <div class="user-area d-flex align-items-center justify-content-center">
            <a href="#"><i class="fa-regular fa-user"></i></a>
        </div>
        <div class="cart-area d-flex align-items-center justify-content-center">
            <a href="#"><i class="fa-solid fa-bag-shopping"></i></a>
        </div>
    </header>

    <!-- Mobile Header -->
    <header class="mobile-header d-flex d-xl-none justify-content-between align-items-center px-3 border-bottom flex-shrink-0">
        <div class="logo-area-mobile">
            <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid d-block">
        </div>
        <div class="mobile-actions d-flex align-items-center gap-3">
            <a href="#" class="text-dark"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="#" class="text-dark"><i class="fa-solid fa-bag-shopping"></i></a>
            <button class="btn border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fa-solid fa-bars fs-4 text-dark"></i>
            </button>
        </div>
    </header>

    <!-- Fullscreen Mobile Menu -->
    <div class="offcanvas offcanvas-end w-100 mobile-menu-offcanvas" tabindex="-1" id="mobileMenu">
        <div class="d-flex justify-content-between align-items-center px-3 border-bottom flex-shrink-0 mobile-menu-header">
            <div class="logo-area-mobile">
                <img src="{{ asset('images/logo.png') }}" alt="Bivinto Logo" class="img-fluid d-block">
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="nav flex-column font-google-sans">
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="#">VỀ BIVINTO</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="#">SẢN PHẨM</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="#">HỢP TÁC</a>
                <a class="nav-link text-dark border-bottom py-3 fw-semibold" href="#">CHÍNH SÁCH</a>
                <a class="nav-link text-dark py-3 fw-semibold" href="#">BLOGS</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
