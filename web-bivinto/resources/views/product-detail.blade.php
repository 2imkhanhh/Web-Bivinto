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
                    <div class="position-relative">
                        <div class="thumbnail-list" id="thumbnail-list-container" style="overflow-x: auto; scroll-behavior: smooth; white-space: nowrap; display: flex; gap: 10px; scrollbar-width: none;">
                            <!-- Injected via JS -->
                        </div>
                        <button class="slider-arrow slider-arrow-left d-none" id="thumb-arrow-left" onclick="slideThumbnails(-1)" style="position: absolute; top: 50%; left: -15px; transform: translateY(-50%); z-index: 10; width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button class="slider-arrow slider-arrow-right d-none" id="thumb-arrow-right" onclick="slideThumbnails(1)" style="position: absolute; top: 50%; right: -15px; transform: translateY(-50%); z-index: 10; width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
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
                        <button class="btn-add-cart" id="btn-add-cart-action">
                            <i class="fa-solid fa-plus"></i> Thêm Vào Giỏ Hàng
                        </button>
                        <button type="button" class="btn-buy-now" id="btn-buy-now-action">
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
        let allImages = [];

        function initImages() {
            if (product.images) {
                allImages = [...product.images];
            }
            if (product.colors) {
                product.colors.forEach(color => {
                    if (color.images) {
                        color.images.forEach(img => {
                            if (!allImages.find(i => i.id === img.id)) {
                                allImages.push(img);
                            }
                        });
                    }
                });
            }
            // Đưa ảnh đại diện lên đầu
            allImages.sort((a, b) => (b.is_primary ? 1 : 0) - (a.is_primary ? 1 : 0));
        }

        function renderColors() {
            const container = document.getElementById('color-swatches-container');
            const label = document.getElementById('selected-color-label');
            container.innerHTML = '';
            
            if (product.colors && product.colors.length > 0) {
                label.innerText = selectedColor ? selectedColor.color_name : 'Vui lòng chọn màu';

                product.colors.forEach(color => {
                    const btn = document.createElement('button');
                    btn.className = 'color-btn ' + (selectedColor && selectedColor.id === color.id ? 'active' : '');
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
            
            // Cố gắng giữ lại size đã chọn nếu size đó có sẵn trong màu mới
            const oldSizeName = selectedSize ? selectedSize.size_name : null;
            selectedSize = null;
            if (oldSizeName && color.sizes) {
                const match = color.sizes.find(s => s.size_name === oldSizeName && s.stock > 0);
                if (match) {
                    selectedSize = match;
                }
            }

            renderColors();
            renderSizes();
            
            // Đổi ảnh chính sang ảnh của màu vừa chọn
            if (color.images && color.images.length > 0) {
                const primary = color.images.find(i => i.is_primary) || color.images[0];
                const imgUrl = '/storage/' + primary.image_path;
                changeProductImage(imgUrl, null, true);
            }
        }

        function renderSizes() {
            const container = document.getElementById('size-options-container');
            const label = document.getElementById('selected-size-label');
            container.innerHTML = '';
            
            let availableSizes = [];
            
            if (selectedColor) {
                if (selectedColor.sizes) {
                    availableSizes = selectedColor.sizes.filter(s => s.stock > 0);
                }
            } else {
                // Gom tất cả các size từ tất cả các màu
                if (product.colors) {
                    product.colors.forEach(color => {
                        if (color.sizes) {
                            color.sizes.forEach(s => {
                                if (s.stock > 0 && !availableSizes.find(as => as.size_name === s.size_name)) {
                                    availableSizes.push(s);
                                }
                            });
                        }
                    });
                }
            }

            // Sắp xếp size (XS -> XXL)
            const sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
            availableSizes.sort((a, b) => {
                let posA = sizeOrder.indexOf(a.size_name);
                let posB = sizeOrder.indexOf(b.size_name);
                if(posA === -1) posA = 99;
                if(posB === -1) posB = 99;
                return posA - posB;
            });
            
            if (availableSizes.length > 0) {
                label.innerText = selectedSize ? selectedSize.size_name : 'Vui lòng chọn size';
                
                availableSizes.forEach(size => {
                    const btn = document.createElement('button');
                    btn.className = 'size-btn ' + (selectedSize && selectedSize.size_name === size.size_name ? 'active' : '');
                    btn.innerText = size.size_name;
                    btn.onclick = () => selectSize(size);
                    container.appendChild(btn);
                });
                
                const stockEl = document.getElementById('stock-remaining');
                if (selectedSize) {
                    stockEl.classList.remove('d-none');
                    let totalStock = selectedSize.stock;
                    
                    if (!selectedColor && product.colors) {
                        totalStock = 0;
                        product.colors.forEach(c => {
                            if (c.sizes) {
                                c.sizes.forEach(s => {
                                    if (s.size_name === selectedSize.size_name) totalStock += s.stock;
                                });
                            }
                        });
                    }
                    
                    stockEl.querySelector('span').innerText = totalStock;
                    
                    // Cập nhật giá trị max cho quantity input
                    const input = document.getElementById('quantity-input');
                    input.setAttribute('data-max', totalStock);
                    if (parseInt(input.value) > totalStock) {
                        input.value = totalStock;
                    }
                } else {
                    stockEl.classList.add('d-none');
                }
            } else {
                selectedSize = null;
                label.innerText = 'Hết hàng';
                container.innerHTML = '<div class="text-danger mt-2 fw-medium"><i class="fa-solid fa-circle-exclamation"></i> Sản phẩm này hiện tại đã hết size.</div>';
                document.getElementById('stock-remaining').classList.add('d-none');
            }
        }

        function selectSize(size) {
            selectedSize = size;
            renderSizes();
        }

        function renderImages() {
            const container = document.getElementById('thumbnail-list-container');
            container.innerHTML = '';

            if (allImages.length > 0) {
                const primaryImage = allImages.find(i => i.is_primary) || allImages[0];
                const mainUrl = '/storage/' + primaryImage.image_path;
                changeProductImage(mainUrl, null, false);
                
                allImages.forEach((img, index) => {
                    const imgUrl = '/storage/' + img.image_path;
                    const thumb = document.createElement('div');
                    thumb.className = 'thumbnail-item flex-shrink-0 ' + (img.id === primaryImage.id ? 'active' : '');
                    thumb.style.width = '80px';
                    thumb.style.height = '100px';
                    thumb.onclick = function() {
                        changeProductImage(imgUrl, this, true);
                    };
                    
                    const imgEl = document.createElement('img');
                    imgEl.src = imgUrl;
                    imgEl.alt = 'Thumbnail';
                    imgEl.style.objectPosition = 'top';
                    imgEl.style.objectFit = 'cover';
                    imgEl.style.width = '100%';
                    imgEl.style.height = '100%';
                    
                    thumb.appendChild(imgEl);
                    container.appendChild(thumb);
                });

                setTimeout(checkThumbArrows, 100);
                container.addEventListener('scroll', checkThumbArrows);
                window.addEventListener('resize', checkThumbArrows);
            } else {
                changeProductImage('{{ asset("images/product1.png") }}', null, false);
            }
        }

        function slideThumbnails(direction) {
            const container = document.getElementById('thumbnail-list-container');
            const scrollAmount = 200;
            container.scrollLeft += direction * scrollAmount;
            setTimeout(checkThumbArrows, 300);
        }

        function checkThumbArrows() {
            const container = document.getElementById('thumbnail-list-container');
            const leftArrow = document.getElementById('thumb-arrow-left');
            const rightArrow = document.getElementById('thumb-arrow-right');
            
            if (!container) return;

            if (container.scrollWidth <= container.clientWidth) {
                leftArrow.classList.add('d-none');
                rightArrow.classList.add('d-none');
            } else {
                if (container.scrollLeft > 0) leftArrow.classList.remove('d-none');
                else leftArrow.classList.add('d-none');
                
                if (Math.ceil(container.scrollLeft + container.clientWidth) >= container.scrollWidth - 5) {
                    rightArrow.classList.add('d-none');
                } else {
                    rightArrow.classList.remove('d-none');
                }
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

            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active');
                if (!el) {
                    const thumbImg = item.querySelector('img');
                    // Kiểm tra URL tương đối (imgUrl) có nằm trong URL tuyệt đối (thumbImg.src) hay không
                    if (thumbImg && thumbImg.src.includes(imgUrl)) {
                        item.classList.add('active');
                    }
                }
            });

            if (el) {
                el.classList.add('active');
            }
        }

        // Adjust quantity selector
        function adjustQuantity(amount) {
            const input = document.getElementById('quantity-input');
            let currentVal = parseInt(input.value);
            if (isNaN(currentVal)) currentVal = 1;
            currentVal += amount;
            
            let maxStock = parseInt(input.getAttribute('data-max')) || 1;
            if (currentVal < 1) currentVal = 1;
            if (currentVal > maxStock) currentVal = maxStock;
            
            input.value = currentVal;
        }

        // Validate quantity input on manual entry
        function validateQuantity(input) {
            let val = parseInt(input.value);
            let maxStock = parseInt(input.getAttribute('data-max')) || 1;
            
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
            initImages();
            renderColors();
            renderSizes();
            renderImages();
            
            document.querySelectorAll('.accordion-panel.show').forEach(panel => {
                panel.style.maxHeight = panel.scrollHeight + "px";
            });

            // Function handle add to cart and optionally redirect
            async function handleAddToCart(redirect = false) {
                const quantity = parseInt(document.getElementById('quantity-input').value);
                const colorId = selectedColor ? selectedColor.id : null;
                const sizeId = selectedSize ? selectedSize.id : null;

                // Nếu sản phẩm có phân loại màu sắc/kích cỡ thì phải bắt buộc chọn
                if (product.colors && product.colors.length > 0 && !colorId) {
                    return showToast('Vui lòng chọn màu sắc', 'warning');
                }
                if (selectedColor && selectedColor.sizes && selectedColor.sizes.length > 0 && !sizeId) {
                    return showToast('Vui lòng chọn kích cỡ', 'warning');
                }

                try {
                    const response = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: product.id,
                            product_color_id: colorId,
                            product_size_id: sizeId,
                            quantity: quantity
                        })
                    });

                    if (response.status === 401) {
                        showToast('Vui lòng đăng nhập để thực hiện', 'warning');
                        return;
                    }

                    const data = await response.json();

                    if (response.ok) {
                        if (redirect) {
                            // Mua ngay thì chuyển thẳng sang trang thanh toán
                            window.location.href = '/thanh-toan';
                        } else {
                            showToast(data.message || 'Thêm vào giỏ hàng thành công!', 'success');
                            
                            // Cập nhật con số trên biểu tượng giỏ hàng ở thanh Menu
                            if (data.cart_count !== undefined) {
                                const badges = document.querySelectorAll('.cart-area .badge, .mobile-actions .badge');
                                badges.forEach(badge => {
                                    badge.innerText = data.cart_count > 99 ? '99+' : data.cart_count;
                                    badge.classList.remove('d-none');
                                    badge.classList.add('d-flex');
                                });
                            }
                        }
                    } else {
                        showToast(data.error || 'Đã có lỗi xảy ra', 'error');
                    }
                } catch (error) {
                    showToast('Lỗi kết nối máy chủ', 'error');
                }
            }

            // Handle Add to Cart
            const btnAddCart = document.getElementById('btn-add-cart-action');
            if (btnAddCart) {
                btnAddCart.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!selectedColor) {
                        showToast('Vui lòng chọn màu sắc!', 'warning');
                        return;
                    }
                    if (!selectedSize && selectedColor.sizes && selectedColor.sizes.length > 0 && selectedColor.sizes.filter(s => s.stock > 0).length > 0) {
                        showToast('Vui lòng chọn kích cỡ!', 'warning');
                        return;
                    }
                    handleAddToCart(false);
                });
            }
            
            // Handle Buy Now
            const btnBuyNow = document.getElementById('btn-buy-now-action');
            if (btnBuyNow) {
                btnBuyNow.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!selectedColor) {
                        showToast('Vui lòng chọn màu sắc!', 'warning');
                        return;
                    }
                    if (!selectedSize && selectedColor.sizes && selectedColor.sizes.length > 0 && selectedColor.sizes.filter(s => s.stock > 0).length > 0) {
                        showToast('Vui lòng chọn kích cỡ!', 'warning');
                        return;
                    }
                    handleAddToCart(true);
                });
            }
        });
    </script>
@endpush
