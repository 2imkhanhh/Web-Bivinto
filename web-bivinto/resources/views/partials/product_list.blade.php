@foreach ($products as $product)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="product-card">
            <div class="product-img-wrapper mb-3">
                @php
                    $primaryImage = $product->images->where('is_primary', true)->first();
                    $imagePath = $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('images/product-placeholder.png');
                @endphp
                <a href="{{ url('/chi-tiet-san-pham/' . $product->slug) }}">
                    <img src="{{ $imagePath }}" alt="{{ $product->name }}"
                        class="img-fluid w-100 object-fit-cover product-img">
                </a>
            </div>
            <div class="product-size">
                @php
                    $sizes = [];
                    foreach ($product->colors as $color) {
                        foreach ($color->sizes as $size) {
                            $sizes[] = $size->size_name;
                        }
                    }
                    $uniqueSizes = array_unique($sizes);
                    // Có thể sắp xếp size
                @endphp
                @if(count($uniqueSizes) > 0)
                    Size: {{ implode(', ', $uniqueSizes) }}
                @else
                    Freesize
                @endif
            </div>
            <h3 class="product-title text-truncate mb-1" title="{{ $product->name }}">
                <a href="{{ url('/chi-tiet-san-pham/' . $product->slug) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
            </h3>
            <p class="product-price fw-bold mb-3">{{ number_format($product->price, 0, ',', '.') }}đ</p>
            <div>
                <a href="{{ url('/chi-tiet-san-pham/' . $product->slug) }}" class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium">Xem Chi Tiết <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
            </div>
        </div>
    </div>
@endforeach
