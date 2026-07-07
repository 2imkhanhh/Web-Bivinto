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

            @if($featuredBlogs->count() > 0)
            <!-- Featured Article -->
            <div class="row gx-5 mb-5 align-items-stretch">
                <!-- Featured Image -->
                <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                    <a href="{{ route('blog.detail', $featuredBlogs[0]->slug) }}" class="d-block h-100">
                        <img src="{{ $featuredBlogs[0]->image_path ? asset('storage/' . $featuredBlogs[0]->image_path) : asset('images/article1.png') }}" alt="{{ $featuredBlogs[0]->title }}"
                            class="img-fluid w-100 object-fit-cover featured-img h-100 rounded">
                    </a>
                </div>
                <!-- Featured Content -->
                <div class="col-12 col-lg-4 d-flex flex-column justify-content-start pt-lg-2">
                    <span class="blog-date mb-2">{{ $featuredBlogs[0]->created_at->format('d/m/Y') }}</span>
                    <h2 class="featured-title fw-medium mb-3">
                        <a href="{{ route('blog.detail', $featuredBlogs[0]->slug) }}" class="text-dark text-decoration-none text-uppercase">{{ $featuredBlogs[0]->title }}</a>
                    </h2>
                    <p class="blog-desc">
                        {{ Str::limit($featuredBlogs[0]->excerpt, 150) }}
                    </p>
                </div>
            </div>

            <!-- Blogs Grid -->
            <div class="row gx-4 gx-lg-5">
                <!-- Column 1: List of small articles -->
                <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex flex-column justify-content-between">
                    @foreach($featuredBlogs->skip(1)->take(3) as $key => $blog)
                        <div class="small-article {{ $loop->last ? 'no-border' : '' }}">
                            <span class="blog-date mb-2 d-block">{{ $blog->created_at->format('d/m/Y') }}</span>
                            <h3 class="small-article-title fw-medium {{ $loop->last ? 'mb-2' : '' }}">
                                <a href="{{ route('blog.detail', $blog->slug) }}" class="text-dark text-decoration-none text-uppercase">{{ $blog->title }}</a>
                            </h3>
                            @if($loop->last)
                            <p class="blog-desc mb-0">
                                {{ Str::limit($blog->excerpt, 100) }}
                            </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Column 2 & 3: Article Card -->
                @foreach($featuredBlogs->skip(4)->take(2) as $blog)
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="article-card">
                        <a href="{{ route('blog.detail', $blog->slug) }}" class="d-block mb-3">
                            <img src="{{ $blog->image_path ? asset('storage/' . $blog->image_path) : asset('images/article2.png') }}" alt="{{ $blog->title }}"
                                class="img-fluid w-100 object-fit-cover card-img rounded">
                        </a>
                        <span class="blog-date mb-2 d-block">{{ $blog->created_at->format('d/m/Y') }}</span>
                        <h3 class="card-title fw-medium mb-2">
                            <a href="{{ route('blog.detail', $blog->slug) }}" class="text-dark text-decoration-none text-uppercase">{{ $blog->title }}</a>
                        </h3>
                        <p class="blog-desc mb-0">
                            {{ Str::limit($blog->excerpt, 120) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <div class="alert alert-light text-center py-5">Chưa có bài viết nổi bật.</div>
            @endif
        </div>

        <!-- New Section: BIVINTO CÓ GÌ MỚI -->
        <section class="news-section py-5" style="background-color: #F5F5F5;">
            <div class="container">
                <h2 class="section-title fw-bold mb-3">BIVINTO CÓ GÌ MỚI?</h2>
                <hr class="blogs-main-divider mb-5">

                @if($latestBlogs->count() > 0)
                <div class="row gx-4 gx-lg-5">
                    @foreach($latestBlogs as $blog)
                    <div class="col-12 col-md-4 mb-4">
                        <div class="article-card h-100 d-flex flex-column">
                            <a href="{{ route('blog.detail', $blog->slug) }}" class="d-block mb-3">
                                <img src="{{ $blog->image_path ? asset('storage/' . $blog->image_path) : asset('images/article6.png') }}" alt="{{ $blog->title }}"
                                    class="img-fluid w-100 object-fit-cover card-img rounded" style="height: 250px;">
                            </a>
                            <span class="blog-date mb-2 d-block">{{ $blog->created_at->format('d/m/Y') }}</span>
                            <h3 class="card-title fw-medium mb-2">
                                <a href="{{ route('blog.detail', $blog->slug) }}" class="text-dark text-decoration-none text-uppercase">{{ $blog->title }}</a>
                            </h3>
                            <p class="blog-desc mb-4 flex-grow-1">
                                {{ Str::limit($blog->excerpt, 150) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.detail', $blog->slug) }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium btn-sm">
                                    Đọc Tiếp <i class="fa-solid fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($latestBlogs->hasPages())
                <div class="d-flex justify-content-center mt-5 mb-3 custom-pagination">
                    {{ $latestBlogs->links('pagination::bootstrap-5') }}
                </div>
                @endif
                
                @else
                <div class="alert alert-light text-center py-5">Chưa có bài viết mới.</div>
                @endif
            </div>
        </section>
    </div>
@endsection
