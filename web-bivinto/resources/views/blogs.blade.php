@extends('layouts.app')

@section('title', 'Bivinto - Blogs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css') }}">
@endpush

@section('content')
    <div class="blogs-page pt-5">
        <div class="container mb-5">
            <h1 class="section-title fw-bold mb-3">BÀI VIẾT NỔI BẬT</h1>
            <hr class="blogs-main-divider mb-5">

            <!-- Featured Article -->
            <div class="row gx-5 mb-5 align-items-stretch">
                <!-- Featured Image -->
                <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                    <a href="#" class="d-block h-100">
                        <img src="{{ asset('images/article1.png') }}" alt="Da ngăm mặc màu gì"
                            class="img-fluid w-100 object-fit-cover featured-img">
                    </a>
                </div>
                <!-- Featured Content -->
                <div class="col-12 col-lg-4 d-flex flex-column justify-content-start pt-lg-2">
                    <span class="blog-date mb-2">12/06/2026</span>
                    <h2 class="featured-title fw-medium mb-3">
                        <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG DA
                            CHO NAM</a>
                    </h2>
                    <p class="blog-desc">
                        Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể hiện sự
                        tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                    </p>
                </div>
            </div>

            <!-- Blogs Grid -->
            <div class="row gx-4 gx-lg-5">
                <!-- Column 1: List of small articles -->
                <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex flex-column justify-content-between">
                    <div class="small-article">
                        <span class="blog-date mb-2 d-block">12/06/2026</span>
                        <h3 class="small-article-title fw-medium">
                            <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG
                                DA CHO NAM</a>
                        </h3>
                    </div>

                    <div class="small-article">
                        <span class="blog-date mb-2 d-block">12/06/2026</span>
                        <h3 class="small-article-title fw-medium">
                            <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG
                                DA CHO NAM</a>
                        </h3>
                    </div>

                    <div class="small-article no-border">
                        <span class="blog-date mb-2 d-block">12/06/2026</span>
                        <h3 class="small-article-title fw-medium mb-2">
                            <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG
                                DA CHO NAM</a>
                        </h3>
                        <p class="blog-desc mb-0">
                            Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể hiện
                            sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                        </p>
                    </div>
                </div>

                <!-- Column 2: Article Card -->
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="article-card">
                        <a href="#" class="d-block mb-3">
                            <img src="{{ asset('images/article2.png') }}" alt="Article 2"
                                class="img-fluid w-100 object-fit-cover card-img">
                        </a>
                        <span class="blog-date mb-2 d-block">12/06/2026</span>
                        <h3 class="card-title fw-medium mb-2">
                            <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG
                                DA CHO NAM</a>
                        </h3>
                        <p class="blog-desc mb-0">
                            Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể hiện
                            sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                        </p>
                    </div>
                </div>

                <!-- Column 3: Article Card -->
                <div class="col-12 col-md-4">
                    <div class="article-card">
                        <a href="#" class="d-block mb-3">
                            <img src="{{ asset('images/article3.png') }}" alt="Article 3"
                                class="img-fluid w-100 object-fit-cover card-img">
                        </a>
                        <span class="blog-date mb-2 d-block">12/06/2026</span>
                        <h3 class="card-title fw-medium mb-2">
                            <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ SÁNG
                                DA CHO NAM</a>
                        </h3>
                        <p class="blog-desc mb-0">
                            Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể hiện
                            sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Section: BIVINTO CÓ GÌ MỚI -->
        <section class="news-section py-5" style="background-color: #F5F5F5;">
            <div class="container">
                <h2 class="section-title fw-bold mb-3">BIVINTO CÓ GÌ MỚI?</h2>
                <hr class="blogs-main-divider mb-5">

                <!-- Staggered Layout -->
                <div class="row gx-5 mb-5 align-items-stretch">
                    <!-- Column 1 (Text then Image) -->
                    <div class="col-12 col-md-6 mb-5 mb-md-0 d-flex flex-column">
                        <div class="mb-4">
                            <span class="blog-date mb-2 d-block">12/06/2026</span>
                            <h3 class="card-title fw-medium mb-3">
                                <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ
                                    SÁNG DA CHO NAM</a>
                            </h3>
                            <p class="blog-desc">
                                Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể
                                hiện sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                            </p>
                        </div>
                        <a href="#" class="d-block flex-grow-1">
                            <img src="{{ asset('images/article4.png') }}" alt="News 1"
                                class="img-fluid w-100 object-fit-cover staggered-img h-100">
                        </a>
                    </div>

                    <!-- Column 2 (Image then Text) -->
                    <div class="col-12 col-md-6 d-flex flex-column">
                        <a href="#" class="d-block flex-grow-1 mb-4">
                            <img src="{{ asset('images/article5.png') }}" alt="News 2"
                                class="img-fluid w-100 object-fit-cover staggered-img h-100">
                        </a>
                        <div>
                            <span class="blog-date mb-2 d-block">12/06/2026</span>
                            <h3 class="card-title fw-medium mb-3">
                                <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ
                                    SÁNG DA CHO NAM</a>
                            </h3>
                            <p class="blog-desc mb-0">
                                Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể
                                hiện sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3 Columns Grid -->
                <div class="row gx-4 gx-lg-5">
                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                        <div class="article-card">
                            <a href="#" class="d-block mb-3">
                                <img src="{{ asset('images/article6.png') }}" alt="News 3"
                                    class="img-fluid w-100 object-fit-cover card-img">
                            </a>
                            <span class="blog-date mb-2 d-block">12/06/2026</span>
                            <h3 class="card-title fw-medium mb-2">
                                <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ
                                    SÁNG DA CHO NAM</a>
                            </h3>
                            <p class="blog-desc mb-0">
                                Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể
                                hiện sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                        <div class="article-card">
                            <a href="#" class="d-block mb-3">
                                <img src="{{ asset('images/article7.png') }}" alt="News 4"
                                    class="img-fluid w-100 object-fit-cover card-img">
                            </a>
                            <span class="blog-date mb-2 d-block">12/06/2026</span>
                            <h3 class="card-title fw-medium mb-2">
                                <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ
                                    SÁNG DA CHO NAM</a>
                            </h3>
                            <p class="blog-desc mb-0">
                                Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể
                                hiện sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="article-card">
                            <a href="#" class="d-block mb-3">
                                <img src="{{ asset('images/article8.png') }}" alt="News 5"
                                    class="img-fluid w-100 object-fit-cover card-img">
                            </a>
                            <span class="blog-date mb-2 d-block">12/06/2026</span>
                            <h3 class="card-title fw-medium mb-2">
                                <a href="#" class="text-dark text-decoration-none">DA NGĂM MẶC MÀU GÌ? CÁCH CHỌN ĐỒ
                                    SÁNG DA CHO NAM</a>
                            </h3>
                            <p class="blog-desc mb-0">
                                Lựa chọn trang phục đi đám cưới cho nam như thế nào để vừa lịch thiệp, phong cách, vừa thể
                                hiện sự tôn trọng đối với cặp đôi trong ngày trọng đại? Đây chắc...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-5 mb-3">
                    <a href="#" class="btn btn-dark rounded-pill px-5 py-2 fw-medium btn-load-more">
                        <i class="fa-solid fa-plus me-2"></i> Xem Thêm
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
