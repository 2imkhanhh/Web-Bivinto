@extends('layouts.app')

@section('title', 'Bivinto - ' . $blog->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css') }}">
    <style>
        .blog-detail-page {
            background-color: #FFFDF8;
        }
        
        .breadcrumb-custom {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }
        
        .breadcrumb-custom a {
            color: #777;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb-custom a:hover {
            color: #000;
        }

        .blog-header {
            width: 100%;
        }
        
        .blog-main-title {
            font-size: 2.25rem;
            line-height: 1.3;
            letter-spacing: -0.5px;
            font-weight: 600;
            color: #100F0F;
        }
        
        .blog-meta-info {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            border-top: 1px solid #E5E5E5;
            border-bottom: 1px solid #E5E5E5;
            padding: 15px 0;
            margin-top: 20px;
        }

        .blog-hero-img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .blog-content-wrapper {
            width: 100%;
            padding: 0;
        }

        .blog-content {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #222;
            font-weight: 400;
        }
        
        .blog-content p, 
        .blog-content span {
            background-color: transparent !important;
        }
        
        .blog-content p {
            margin-bottom: 1.5rem;
        }
        
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 30px auto;
            display: block;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .blog-content h2, .blog-content h3 {
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #100F0F;
        }
        
        .blog-content blockquote {
            border-left: 4px solid #100F0F;
            padding-left: 20px;
            font-style: italic;
            font-size: 1.3rem;
            color: #555;
            margin: 30px 0;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 8px 8px 0;
        }

        .related-blogs-section {
            background-color: #F5F5F5;
            padding: 80px 0;
        }
        
        .btn-back {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            transform: translateX(-5px);
        }
    </style>
@endpush

@section('content')
    <div class="blog-detail-page py-5">
        <div class="container">
            <!-- Header -->
            <div class="blog-header mb-4">
                <h1 class="blog-main-title">{{ $blog->title }}</h1>
                <div class="blog-meta-info d-flex justify-content-start align-items-center gap-4">
                    <span><i class="fa-regular fa-calendar me-2"></i>{{ $blog->created_at->format('d/m/Y') }}</span>
                </div>
                
                @if($blog->image_path)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="blog-hero-img w-100">
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="blog-content-wrapper">
                <div class="blog-content mb-5">
                    {!! $blog->content !!}
                </div>
                
                <!-- Share (Placeholder for aesthetics) -->
                <div class="d-flex justify-content-end align-items-center border-top border-bottom py-3 mb-5">
                    <div class="social-share">
                        <span class="fw-bold me-3">Chia sẻ:</span>
                        <a href="#" class="text-dark me-3"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="text-dark me-3"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="text-dark"><i class="fa-brands fa-pinterest-p"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Blogs -->
    @if($relatedBlogs->count() > 0)
    <div class="related-blogs-section">
        <div class="container">
            <h2 class="section-title fw-bold mb-3 text-center">BÀI VIẾT LIÊN QUAN</h2>
            <hr class="blogs-main-divider mb-5 mx-auto" style="width: 100px;">
            
            <div class="row gx-4 gx-lg-5 justify-content-center">
                @foreach($relatedBlogs as $related)
                <div class="col-12 col-md-4 mb-4">
                    <div class="article-card h-100 d-flex flex-column bg-white shadow-sm rounded overflow-hidden">
                        <a href="{{ route('blog.detail', $related->slug) }}" class="d-block mb-0">
                            <img src="{{ $related->image_path ? asset('storage/' . $related->image_path) : asset('images/article1.png') }}" class="card-img w-100 object-fit-cover" alt="{{ $related->title }}">
                        </a>
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <span class="blog-date mb-2 d-block">{{ $related->created_at->format('d/m/Y') }}</span>
                            <h3 class="card-title fw-medium mb-3">
                                <a href="{{ route('blog.detail', $related->slug) }}" class="text-dark text-decoration-none text-uppercase">{{ $related->title }}</a>
                            </h3>
                            <p class="blog-desc mb-0 flex-grow-1">
                                {{ Str::limit($related->excerpt, 100) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection
