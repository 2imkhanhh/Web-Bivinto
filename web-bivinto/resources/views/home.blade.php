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
                <a href="/san-pham" class="btn btn-black rounded-pill">Khám Phá Sản Phẩm</a>
                <a href="/hop-tac" class="btn btn-gray rounded-pill">Liên Hệ Hợp Tác</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section pb-5 overflow-hidden">
        <div class="container">
            <div class="row gx-4 align-items-start mb-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="about-title">VỀ BIVINTO</h2>
                    <p class="about-subtitle m-0">Bivinto - Nơi Mọi Hành Trình Mạnh Mẽ Bắt Đầu</p>
                </div>
                <div class="col-md-6">
                    <p class="about-desc mb-4 max-w-480">
                        Bivinto được sinh ra từ khát vọng giúp đàn ông Việt dễ dàng tiếp cận thời trang chất lượng, xây dựng
                        phong thái tự tin và trưởng thành hơn mỗi ngày.
                    </p>
                    <a href="/ve-chung-toi" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                        Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-0">
            <div class="d-flex flex-column flex-md-row w-100 gallery-gap">
                <div class="flex-grow-1 w-100 mb-4 mb-md-0">
                    <img src="{{ asset('images/pic1.png') }}" alt="Bivinto Fashion 1"
                        class="img-fluid w-100 object-fit-cover gallery-img">
                </div>
                <div class="flex-grow-1 w-100">
                    <img src="{{ asset('images/pic2.png') }}" alt="Bivinto Fashion 2"
                        class="img-fluid w-100 object-fit-cover gallery-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Business Areas Section -->
    <section class="business-areas-section py-5">
        <div class="container text-center mb-5">
            <h2 class="fw-bold mb-3 section-title">LĨNH VỰC HOẠT ĐỘNG</h2>
            <p class="mb-0 section-subtitle">Bivinto Đồng Hành Cùng Đối Tác Và Khách Hàng</p>
        </div>

        <div class="container px-lg-5">
            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-transparent h-100">
                        <img src="{{ asset('images/pic3.png') }}" alt="Gia Công Thời Trang"
                            class="card-img-top rounded-0 object-fit-cover business-card-img">
                        <div class="card-body px-0 pt-4 pb-0 d-flex flex-column">
                            <h5 class="card-title text-uppercase mb-2 business-card-title">GIA CÔNG THỜI TRANG</h5>
                            <p class="card-text small mb-4 business-card-desc">Thiết kế, sản xuất, OEM/ ODM</p>
                            <div class="mt-auto">
                                <a href="/hop-tac" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                                    Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-transparent h-100">
                        <img src="{{ asset('images/pic4.png') }}" alt="Nhập Sỉ"
                            class="card-img-top rounded-0 object-fit-cover business-card-img">
                        <div class="card-body px-0 pt-4 pb-0 d-flex flex-column">
                            <h5 class="card-title text-uppercase mb-2 business-card-title">NHẬP SỈ</h5>
                            <p class="card-text small mb-4 business-card-desc">Chính sách hấp dẫn, nguồn hàng ổn định
                            </p>
                            <div class="mt-auto">
                                <a href="/hop-tac" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                                    Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-transparent h-100">
                        <img src="{{ asset('images/pic5.png') }}" alt="Bán Lẻ"
                            class="card-img-top rounded-0 object-fit-cover business-card-img">
                        <div class="card-body px-0 pt-4 pb-0 d-flex flex-column">
                            <h5 class="card-title text-uppercase mb-2 business-card-title">BÁN LẺ</h5>
                            <p class="card-text small mb-4 business-card-desc">Thời trang hiện đại, chất lượng cao</p>
                            <div class="mt-auto">
                                <a href="/hop-tac" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                                    Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products-section py-5">
        <div class="container-fluid px-3 px-md-4 px-xl-5">
            <!-- Header Area -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end mb-4">
                <h2 class="fw-bold mb-3 mb-lg-0 section-title text-start pe-lg-5">DANH MỤC<br>SẢN PHẨM NỔI BẬT</h2>

                <!-- Custom Tabs -->
                <ul class="nav custom-product-tabs flex-nowrap overflow-x-auto overflow-y-hidden text-nowrap pb-0 justify-content-between">
                    @forelse($categories as $index => $category)
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link category-tab {{ $index === 0 ? 'active' : '' }} fw-medium px-0 text-muted" data-id="{{ $category->id }}" href="#">{{ mb_strtoupper($category->name, 'UTF-8') }}</a>
                        </li>
                    @empty
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link active fw-medium px-0 text-muted" href="#">CHƯA CÓ DANH MỤC</a>
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Product Grid -->
            <div class="row gx-1 gy-5">
                @forelse ($featuredProducts as $product)
                    @php
                        $primaryImage = $product->images->where('is_primary', true)->first();
                        $imagePath = $primaryImage ? $primaryImage->image_path : ($product->images->first() ? $product->images->first()->image_path : null);
                        $imageUrl = $imagePath ? asset('storage/' . $imagePath) : asset('images/product1.png');
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 product-item" data-category-id="{{ $product->category_id }}">
                        <div class="product-card h-100 d-flex flex-column">
                            <div class="product-img-wrapper mb-3">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="img-fluid w-100 object-fit-cover product-img">
                            </div>
                            <h3 class="product-title text-truncate mb-1">{{ $product->name }}</h3>
                            <p class="product-price fw-bold mb-3">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                            <div class="mt-auto">
                                <a href="/chi-tiet-san-pham/{{ $product->slug }}" class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium">
                                    Xem Chi Tiết <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <p>Chưa có sản phẩm nổi bật nào.</p>
                    </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-5">
                <a href="#" class="btn btn-dark rounded-pill px-5 py-2 fw-medium btn-load-more">
                    <i class="fa-solid fa-plus me-2"></i> Xem Thêm
                </a>
            </div>
        </div>
    </section>

    <!-- Blogs Section -->
    <section class="blogs-section">
        <div class="container-fluid px-3 px-md-4 px-xl-5">
            <div class="blog-section-header d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0 section-title">BLOGS</h2>
                <a href="/blogs" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                    Xem Thêm <i class="fa-solid fa-arrow-right ms-2 btn-icon-sm"></i>
                </a>
            </div>

            <div class="row g-4">
                <!-- Column 1 (Tall) -->
                <div class="col-12 col-md-4">
                    <div class="blog-card h-100 d-flex flex-column">
                        <img src="{{ asset('images/product1.png') }}" alt="Behind The Suit" class="blog-img-tall">
                        <h5 class="blog-title text-uppercase">BEHIND THE SUIT</h5>
                        <p class="blog-desc">Meet four of the designers defining modern tailoring</p>
                        <div class="mt-auto">
                            <a href="#" class="blog-link">Chi Tiết</a>
                        </div>
                    </div>
                </div>

                <!-- Column 2 (Two Shorts) -->
                <div class="col-12 col-md-4 d-flex flex-column gap-4">
                    <div class="blog-card flex-grow-1 d-flex flex-column">
                        <img src="{{ asset('images/product1.png') }}" alt="Behind The Suit" class="blog-img-short">
                        <h5 class="blog-title text-uppercase">BEHIND THE SUIT</h5>
                        <p class="blog-desc">Meet four of the designers defining modern tailoring</p>
                        <div class="mt-auto">
                            <a href="#" class="blog-link">Chi Tiết</a>
                        </div>
                    </div>
                    <div class="blog-card flex-grow-1 d-flex flex-column">
                        <img src="{{ asset('images/product1.png') }}" alt="Behind The Suit" class="blog-img-short">
                        <h5 class="blog-title text-uppercase">BEHIND THE SUIT</h5>
                        <p class="blog-desc">Meet four of the designers defining modern tailoring</p>
                        <div class="mt-auto">
                            <a href="#" class="blog-link">Chi Tiết</a>
                        </div>
                    </div>
                </div>

                <!-- Column 3 (Tall) -->
                <div class="col-12 col-md-4">
                    <div class="blog-card h-100 d-flex flex-column">
                        <img src="{{ asset('images/product1.png') }}" alt="Behind The Suit" class="blog-img-tall">
                        <h5 class="blog-title text-uppercase">BEHIND THE SUIT</h5>
                        <p class="blog-desc">Meet four of the designers defining modern tailoring</p>
                        <div class="mt-auto">
                            <a href="#" class="blog-link">Chi Tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.category-tab');
        const products = document.querySelectorAll('.product-item');
        
        function filterProducts(categoryId) {
            let count = 0;
            products.forEach(product => {
                if (product.dataset.categoryId === categoryId) {
                    product.style.display = 'block';
                    count++;
                } else {
                    product.style.display = 'none';
                }
            });
            
            // Optional: Hide/Show a "No products" message if count === 0
        }

        // Initialize with the first tab
        if (tabs.length > 0) {
            filterProducts(tabs[0].dataset.id);
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                // Remove active class from all
                tabs.forEach(t => t.classList.remove('active'));
                // Add to clicked
                this.classList.add('active');
                
                filterProducts(this.dataset.id);
            });
        });
    });
</script>
@endpush
