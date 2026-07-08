@extends('layouts.app')

@section('title', 'Bivinto - Thanh Toán')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <style>
        .custom-cart-checkbox {
            width: 1.2rem;
            height: 1.2rem;
            border-color: #333;
            cursor: pointer;
        }

        .custom-cart-checkbox:checked {
            background-color: #100F0F;
            border-color: #100F0F;
        }
    </style>
@endpush

@section('content')
    <div class="cart-page pt-5 pb-5">
        <div class="container mb-5">
            @if ($cartItems->count() > 0)
                <h1 class="section-title fw-bold mb-3">GIỎ HÀNG</h1>
                <hr class="blogs-main-divider mb-5">

                <div class="row gx-5">
                    <!-- Left Column: Cart Items -->
                    <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            <input class="form-check-input custom-cart-checkbox m-0 me-3" type="checkbox"
                                id="check-all-items" checked>
                            <label class="form-check-label fw-medium cursor-pointer" for="check-all-items"
                                style="font-size: 0.95rem; user-select: none;">
                                Chọn tất cả (<span id="total-items-count">{{ $cartItems->count() }}</span>)
                            </label>
                        </div>

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
                            <div class="d-flex align-items-center cart-item pb-4 mb-4 border-bottom cart-item-row"
                                data-id="{{ $item->id }}" data-price="{{ $item->product->price }}">
                                <input class="form-check-input custom-cart-checkbox item-checkbox m-0 me-3 flex-shrink-0"
                                    type="checkbox" checked onchange="recalculateTotals()">

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

                    <!-- Right Column: Cart Summary -->
                    <div class="col-12 col-lg-4 ps-lg-5">

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

                        <!-- Checkout Button -->
                        <button type="button" id="btn-proceed-checkout"
                            class="btn btn-dark w-100 rounded-pill py-3 fw-medium" style="font-size: 1.05rem;">Thanh
                            Toán</button>
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
            let checkedCount = 0;
            const rows = document.querySelectorAll('.cart-item-row');

            rows.forEach(row => {
                const price = parseFloat(row.getAttribute('data-price'));
                const qty = parseInt(row.querySelector('.item-qty').innerText);
                const isChecked = row.querySelector('.item-checkbox').checked;

                // update item total
                const itemTotal = price * qty;
                row.querySelector('.item-total-price').innerText = new Intl.NumberFormat('vi-VN').format(itemTotal);

                if (isChecked) {
                    totalItems += qty;
                    subtotal += itemTotal;
                    checkedCount++;
                }
            });

            // Handle "Select All" state
            const checkAll = document.getElementById('check-all-items');
            if (checkAll) {
                checkAll.checked = (checkedCount === rows.length && rows.length > 0);
            }

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


        // Event listeners
        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('check-all-items')?.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = isChecked);
                recalculateTotals();
            });

            document.getElementById('btn-proceed-checkout')?.addEventListener('click', function() {
                const checkedIds = [];
                document.querySelectorAll('.cart-item-row').forEach(row => {
                    if (row.querySelector('.item-checkbox').checked) {
                        checkedIds.push(row.getAttribute('data-id'));
                    }
                });

                if (checkedIds.length === 0) {
                    showToast('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán', 'warning');
                    return;
                }

                window.location.href = '/thanh-toan?items=' + checkedIds.join(',');
            });

            // Initial calculation
            recalculateTotals();
        });
    </script>
@endpush
