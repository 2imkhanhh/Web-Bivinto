@extends('layouts.app')

@section('title', 'Bivinto - Chi Tiết Sản Phẩm')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-xl-5 product-detail-container">
        <div class="row">

            <!-- Left: Gallery Section -->
            <div class="col-12 col-lg-6">
                <div class="product-gallery">
                    <div class="main-image-wrapper">
                        <!-- Main product image -->
                        <img id="main-product-img" src="" alt="{{ $product->name }}">
                    </div>

                    <!-- Thumbnails -->
                    <div class="thumbnail-list" id="thumbnail-list-container">
                        <!-- Injected via JS -->
                    </div>
                </div>
            </div>

            <!-- Right: Info Panel Section -->
            <div class="col-12 col-lg-6">
                <div class="product-info-panel">

                    <h1 class="detail-title">{{ $product->name }}</h1>
                    <div class="detail-status">Còn hàng</div>

                    <hr class="detail-divider">

                    <div class="detail-price">{{ number_format($product->price, 0, ',', '.') }}đ</div>

                    <!-- Color Picker -->
                    <div class="picker-section" id="color-picker-section">
                        <div class="picker-label">Màu Sắc: <span id="selected-color-label"></span></div>
                        <div class="color-swatches" id="color-swatches-container">
                            <!-- Injected via JS -->
                        </div>
                    </div>

                    <!-- Size Picker -->
                    <div class="picker-section mt-4" id="size-picker-section">
                        <div class="picker-label">Kích Cỡ: <span id="selected-size-label"></span></div>
                        <div class="size-options" id="size-options-container">
                            <!-- Injected via JS -->
                        </div>
                        <a href="#" class="size-guide-link mt-2 d-inline-block">
                            <i class="fa-solid fa-ruler-horizontal"></i> Hướng dẫn chọn size
                        </a>
                    </div>

                    <!-- Quantity and Actions -->
                    <div class="purchase-actions">
                        <div class="position-relative">
                            <div id="stock-remaining" class="text-muted mb-1 d-none position-absolute bottom-100 start-50 translate-middle-x w-100 text-center" style="font-size: 0.75rem;">Còn lại: <span></span></div>
                            <div class="quantity-selector">
                                <button class="quantity-btn" onclick="adjustQuantity(-1)">-</button>
                                <input type="text" inputmode="numeric" id="quantity-input" class="quantity-input" value="1"
                                    onchange="validateQuantity(this)">
                                <button class="quantity-btn" onclick="adjustQuantity(1)">+</button>
                            </div>
                        </div>
                        <button class="btn-add-cart">
                            <i class="fa-solid fa-plus"></i> Thêm Vào Giỏ Hàng
                        </button>
                        <button class="btn-buy-now">
                            Mua Ngay
                        </button>
                    </div>

                    <!-- Policies Box -->
                    <div class="policy-box">
                        <div class="policy-item">
                            <i class="fa-solid fa-truck-fast policy-icon"></i>
                            <div class="policy-text">Giao hàng nội thành Hà Nội từ 25.000đ trong 24h</div>
                        </div>
                        <div class="policy-item">
                            <i class="fa-solid fa-medal policy-icon"></i>
                            <div class="policy-text">Tích điểm đến 12% giá trị đơn hàng cho mỗi lần mua</div>
                        </div>
                        <div class="policy-item">
                            <i class="fa-solid fa-gift policy-icon"></i>
                            <div class="policy-text">Giảm đến 20% tổng hoá đơn khi mua hàng tại cửa hàng vào tháng sinh
                                nhật</div>
                        </div>
                        <div class="policy-item">
                            <i class="fa-solid fa-rotate-left policy-icon"></i>
                            <div class="policy-text">Đổi hàng trong vòng 30 ngày</div>
                        </div>
                    </div>

                    <!-- Accordions (Mô tả & Hỗ trợ) -->
                    <div class="detail-accordion">
                        <button class="accordion-trigger" onclick="toggleAccordion(this)">
                            Mô Tả <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="accordion-panel">
                            <div class="accordion-body">
                                @if($product->description)
                                    <ul>
                                        @foreach(explode("\n", $product->description) as $line)
                                            @if(trim($line) !== '')
                                                <li>{{ ltrim(trim($line), '-* ') }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Chưa có mô tả cho sản phẩm này.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-accordion">
                        <button class="accordion-trigger" onclick="toggleAccordion(this)">
                            Hỗ Trợ? <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="accordion-panel">
                            <div class="accordion-body">
                                <ul>
                                    <li>Hướng dẫn bảo quản: Giặt tay hoặc giặt máy nhẹ, không dùng chất tẩy mạnh, ủi ở nhiệt
                                        độ thấp.</li>
                                    <li>Chính sách bảo hành: Đổi mới miễn phí trong trường hợp phát hiện lỗi từ nhà sản
                                        xuất.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Similar Products Section -->
        <div class="row mt-5 pt-5">
            <div class="col-12">
                <h2 class="similar-products-title">Sản phẩm tương tự</h2>

                <div class="similar-slider-container">
                    @if($similarProducts->count() > 0)
                        <!-- Left arrow -->
                        <button class="slider-arrow slider-arrow-left" onclick="slideProducts(-1)">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                    @endif

                    <!-- Track -->
                    <div class="similar-slider-track" id="similar-slider">
                        @forelse($similarProducts as $simProduct)
                            @php
                                $simPrimaryImage = $simProduct->images->where('is_primary', true)->first();
                                $simImagePath = $simPrimaryImage ? $simPrimaryImage->image_path : ($simProduct->images->first() ? $simProduct->images->first()->image_path : null);
                                $simImageUrl = $simImagePath ? asset('storage/' . $simImagePath) : asset('images/product1.png');
                            @endphp
                            <div class="similar-slider-item">
                                <div class="product-card h-100 d-flex flex-column">
                                    <div class="product-img-wrapper mb-3">
                                        <img src="{{ $simImageUrl }}" alt="{{ $simProduct->name }}"
                                            class="img-fluid w-100 object-fit-cover product-img" style="object-position: top;">
                                    </div>
                                    <h3 class="product-title text-truncate mb-1">{{ $simProduct->name }}</h3>
                                    <p class="product-price fw-bold mb-3">{{ number_format($simProduct->price, 0, ',', '.') }}đ</p>
                                    <div class="mt-auto">
                                        <a href="/chi-tiet-san-pham/{{ $simProduct->slug }}"
                                            class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                            <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-100 text-center py-4 text-muted">Không có sản phẩm tương tự.</div>
                        @endforelse
                    </div>

                    @if($similarProducts->count() > 0)
                        <!-- Right arrow -->
                        <button class="slider-arrow slider-arrow-right" onclick="slideProducts(1)">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const product = @json($product);
        let selectedColor = null;
        let selectedSize = null;

        function renderColors() {
            const container = document.getElementById('color-swatches-container');
            const label = document.getElementById('selected-color-label');
            container.innerHTML = '';
            
            if (product.colors && product.colors.length > 0) {
                if (!selectedColor) {
                    selectedColor = product.colors[0];
                }
                label.innerText = selectedColor.color_name;

                product.colors.forEach(color => {
                    const btn = document.createElement('button');
                    btn.className = 'color-btn ' + (selectedColor.id === color.id ? 'active' : '');
                    btn.onclick = () => selectColor(color);
                    
                    const span = document.createElement('span');
                    span.className = 'color-fill';
                    span.style.backgroundColor = color.color_code;
                    
                    btn.appendChild(span);
                    container.appendChild(btn);
                });
            } else {
                label.innerText = 'Không có màu sắc';
            }
        }

        function selectColor(color) {
            selectedColor = color;
            renderColors();
            renderSizes();
            renderImages();
        }

        function renderSizes() {
            const container = document.getElementById('size-options-container');
            const label = document.getElementById('selected-size-label');
            container.innerHTML = '';
            
            if (selectedColor && selectedColor.sizes && selectedColor.sizes.length > 0) {
                // Filter out out-of-stock sizes
                const availableSizes = selectedColor.sizes.filter(s => s.stock > 0);
                
                if (availableSizes.length > 0) {
                    // if currently selected size is not in the new available list, select the first available
                    if (!selectedSize || !availableSizes.find(s => s.id === selectedSize.id)) {
                        selectedSize = availableSizes[0];
                    }
                    label.innerText = selectedSize.size_name;
                    
                    availableSizes.forEach(size => {
                        const btn = document.createElement('button');
                        btn.className = 'size-btn ' + (selectedSize.id === size.id ? 'active' : '');
                        btn.innerText = size.size_name;
                        btn.onclick = () => selectSize(size);
                        container.appendChild(btn);
                    });
                    
                    // Update stock display
                    const stockEl = document.getElementById('stock-remaining');
                    if (selectedSize) {
                        stockEl.classList.remove('d-none');
                        stockEl.querySelector('span').innerText = selectedSize.stock;
                        validateQuantity(document.getElementById('quantity-input')); // Re-validate qty when size changes
                    } else {
                        stockEl.classList.add('d-none');
                    }
                    
                } else {
                    selectedSize = null;
                    label.innerText = 'Hết hàng';
                    container.innerHTML = '<div class="text-danger mt-2 fw-medium"><i class="fa-solid fa-circle-exclamation"></i> Màu này hiện tại đã hết size.</div>';
                    document.getElementById('stock-remaining').classList.add('d-none');
                }
            } else {
                selectedSize = null;
                label.innerText = 'Không có kích cỡ';
                document.getElementById('stock-remaining').classList.add('d-none');
            }
        }

        function selectSize(size) {
            selectedSize = size;
            renderSizes();
        }

        function renderImages() {
            const container = document.getElementById('thumbnail-list-container');
            const mainImg = document.getElementById('main-product-img');
            container.innerHTML = '';
            
            let images = [];
            if (selectedColor && selectedColor.images && selectedColor.images.length > 0) {
                images = selectedColor.images;
            } else if (product.images && product.images.length > 0) {
                images = product.images; // fallback
            }

            if (images.length > 0) {
                // put primary first
                images.sort((a, b) => (b.is_primary ? 1 : 0) - (a.is_primary ? 1 : 0));
                
                const mainUrl = '/storage/' + images[0].image_path;
                changeProductImage(mainUrl, null, false);
                
                images.forEach((img, index) => {
                    const imgUrl = '/storage/' + img.image_path;
                    const thumb = document.createElement('div');
                    thumb.className = 'thumbnail-item ' + (index === 0 ? 'active' : '');
                    thumb.onclick = function() {
                        changeProductImage(imgUrl, this, true);
                    };
                    
                    const imgEl = document.createElement('img');
                    imgEl.src = imgUrl;
                    imgEl.alt = 'Thumbnail';
                    imgEl.style.objectPosition = 'top';
                    imgEl.style.objectFit = 'cover';
                    
                    thumb.appendChild(imgEl);
                    container.appendChild(thumb);
                });
            } else {
                changeProductImage('{{ asset("images/product1.png") }}', null, false);
            }
        }

        // Change main product image
        function changeProductImage(imgUrl, el, animate) {
            const mainImg = document.getElementById('main-product-img');
            
            if (animate) {
                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = imgUrl;
                    mainImg.style.opacity = '1';
                }, 250);
            } else {
                mainImg.src = imgUrl;
                mainImg.style.opacity = '1';
            }

            if (el) {
                document.querySelectorAll('.thumbnail-item').forEach(item => {
                    item.classList.remove('active');
                });
                el.classList.add('active');
            }
        }

        // Adjust quantity selector
        function adjustQuantity(amount) {
            const input = document.getElementById('quantity-input');
            let currentVal = parseInt(input.value);
            if (isNaN(currentVal)) currentVal = 1;
            currentVal += amount;
            
            // Validate limits
            let maxStock = selectedSize ? selectedSize.stock : 1;
            if (currentVal < 1) currentVal = 1;
            if (currentVal > maxStock) currentVal = maxStock;
            
            input.value = currentVal;
        }

        // Validate quantity input on manual entry
        function validateQuantity(input) {
            let val = parseInt(input.value);
            let maxStock = selectedSize ? selectedSize.stock : 1;
            
            if (isNaN(val) || val < 1) {
                input.value = 1;
            } else if (val > maxStock) {
                input.value = maxStock;
            } else {
                input.value = val;
            }
        }

        // Toggle details accordion
        function toggleAccordion(el) {
            const panel = el.nextElementSibling;

            // Toggle active indicator
            el.classList.toggle('active');
            panel.classList.toggle('show');

            if (panel.classList.contains('show')) {
                panel.style.maxHeight = panel.scrollHeight + "px";
            } else {
                panel.style.maxHeight = "0px";
            }
        }

        // Similar products slider scrolling
        function slideProducts(direction) {
            const track = document.getElementById('similar-slider');
            const scrollAmount = track.clientWidth * 0.8; // scroll 80% width of slider view
            track.scrollBy({
                left: scrollAmount * direction,
                behavior: 'smooth'
            });
        }

        // Set initial state on window load
        window.addEventListener('DOMContentLoaded', () => {
            renderColors();
            renderSizes();
            renderImages();
            
            document.querySelectorAll('.accordion-panel.show').forEach(panel => {
                panel.style.maxHeight = panel.scrollHeight + "px";
            });
        });
    </script>
@endpush
