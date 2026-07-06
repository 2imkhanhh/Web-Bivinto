@extends('layouts.app')

@section('title', 'Bivinto - Sản Phẩm')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-xl-5 py-4 mt-3 mt-xl-4">

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between align-items-center filter-bar-container flex-wrap gap-3">
            <div class="category-links">
                <a href="#" class="active filter-item" data-type="category" data-val="all">Tất cả</a>
                @foreach($categories as $category)
                    <a href="#" class="filter-item" data-type="category" data-val="{{ $category->slug }}">{{ $category->name }}</a>
                @endforeach
            </div>
            <div class="filter-dropdowns">
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="btn-text-size">Kích Cỡ</span>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item filter-item" href="#" data-type="size" data-val="all">Tất cả</a></li>
                    @foreach($allSizes as $sz)
                    <li><a class="dropdown-item filter-item" href="#" data-type="size" data-val="{{ $sz }}">{{ $sz }}</a></li>
                    @endforeach
                </ul>

                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="btn-text-color">Màu Sắc</span>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item filter-item" href="#" data-type="color" data-val="all">Tất cả</a></li>
                    @foreach($allColors as $clr)
                    <li><a class="dropdown-item filter-item" href="#" data-type="color" data-val="{{ $clr }}">{{ $clr }}</a></li>
                    @endforeach
                </ul>

                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="btn-text-price">Mức Giá</span>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item filter-item" href="#" data-type="price" data-val="all">Tất cả</a></li>
                    <li><a class="dropdown-item filter-item" href="#" data-type="price" data-val="under_500">Dưới 500.000đ</a></li>
                    <li><a class="dropdown-item filter-item" href="#" data-type="price" data-val="500_1000">500.000đ - 1.000.000đ</a></li>
                    <li><a class="dropdown-item filter-item" href="#" data-type="price" data-val="over_1000">Trên 1.000.000đ</a></li>
                </ul>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row gx-1 gy-5 mb-5" id="product-grid" style="transition: opacity 0.3s ease;">
            @include('partials.product_list', ['products' => $products])
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-5 mb-3 pb-3 pb-lg-5" id="load-more-container" style="{{ $products->hasMorePages() ? '' : 'display: none;' }}">
            <button id="btn-load-more" class="btn btn-dark rounded-pill px-5 py-2 fw-medium btn-load-more">
                <i class="fa-solid fa-plus me-2"></i> Xem Thêm
            </button>
        </div>

    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = {{ $products->currentPage() }};
    let filters = {
        category: 'all',
        size: 'all',
        color: 'all',
        price: 'all'
    };

    const productGrid = document.getElementById('product-grid');
    const loadMoreBtn = document.getElementById('btn-load-more');
    const loadMoreContainer = document.getElementById('load-more-container');

    // Filter click
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            const val = this.getAttribute('data-val');

            // Update active state for category links
            if (type === 'category') {
                document.querySelectorAll('.category-links .filter-item').forEach(el => el.classList.remove('active'));
                this.classList.add('active');
            } else {
                // Update dropdown text
                let textMapping = {
                    'size': 'Kích Cỡ',
                    'color': 'Màu Sắc',
                    'price': 'Mức Giá'
                };
                if (val !== 'all') {
                    document.getElementById('btn-text-' + type).innerText = this.innerText;
                } else {
                    document.getElementById('btn-text-' + type).innerText = textMapping[type];
                }
            }

            filters[type] = val;
            currentPage = 1;
            fetchProducts(true);
        });
    });

    if(loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentPage++;
            fetchProducts(false);
        });
    }

    function fetchProducts(reset) {
        // Build query string
        let url = new URL(window.location.href);
        url.searchParams.set('page', currentPage);
        Object.keys(filters).forEach(key => {
            if (filters[key] !== 'all') {
                url.searchParams.set(key, filters[key]);
            } else {
                url.searchParams.delete(key);
            }
        });

        // Add visual loading state
        if (reset) {
            productGrid.style.opacity = '0.5';
        } else {
            loadMoreBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải...';
            loadMoreBtn.disabled = true;
        }

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (reset) {
                productGrid.innerHTML = data.html;
                productGrid.style.opacity = '1';
                // Update URL without reloading
                window.history.pushState({}, '', url);
            } else {
                productGrid.insertAdjacentHTML('beforeend', data.html);
                loadMoreBtn.innerHTML = '<i class="fa-solid fa-plus me-2"></i> Xem Thêm';
                loadMoreBtn.disabled = false;
            }

            if (data.hasMore) {
                loadMoreContainer.style.display = 'block';
            } else {
                loadMoreContainer.style.display = 'none';
            }
            
            // Xử lý thông báo rỗng
            if (reset && data.html.trim() === '') {
                productGrid.innerHTML = '<div class="col-12 text-center py-5"><h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</h5></div>';
            }
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            if (reset) productGrid.style.opacity = '1';
            else {
                loadMoreBtn.innerHTML = '<i class="fa-solid fa-plus me-2"></i> Xem Thêm';
                loadMoreBtn.disabled = false;
            }
        });
    }
});
</script>
@endpush
