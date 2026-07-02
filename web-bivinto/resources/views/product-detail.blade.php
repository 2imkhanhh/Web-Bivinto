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
                        <!-- Main product image (Product 3 image) -->
                        <img id="main-product-img" src="{{ asset('images/product3.png') }}" alt="Áo Sơ Mi Cộc Tay Cổ Đức">
                    </div>

                    <!-- Thumbnails (6 items) -->
                    <div class="thumbnail-list">
                        <div class="thumbnail-item active"
                            onclick="changeProductImage('{{ asset('images/product3.png') }}', this)">
                            <img src="{{ asset('images/product3.png') }}" alt="Thumbnail 1">
                        </div>
                        <div class="thumbnail-item"
                            onclick="changeProductImage('{{ asset('images/product1.png') }}', this)">
                            <img src="{{ asset('images/product1.png') }}" alt="Thumbnail 2">
                        </div>
                        <div class="thumbnail-item"
                            onclick="changeProductImage('{{ asset('images/product2.png') }}', this)">
                            <img src="{{ asset('images/product2.png') }}" alt="Thumbnail 3">
                        </div>
                        <div class="thumbnail-item"
                            onclick="changeProductImage('{{ asset('images/product4.png') }}', this)">
                            <img src="{{ asset('images/product4.png') }}" alt="Thumbnail 4">
                        </div>
                        <div class="thumbnail-item"
                            onclick="changeProductImage('{{ asset('images/product1.png') }}', this)">
                            <img src="{{ asset('images/product1.png') }}" alt="Thumbnail 5">
                        </div>
                        <div class="thumbnail-item"
                            onclick="changeProductImage('{{ asset('images/product2.png') }}', this)">
                            <img src="{{ asset('images/product2.png') }}" alt="Thumbnail 6">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Info Panel Section -->
            <div class="col-12 col-lg-6">
                <div class="product-info-panel">

                    <h1 class="detail-title">Áo Sơ Mi Cộc Tay Cổ Đức</h1>
                    <div class="detail-status">Còn hàng</div>

                    <hr class="detail-divider">

                    <div class="detail-price">850.000đ</div>

                    <!-- Color Picker -->
                    <div class="picker-section">
                        <div class="picker-label">Màu Sắc: <span id="selected-color-label">Trắng</span></div>
                        <div class="color-swatches">
                            <button class="color-btn active" onclick="changeColor('Trắng', this)">
                                <span class="color-fill bg-white-swatch"></span>
                            </button>
                            <button class="color-btn" onclick="changeColor('Xám', this)">
                                <span class="color-fill bg-gray-swatch"></span>
                            </button>
                            <button class="color-btn" onclick="changeColor('Xanh dương', this)">
                                <span class="color-fill bg-blue-swatch"></span>
                            </button>
                            <button class="color-btn" onclick="changeColor('Xanh lá', this)">
                                <span class="color-fill bg-green-swatch"></span>
                            </button>
                            <button class="color-btn" onclick="changeColor('Đen', this)">
                                <span class="color-fill bg-black-swatch"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Size Picker -->
                    <div class="picker-section mt-4">
                        <div class="picker-label">Kích Cỡ: <span id="selected-size-label">M</span></div>
                        <div class="size-options">
                            <button class="size-btn" onclick="changeSize('XS', this)">XS</button>
                            <button class="size-btn" onclick="changeSize('S', this)">S</button>
                            <button class="size-btn active" onclick="changeSize('M', this)">M</button>
                            <button class="size-btn" onclick="changeSize('L', this)">L</button>
                            <button class="size-btn" onclick="changeSize('X', this)">X</button>
                            <button class="size-btn" onclick="changeSize('XXL', this)">XXL</button>
                        </div>
                        <a href="#" class="size-guide-link">
                            <i class="fa-solid fa-ruler-horizontal"></i> Hướng dẫn chọn size
                        </a>
                    </div>

                    <!-- Quantity and Actions -->
                    <div class="purchase-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn" onclick="adjustQuantity(-1)">-</button>
                            <input type="text" id="quantity-input" class="quantity-input" value="1"
                                onchange="validateQuantity(this)">
                            <button class="quantity-btn" onclick="adjustQuantity(1)">+</button>
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
                                <ul>
                                    <li>Chất liệu: 67% Polyester, 29% Rayon, 4% Spandex. Vải mềm mại, thoáng khí, giữ form
                                        tốt</li>
                                    <li>Kiểu dáng: quần jean nam form carrot gọn gàng, hiện đại, năng động</li>
                                    <li>Chi tiết: thiết kế tối giản, phù hợp đi làm, đi chơi, dạo phố</li>
                                </ul>
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
                    <!-- Left arrow -->
                    <button class="slider-arrow slider-arrow-left" onclick="slideProducts(-1)">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <!-- Track -->
                    <div class="similar-slider-track" id="similar-slider">
                        <!-- Product 1 -->
                        <div class="similar-slider-item">
                            <div class="product-card">
                                <div class="product-img-wrapper mb-3">
                                    <img src="{{ asset('images/product1.png') }}" alt="Áo Sơ Mi"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </div>
                                <div class="product-size">Size: XS - XXL</div>
                                <h3 class="product-title text-truncate mb-1">Áo Sơ Mi Cộc Tay Cổ Đức</h3>
                                <p class="product-price fw-bold mb-3">850.000đ</p>
                                <div>
                                    <a href="#"
                                        class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                        <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="similar-slider-item">
                            <div class="product-card">
                                <div class="product-img-wrapper mb-3">
                                    <img src="{{ asset('images/product2.png') }}" alt="Áo Sơ Mi"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </div>
                                <div class="product-size">Size: M - XXL</div>
                                <h3 class="product-title text-truncate mb-1">Áo Sơ Mi Cộc Tay Cổ Đức</h3>
                                <p class="product-price fw-bold mb-3">850.000đ</p>
                                <div>
                                    <a href="#"
                                        class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                        <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="similar-slider-item">
                            <div class="product-card">
                                <div class="product-img-wrapper mb-3">
                                    <img src="{{ asset('images/product3.png') }}" alt="Áo Sơ Mi"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </div>
                                <div class="product-size">Size: XS - XXL</div>
                                <h3 class="product-title text-truncate mb-1">Áo Sơ Mi Cộc Tay Cổ Đức</h3>
                                <p class="product-price fw-bold mb-3">850.000đ</p>
                                <div>
                                    <a href="#"
                                        class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                        <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="similar-slider-item">
                            <div class="product-card">
                                <div class="product-img-wrapper mb-3">
                                    <img src="{{ asset('images/product4.png') }}" alt="Áo Sơ Mi"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </div>
                                <div class="product-size">Size: XS - XXL</div>
                                <h3 class="product-title text-truncate mb-1">Áo Sơ Mi Cộc Tay Cổ Đức</h3>
                                <p class="product-price fw-bold mb-3">850.000đ</p>
                                <div>
                                    <a href="#"
                                        class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                        <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="similar-slider-item">
                            <div class="product-card">
                                <div class="product-img-wrapper mb-3">
                                    <img src="{{ asset('images/product4.png') }}" alt="Áo Sơ Mi"
                                        class="img-fluid w-100 object-fit-cover product-img">
                                </div>
                                <div class="product-size">Size: XS - XXL</div>
                                <h3 class="product-title text-truncate mb-1">Áo Sơ Mi Cộc Tay Cổ Đức</h3>
                                <p class="product-price fw-bold mb-3">850.000đ</p>
                                <div>
                                    <a href="#"
                                        class="btn btn-outline-dark rounded-pill px-3 py-1 fw-medium btn-sm">Xem Chi Tiết
                                        <i class="fa-solid fa-chevron-right ms-1 btn-icon-xs"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right arrow -->
                    <button class="slider-arrow slider-arrow-right" onclick="slideProducts(1)">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Change main product image
        function changeProductImage(imgUrl, el) {
            const mainImg = document.getElementById('main-product-img');
            mainImg.style.opacity = '0';

            // Wait for transition before swapping source
            setTimeout(() => {
                mainImg.src = imgUrl;
                mainImg.style.opacity = '1';
            }, 250);

            // Toggle active class on thumbnails
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active');
            });
            el.classList.add('active');
        }

        // Change selected color text label
        function changeColor(colorName, el) {
            document.getElementById('selected-color-label').innerText = colorName;

            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            el.classList.add('active');
        }

        // Change selected size text label
        function changeSize(sizeName, el) {
            document.getElementById('selected-size-label').innerText = sizeName;

            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            el.classList.add('active');
        }

        // Adjust quantity selector
        function adjustQuantity(amount) {
            const input = document.getElementById('quantity-input');
            let currentVal = parseInt(input.value);
            if (isNaN(currentVal)) currentVal = 1;
            currentVal += amount;
            if (currentVal < 1) currentVal = 1;
            input.value = currentVal;
        }

        // Validate quantity input on manual entry
        function validateQuantity(input) {
            let val = parseInt(input.value);
            if (isNaN(val) || val < 1) {
                input.value = 1;
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

        // Set initial heights for open accordions on window load
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.accordion-panel.show').forEach(panel => {
                panel.style.maxHeight = panel.scrollHeight + "px";
            });
        });
    </script>
@endpush
