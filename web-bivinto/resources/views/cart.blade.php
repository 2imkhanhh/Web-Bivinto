@extends('layouts.app')

@section('title', 'Bivinto - Thanh Toán')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
    <div class="cart-page pt-5 pb-5">
        <div class="container mb-5">
            @if ($cartItems->count() > 0)
                <h1 class="section-title fw-bold mb-3">GIỎ HÀNG</h1>
                <hr class="blogs-main-divider mb-5">

                <div class="row gx-5">
                    <!-- Left Column: Cart Items -->
                    <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                        @php
                            $subtotal = 0;
                            $totalItems = 0;
                        @endphp

                        @foreach ($cartItems as $item)
                            @php
                                // Calculate subtotal
                                $itemTotal = $item->product->price * $item->quantity;
                                $subtotal += $itemTotal;
                                $totalItems += $item->quantity;

                                // Find primary image or use placeholder
                                $primaryImage = $item->product->images->where('is_primary', true)->first();
                                $imagePath = $primaryImage
                                    ? $primaryImage->image_path
                                    : ($item->product->images->first()
                                        ? $item->product->images->first()->image_path
                                        : null);
                                $imageUrl = $imagePath ? asset('storage/' . $imagePath) : asset('images/product2.png');

                                // Get variant text
                                $variantText = '';
                                if ($item->color && $item->size) {
                                    $variantText = $item->color->color_name . ' / ' . $item->size->size_name;
                                } elseif ($item->color) {
                                    $variantText = $item->color->color_name;
                                } elseif ($item->size) {
                                    $variantText = $item->size->size_name;
                                }
                            @endphp
                            <!-- Cart Item -->
                            <div class="d-flex cart-item pb-4 mb-4 border-bottom cart-item-row" data-id="{{ $item->id }}"
                                data-price="{{ $item->product->price }}">
                                <div class="cart-item-img-wrap me-4 flex-shrink-0">
                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                        class="img-fluid object-fit-cover"
                                        style="width: 120px; height: 160px; border-radius: 4px; object-position: top;">
                                </div>
                                <div class="cart-item-info flex-grow-1 d-flex flex-column justify-content-between">
                                    <div>
                                        <h4 class="cart-item-title fw-semibold mb-1" style="font-size: 1rem;">
                                            <a href="/chi-tiet-san-pham/{{ $item->product->slug }}"
                                                class="text-dark text-decoration-none">{{ $item->product->name }}</a>
                                        </h4>
                                        @if ($variantText)
                                            <p class="cart-item-variant text-muted mb-2" style="font-size: 0.85rem;">
                                                {{ $variantText }}</p>
                                        @endif
                                        <p class="cart-item-price fw-bold mb-0" style="font-size: 0.95rem;">
                                            {{ number_format($item->product->price, 0, ',', '.') }}đ</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-start mt-3">
                                        <div class="d-flex flex-column gap-3">
                                            <div class="quantity-pill d-flex align-items-center justify-content-between rounded-pill border px-3 py-1"
                                                style="width: 100px;">
                                                <button type="button" class="btn p-0 border-0 btn-qty-minus"
                                                    style="font-size:1rem;"
                                                    onclick="updateCartQuantity({{ $item->id }}, -1)">-</button>
                                                <span class="fw-medium item-qty"
                                                    style="font-size:0.95rem;">{{ $item->quantity }}</span>
                                                <button type="button" class="btn p-0 border-0 btn-qty-plus"
                                                    style="font-size:1rem;"
                                                    onclick="updateCartQuantity({{ $item->id }}, 1)">+</button>
                                            </div>
                                            <button type="button"
                                                class="btn p-0 border-0 text-dark d-flex align-items-center gap-2 cart-delete-btn"
                                                style="font-size:0.9rem;" onclick="removeCartItem({{ $item->id }})">
                                                <i class="fa-regular fa-trash-can"></i> <span class="text-muted">Xoá</span>
                                            </button>
                                        </div>
                                        <div class="cart-item-total fw-bold mt-1" style="font-size: 0.95rem;">
                                            Tổng: <span
                                                class="item-total-price">{{ number_format($itemTotal, 0, ',', '.') }}</span>đ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right Column: Checkout Form -->
                    <div class="col-12 col-lg-6 ps-lg-5">

                        <!-- Order Info -->
                        <h3 class="checkout-title fw-bold mb-3" style="font-size: 1.05rem;">THÔNG TIN ĐƠN HÀNG</h3>
                        <div class="order-summary-container mb-5">
                            <div class="bg-light rounded px-3 py-3 d-flex justify-content-between align-items-center mb-2 cursor-pointer border-0"
                                style="background-color: #F6F6F6 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-ticket"></i>
                                    <span class="fw-medium" style="font-size: 0.9rem;">Phiếu giảm giá</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                            </div>

                            <div class="bg-light rounded px-3 py-4 border-0" style="background-color: #F6F6F6 !important;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium" style="font-size: 0.9rem;">Số lượng</span>
                                    <span class="fw-bold" style="font-size: 0.9rem;"
                                        id="summary-total-items">{{ $totalItems }} sản phẩm</span>
                                </div>
                                <hr class="my-3 border-secondary border-opacity-25">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted" style="font-size: 0.9rem;">Tạm tính</span>
                                    <span class="fw-medium" style="font-size: 0.95rem;"><span
                                            id="summary-subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span>đ</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted" style="font-size: 0.9rem;">Phí vận chuyển</span>
                                    <span class="fw-medium" style="font-size: 0.95rem;">0đ</span>
                                </div>
                                <hr class="my-3 border-secondary border-opacity-25">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fw-bold" style="font-size: 1rem;">Tổng đơn đặt hàng</span>
                                    <span class="fw-bold" style="font-size: 1.1rem;"><span
                                            id="summary-total">{{ number_format($subtotal, 0, ',', '.') }}</span>đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <h3 class="checkout-title fw-bold mb-3" style="font-size: 1.05rem;">THÔNG TIN GIAO HÀNG</h3>
                        <div class="shipping-info-box mb-5">
                            <input type="text" id="checkout-name" class="form-control custom-input mb-3" placeholder="Họ và tên*"
                                value="{{ auth()->user()->name ?? '' }}">
                            <div class="row gx-3 mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input type="email" id="checkout-email" class="form-control custom-input"
                                        placeholder="Email (không bắt buộc)" value="{{ auth()->user()->email ?? '' }}"></div>
                                <div class="col-sm-6"><input type="text" id="checkout-phone" class="form-control custom-input"
                                        placeholder="Số điện thoại*" value="{{ auth()->user()->phone ?? '' }}"></div>
                            </div>
                            <textarea id="checkout-address" class="form-control custom-input mb-3" rows="3" placeholder="Địa chỉ nhà cụ thể (Số nhà, đường...)*"></textarea>
                            <div class="row gx-2 mb-3">
                                <div class="col-4">
                                    <select id="checkout-province" class="form-select custom-input text-muted">
                                        <option value="" selected>Chọn Tỉnh/Thành</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select id="checkout-district" class="form-select custom-input text-muted" disabled>
                                        <option value="" selected>Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select id="checkout-ward" class="form-select custom-input text-muted" disabled>
                                        <option value="" selected>Chọn Phường/Xã</option>
                                    </select>
                                </div>
                            </div>
                            <input type="text" id="checkout-note" class="form-control custom-input mb-3"
                                placeholder="Ghi chú thêm: (cơ quan làm việc, giao giờ hành chính)">
                        </div>

                        <!-- Payment Method -->
                        <h3 class="checkout-title fw-bold mb-3" style="font-size: 1.05rem;">PHƯƠNG THỨC THANH TOÁN</h3>
                        <div class="payment-method-box mb-5">
                            <label
                                class="payment-box border rounded px-4 py-3 d-flex align-items-center gap-3 w-100 m-0 cursor-pointer">
                                <input class="form-check-input mt-0 custom-radio" type="radio" name="paymentMethod"
                                    checked style="width: 1.2rem; height: 1.2rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-truck-fast fs-5"></i>
                                    <span class="fw-medium text-dark" style="font-size: 0.95rem;">Thanh toán khi nhận hàng
                                        (COD)</span>
                                </div>
                            </label>
                        </div>

                        <!-- Checkout Button -->
                        <button type="button" id="btn-checkout" class="btn btn-dark w-100 rounded-pill py-3 fw-medium" style="font-size: 1.05rem;">Đặt
                            Hàng</button>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center py-5 my-5">
                    <i class="bx bx-cart text-muted mb-4" style="font-size: 6rem;"></i>
                    <h5 class="text-muted mb-4 fw-normal" style="font-size: 1.1rem;">Giỏ hàng của bạn đang trống</h5>
                    <a href="/san-pham" class="btn btn-dark rounded-pill px-5 py-2 fw-medium">Tiếp tục mua sắm</a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function updateCartQuantity(cartId, change) {
            const row = document.querySelector(`.cart-item-row[data-id="${cartId}"]`);
            if (!row) return;

            const qtyEl = row.querySelector('.item-qty');
            let currentQty = parseInt(qtyEl.innerText);
            let newQty = currentQty + change;
            if (newQty < 1) newQty = 1;

            if (newQty === currentQty) return;

            qtyEl.innerText = newQty; // Optimistic update
            recalculateTotals();

            try {
                const response = await fetch(`/cart/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        quantity: newQty
                    })
                });

                if (!response.ok) {
                    qtyEl.innerText = currentQty; // Revert on failure
                    recalculateTotals();
                    showToast('Không thể cập nhật số lượng', 'error');
                }
            } catch (error) {
                qtyEl.innerText = currentQty; // Revert
                recalculateTotals();
                showToast('Lỗi kết nối', 'error');
            }
        }

        function recalculateTotals() {
            let totalItems = 0;
            let subtotal = 0;
            document.querySelectorAll('.cart-item-row').forEach(row => {
                const price = parseFloat(row.getAttribute('data-price'));
                const qty = parseInt(row.querySelector('.item-qty').innerText);

                // update item total
                const itemTotal = price * qty;
                row.querySelector('.item-total-price').innerText = new Intl.NumberFormat('vi-VN').format(itemTotal);

                totalItems += qty;
                subtotal += itemTotal;
            });

            // update summary
            const summarySubtotal = document.getElementById('summary-subtotal');
            if (summarySubtotal) summarySubtotal.innerText = new Intl.NumberFormat('vi-VN').format(subtotal);

            const summaryTotal = document.getElementById('summary-total');
            if (summaryTotal) summaryTotal.innerText = new Intl.NumberFormat('vi-VN').format(subtotal);

            const summaryTotalItems = document.getElementById('summary-total-items');
            if (summaryTotalItems) summaryTotalItems.innerText = totalItems + ' sản phẩm';

            // update header badges
            document.querySelectorAll('.cart-area .badge, .mobile-actions .badge').forEach(badge => {
                badge.innerText = totalItems > 99 ? '99+' : totalItems;
            });
        }

        async function removeCartItem(cartId) {
            if (!await showConfirm('Xóa sản phẩm', 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                return;
            }

            try {
                const response = await fetch(`/cart/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (response.ok) {
                    const row = document.querySelector(`.cart-item-row[data-id="${cartId}"]`);
                    if (row) row.remove();
                    recalculateTotals();
                    showToast('Đã xóa sản phẩm', 'success');

                    // Nếu xóa hết sạch sản phẩm, tải lại trang để hiện trạng thái "Giỏ hàng trống"
                    if (document.querySelectorAll('.cart-item-row').length === 0) {
                        window.location.reload();
                    }
                } else {
                    showToast('Không thể xóa sản phẩm', 'error');
                }
            } catch (error) {
                showToast('Lỗi kết nối', 'error');
            }
        }

        // ==========================================
        // KHU VỰC: XỬ LÝ ĐẶT HÀNG VÀ API TỈNH THÀNH
        // ==========================================
        let provincesData = [];

        async function loadProvinces() {
            try {
                const response = await fetch('https://provinces.open-api.vn/api/?depth=3');
                provincesData = await response.json();
                
                const provinceSelect = document.getElementById('checkout-province');
                provincesData.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.name;
                    opt.dataset.code = p.code;
                    opt.innerText = p.name;
                    provinceSelect.appendChild(opt);
                });
            } catch (error) {
                console.error("Lỗi lấy danh sách tỉnh thành", error);
            }
        }

        document.getElementById('checkout-province')?.addEventListener('change', function() {
            const districtSelect = document.getElementById('checkout-district');
            const wardSelect = document.getElementById('checkout-ward');
            
            districtSelect.innerHTML = '<option value="" selected>Chọn Quận/Huyện</option>';
            wardSelect.innerHTML = '<option value="" selected>Chọn Phường/Xã</option>';
            wardSelect.disabled = true;

            const selectedOpt = this.options[this.selectedIndex];
            if (!selectedOpt.value) {
                districtSelect.disabled = true;
                return;
            }

            const code = selectedOpt.dataset.code;
            const province = provincesData.find(p => p.code == code);
            
            if (province && province.districts) {
                province.districts.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.name;
                    opt.dataset.code = d.code;
                    opt.innerText = d.name;
                    districtSelect.appendChild(opt);
                });
                districtSelect.disabled = false;
            }
        });

        document.getElementById('checkout-district')?.addEventListener('change', function() {
            const wardSelect = document.getElementById('checkout-ward');
            wardSelect.innerHTML = '<option value="" selected>Chọn Phường/Xã</option>';

            const selectedOpt = this.options[this.selectedIndex];
            if (!selectedOpt.value) {
                wardSelect.disabled = true;
                return;
            }

            const provinceCode = document.getElementById('checkout-province').options[document.getElementById('checkout-province').selectedIndex].dataset.code;
            const province = provincesData.find(p => p.code == provinceCode);
            const district = province.districts.find(d => d.code == selectedOpt.dataset.code);
            
            if (district && district.wards) {
                district.wards.forEach(w => {
                    const opt = document.createElement('option');
                    opt.value = w.name;
                    opt.innerText = w.name;
                    wardSelect.appendChild(opt);
                });
                wardSelect.disabled = false;
            }
        });

        document.getElementById('btn-checkout')?.addEventListener('click', async function() {
            const name = document.getElementById('checkout-name').value.trim();
            const email = document.getElementById('checkout-email').value.trim();
            const phone = document.getElementById('checkout-phone').value.trim();
            const address = document.getElementById('checkout-address').value.trim();
            const province = document.getElementById('checkout-province').value;
            const district = document.getElementById('checkout-district').value;
            const ward = document.getElementById('checkout-ward').value;
            const note = document.getElementById('checkout-note').value.trim();

            if (!name) return showToast('Vui lòng nhập Họ và tên', 'warning');
            if (!phone) return showToast('Vui lòng nhập Số điện thoại', 'warning');
            if (!address) return showToast('Vui lòng nhập Địa chỉ nhà', 'warning');
            if (!province) return showToast('Vui lòng chọn Tỉnh/Thành phố', 'warning');
            if (!district) return showToast('Vui lòng chọn Quận/Huyện', 'warning');
            if (!ward) return showToast('Vui lòng chọn Phường/Xã', 'warning');

            const btn = this;
            const originalText = btn.innerText;
            btn.innerText = 'Đang xử lý...';
            btn.disabled = true;

            try {
                const response = await fetch('/dat-hang', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name, email, phone, address, province, district, ward, note
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect_url;
                } else {
                    showToast(data.error || 'Đã có lỗi xảy ra', 'error');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                showToast('Lỗi kết nối máy chủ', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });

        // Tải danh sách tỉnh thành ngay khi load xong script
        window.addEventListener('DOMContentLoaded', () => {
            loadProvinces();
        });
    </script>
@endpush
