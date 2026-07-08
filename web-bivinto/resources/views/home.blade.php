@extends('layouts.app')

@section('title', 'Bivinto - Trang Chủ')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden p-0">
        @php
            $bannerImg = get_setting('banner_image', '/images/banner.png');
            $bannerSrc = str_starts_with($bannerImg, 'settings/') ? asset('storage/' . $bannerImg) : asset($bannerImg);
        @endphp
        <img src="{{ $bannerSrc }}" alt="Banner" class="w-100 h-auto d-block">
        <div class="hero-content w-100 z-1 px-3">
            <h1>{!! get_setting('banner_title', 'MẶC ĐÚNG GU<br>COOL ĐÚNG CHẤT') !!}</h1>
            <p>{{ get_setting('banner_subtitle', 'Đồng hành cùng đàn ông việt trên hành trình xây dựng phong thái tự tin và chinh phục mục tiêu') }}</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="{{ get_setting('banner_btn1_link', '/san-pham') }}" class="btn btn-black rounded-pill">{{ get_setting('banner_btn1_text', 'Khám Phá Sản Phẩm') }}</a>
                <a href="{{ get_setting('banner_btn2_link', '/hop-tac') }}" class="btn btn-gray rounded-pill">{{ get_setting('banner_btn2_text', 'Liên Hệ Hợp Tác') }}</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section pb-5 overflow-hidden">
        <div class="container">
            <div class="row gx-4 align-items-start mb-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="about-title">{{ get_setting('about_title', 'VỀ BIVINTO') }}</h2>
                    <p class="about-subtitle m-0">{{ get_setting('about_subtitle', 'Bivinto - Nơi Mọi Hành Trình Mạnh Mẽ Bắt Đầu') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="about-desc mb-4 max-w-480">
                        {{ get_setting('about_description', 'Bivinto được sinh ra từ khát vọng giúp đàn ông Việt dễ dàng tiếp cận thời trang chất lượng, xây dựng phong thái tự tin và trưởng thành hơn mỗi ngày.') }}
                    </p>
                    <a href="/ve-chung-toi" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                        Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-0">
            <div class="d-flex flex-column flex-md-row w-100 gallery-gap">
                @php
                    $aboutImg1 = get_setting('about_image_1', '/images/pic1.png');
                    $aboutSrc1 = str_starts_with($aboutImg1, 'settings/') ? asset('storage/' . $aboutImg1) : asset($aboutImg1);
                    $aboutImg2 = get_setting('about_image_2', '/images/pic2.png');
                    $aboutSrc2 = str_starts_with($aboutImg2, 'settings/') ? asset('storage/' . $aboutImg2) : asset($aboutImg2);
                @endphp
                <div class="flex-grow-1 w-100 mb-4 mb-md-0">
                    <img src="{{ $aboutSrc1 }}" alt="Bivinto Fashion 1"
                        class="img-fluid w-100 object-fit-cover gallery-img">
                </div>
                <div class="flex-grow-1 w-100">
                    <img src="{{ $aboutSrc2 }}" alt="Bivinto Fashion 2"
                        class="img-fluid w-100 object-fit-cover gallery-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Business Areas Section -->
    <section class="business-areas-section py-5">
        <div class="container text-center mb-5">
            <h2 class="fw-bold mb-3 section-title">{{ get_setting('business_title', 'LĨNH VỰC HOẠT ĐỘNG') }}</h2>
            <p class="mb-0 section-subtitle">{{ get_setting('business_subtitle', 'Bivinto Đồng Hành Cùng Đối Tác Và Khách Hàng') }}</p>
        </div>

        @php
            $bCards = [
                ['image' => 'business_card1_image', 'title' => 'business_card1_title', 'desc' => 'business_card1_desc', 'default_img' => '/images/pic3.png', 'default_title' => 'GIA CÔNG THỜI TRANG', 'default_desc' => 'Thiết kế, sản xuất, OEM/ ODM'],
                ['image' => 'business_card2_image', 'title' => 'business_card2_title', 'desc' => 'business_card2_desc', 'default_img' => '/images/pic4.png', 'default_title' => 'NHẬP SỈ', 'default_desc' => 'Chính sách hấp dẫn, nguồn hàng ổn định'],
                ['image' => 'business_card3_image', 'title' => 'business_card3_title', 'desc' => 'business_card3_desc', 'default_img' => '/images/pic5.png', 'default_title' => 'BÁN LẺ', 'default_desc' => 'Thời trang hiện đại, chất lượng cao'],
            ];
        @endphp
        <div class="container px-lg-5">
            <div class="row g-4">
                @foreach($bCards as $card)
                    @php
                        $cardImg = get_setting($card['image'], $card['default_img']);
                        $cardSrc = str_starts_with($cardImg, 'settings/') ? asset('storage/' . $cardImg) : asset($cardImg);
                    @endphp
                    <div class="col-12 col-md-4">
                        <div class="card border-0 bg-transparent h-100">
                            <img src="{{ $cardSrc }}" alt="{{ get_setting($card['title'], $card['default_title']) }}"
                                class="card-img-top rounded-0 object-fit-cover business-card-img">
                            <div class="card-body px-0 pt-4 pb-0 d-flex flex-column">
                                <h5 class="card-title text-uppercase mb-2 business-card-title">{{ get_setting($card['title'], $card['default_title']) }}</h5>
                                <p class="card-text small mb-4 business-card-desc">{{ get_setting($card['desc'], $card['default_desc']) }}</p>
                                <div class="mt-auto">
                                    <a href="/hop-tac" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">
                                        Xem Thêm <i class="fa-solid fa-chevron-right ms-2 btn-icon-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                                <a href="/chi-tiet-san-pham/{{ $product->slug }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </a>
                            </div>
                            <h3 class="product-title text-truncate mb-1">
                                <a href="/chi-tiet-san-pham/{{ $product->slug }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                            </h3>
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
            <div class="text-center mt-5" id="load-more-wrapper" style="display: none;">
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
                @if($blogs->count() > 0)
                    <!-- Column 1 (Tall) -->
                    @if(isset($blogs[0]))
                    <div class="col-12 col-md-4">
                        <div class="blog-card h-100 d-flex flex-column">
                            <img src="{{ $blogs[0]->image_path ? asset('storage/' . $blogs[0]->image_path) : asset('images/product1.png') }}" alt="{{ $blogs[0]->title }}" class="blog-img-tall object-fit-cover">
                            <h5 class="blog-title text-uppercase">
                                <a href="{{ route('blog.detail', $blogs[0]->slug) }}" class="text-dark text-decoration-none">{{ $blogs[0]->title }}</a>
                            </h5>
                            <p class="blog-desc">{{ Str::limit($blogs[0]->excerpt, 80) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.detail', $blogs[0]->slug) }}" class="blog-link">Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Column 2 (Two Shorts) -->
                    <div class="col-12 col-md-4 d-flex flex-column gap-4">
                        @if(isset($blogs[1]))
                        <div class="blog-card flex-grow-1 d-flex flex-column">
                            <img src="{{ $blogs[1]->image_path ? asset('storage/' . $blogs[1]->image_path) : asset('images/product1.png') }}" alt="{{ $blogs[1]->title }}" class="blog-img-short object-fit-cover">
                            <h5 class="blog-title text-uppercase">
                                <a href="{{ route('blog.detail', $blogs[1]->slug) }}" class="text-dark text-decoration-none">{{ $blogs[1]->title }}</a>
                            </h5>
                            <p class="blog-desc">{{ Str::limit($blogs[1]->excerpt, 60) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.detail', $blogs[1]->slug) }}" class="blog-link">Chi Tiết</a>
                            </div>
                        </div>
                        @endif
                        @if(isset($blogs[2]))
                        <div class="blog-card flex-grow-1 d-flex flex-column">
                            <img src="{{ $blogs[2]->image_path ? asset('storage/' . $blogs[2]->image_path) : asset('images/product1.png') }}" alt="{{ $blogs[2]->title }}" class="blog-img-short object-fit-cover">
                            <h5 class="blog-title text-uppercase">
                                <a href="{{ route('blog.detail', $blogs[2]->slug) }}" class="text-dark text-decoration-none">{{ $blogs[2]->title }}</a>
                            </h5>
                            <p class="blog-desc">{{ Str::limit($blogs[2]->excerpt, 60) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.detail', $blogs[2]->slug) }}" class="blog-link">Chi Tiết</a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Column 3 (Tall) -->
                    @if(isset($blogs[3]))
                    <div class="col-12 col-md-4">
                        <div class="blog-card h-100 d-flex flex-column">
                            <img src="{{ $blogs[3]->image_path ? asset('storage/' . $blogs[3]->image_path) : asset('images/product1.png') }}" alt="{{ $blogs[3]->title }}" class="blog-img-tall object-fit-cover">
                            <h5 class="blog-title text-uppercase">
                                <a href="{{ route('blog.detail', $blogs[3]->slug) }}" class="text-dark text-decoration-none">{{ $blogs[3]->title }}</a>
                            </h5>
                            <p class="blog-desc">{{ Str::limit($blogs[3]->excerpt, 80) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.detail', $blogs[3]->slug) }}" class="blog-link">Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="col-12 text-center text-muted">
                        <p>Chưa có bài viết nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.category-tab');
        const products = document.querySelectorAll('.product-item');
        const loadMoreWrapper = document.getElementById('load-more-wrapper');
        const loadMoreBtn = document.querySelector('.btn-load-more');
        
        let currentCategoryId = null;
        let visibleCount = 8;
        
        function filterProducts(categoryId, resetCount = true) {
            if (resetCount) {
                visibleCount = 8;
                currentCategoryId = categoryId;
            }

            let totalInCategory = 0;
            let shownCount = 0;

            products.forEach(product => {
                if (product.dataset.categoryId === categoryId) {
                    totalInCategory++;
                    if (shownCount < visibleCount) {
                        product.style.display = 'block';
                        shownCount++;
                    } else {
                        product.style.display = 'none';
                    }
                } else {
                    product.style.display = 'none';
                }
            });
            
            // Hiện/ẩn nút xem thêm
            if (totalInCategory > visibleCount) {
                loadMoreWrapper.style.display = 'block';
            } else {
                loadMoreWrapper.style.display = 'none';
            }
        }

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function (e) {
                e.preventDefault();
                visibleCount += 8;
                filterProducts(currentCategoryId, false);
            });
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
                
                filterProducts(this.dataset.id, true);
            });
        });
    });
</script>
@endpush
