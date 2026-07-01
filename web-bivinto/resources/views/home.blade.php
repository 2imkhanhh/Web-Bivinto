@extends('layouts.app')

@section('title', 'Bivinto - Trang Chủ')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden p-0">
        <img src="{{ asset('images/banner.png') }}" alt="Banner" class="w-100 h-auto d-block">
        <div class="hero-content w-100 z-1 px-3">
            <h1>MẶC ĐÚNG GU<br>COOL ĐÚNG CHẤT</h1>
            <p>Đồng hành cùng đàn ông việt trên hành trình xây dựng phong thái tự tin và chinh phục mục tiêu</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="#" class="btn btn-black rounded-pill">Khám Phá Sản Phẩm</a>
                <a href="#" class="btn btn-gray rounded-pill">Liên Hệ Hợp Tác</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section pb-5 overflow-hidden">
        <div class="container pt-4">
            <div class="row gx-4 align-items-start mb-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="about-title">VỀ BIVINTO</h2>
                    <p class="about-subtitle m-0">Bivinto - Nơi Mọi Hành Trình Mạnh Mẽ Bắt Đầu</p>
                </div>
                <div class="col-md-6">
                    <p class="about-desc mb-4" style="max-width: 480px;">
                        Bivinto được sinh ra từ khát vọng giúp đàn ông Việt dễ dàng tiếp cận thời trang chất lượng, xây dựng
                        phong thái tự tin và trưởng thành hơn mỗi ngày.
                    </p>
                    <a href="#" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                        Xem Thêm <i class="fa-solid fa-chevron-right ms-2" style="font-size: 0.8rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-0">
            <div class="d-flex flex-column flex-md-row w-100" style="gap: 1.5rem;">
                <div class="flex-grow-1 w-100 mb-4 mb-md-0">
                    <img src="{{ asset('images/pic1.png') }}" alt="Bivinto Fashion 1" class="img-fluid w-100 object-fit-cover" style="height: 650px; object-position: top;">
                </div>
                <div class="flex-grow-1 w-100">
                    <img src="{{ asset('images/pic2.png') }}" alt="Bivinto Fashion 2" class="img-fluid w-100 object-fit-cover" style="height: 650px; object-position: top;">
                </div>
            </div>
        </div>
    </section>
@endsection
