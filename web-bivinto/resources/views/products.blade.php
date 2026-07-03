@extends('layouts.app')

@section('title', 'Bivinto - Sản Phẩm')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endpush

@section('content')
    @php
        $mockProducts = [
            // Dòng 1: 4 sản phẩm bình thường
            [
                'image' => 'images/product1.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product2.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: M - XXL',
            ],
            [
                'image' => 'images/product3.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product4.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],

            // Dòng 2: 4 sản phẩm bình thường
            [
                'image' => 'images/product1.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product2.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: M - XXL',
            ],
            [
                'image' => 'images/product3.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product4.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],

            // Dòng 3: 3 sản phẩm trên 1 hàng (product5 to + 2 sản phẩm cũ nhỏ)
            [
                'image' => 'images/product5.png',
                'col_class' => 'col-12 col-md-8 col-lg-6',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product2.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: M - XXL',
            ],
            [
                'image' => 'images/product3.png',
                'col_class' => 'col-6 col-md-4 col-lg-3',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],

            // Dòng 4: 2 sản phẩm trên 1 hàng (sử dụng product6, product7)
            [
                'image' => 'images/product6.png',
                'col_class' => 'col-12 col-md-6 col-lg-6',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
            [
                'image' => 'images/product7.png',
                'col_class' => 'col-12 col-md-6 col-lg-6',
                'title' => 'Áo Sơ Mi Cộc Tay Cổ Đức',
                'price' => '850.000đ',
                'size' => 'Size: XS - XXL',
            ],
        ];
    @endphp

    <div class="container-fluid px-3 px-md-4 px-xl-5 py-4 mt-3 mt-xl-4">

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between align-items-center filter-bar-container">
            <div class="category-links">
                @forelse($categories as $index => $category)
                    <a href="#" class="{{ $index === 0 ? 'active' : '' }}">{{ $category->name }}</a>
                @empty
                    <a href="#" class="active">CHƯA CÓ DANH MỤC</a>
                @endforelse
            </div>
            <div class="filter-dropdowns">
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Kích Cỡ
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">S</a></li>
                    <li><a class="dropdown-item" href="#">M</a></li>
                    <li><a class="dropdown-item" href="#">L</a></li>
                    <li><a class="dropdown-item" href="#">XL</a></li>
                    <li><a class="dropdown-item" href="#">XXL</a></li>
                </ul>

                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Màu Sắc
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Trắng</a></li>
                    <li><a class="dropdown-item" href="#">Đen</a></li>
                    <li><a class="dropdown-item" href="#">Xanh</a></li>
                </ul>

                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Mức Giá
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Dưới 500.000đ</a></li>
                    <li><a class="dropdown-item" href="#">500.000đ - 1.000.000đ</a></li>
                    <li><a class="dropdown-item" href="#">Trên 1.000.000đ</a></li>
                </ul>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row gx-1 gy-5 mb-5">
            @foreach ($mockProducts as $product)
                <div class="{{ $product['col_class'] }}">
                    <div class="product-card">
                        <div class="product-img-wrapper mb-3">
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['title'] }}"
                                class="img-fluid w-100 object-fit-cover product-img">
                        </div>
                        <div class="product-size">{{ $product['size'] }}</div>
                        <h3 class="product-title text-truncate mb-1">{{ $product['title'] }}</h3>
                        <p class="product-price fw-bold mb-3">{{ $product['price'] }}</p>
                        <div>
                            <a href="/chi-tiet-san-pham" class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium">Xem Chi Tiết <i
                                    class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-5 mb-3 pb-3 pb-lg-5">
            <a href="#" class="btn btn-dark rounded-pill px-5 py-2 fw-medium btn-load-more">
                <i class="fa-solid fa-plus me-2"></i> Xem Thêm
            </a>
        </div>
    </div>
@endsection
