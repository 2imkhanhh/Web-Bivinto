@extends('layouts.app')

@section('title', 'Bivinto - Giỏ Hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
    <div class="cart-page pt-5 pb-5">
        <div class="container mb-5">
            <h1 class="section-title fw-bold mb-3">GIỎ HÀNG</h1>
            <hr class="blogs-main-divider mb-5">

            <div class="row gx-5">
                <!-- Left Column: Cart Items -->
                <div class="col-12 col-lg-6 mb-5 mb-lg-0">

                    <!-- Cart Item 1 -->
                    <div class="d-flex cart-item pb-4 mb-4 border-bottom">
                        <div class="cart-item-img-wrap me-4 flex-shrink-0">
                            <img src="{{ asset('images/product2.png') }}" alt="Áo Sơ Mi" class="img-fluid object-fit-cover"
                                style="width: 120px; height: 160px; border-radius: 4px;">
                        </div>
                        <div class="cart-item-info flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="cart-item-title fw-semibold mb-1" style="font-size: 1rem;">Áo Sơ Mi Cộc Tay Cổ
                                    Đức</h4>
                                <p class="cart-item-variant text-muted mb-2" style="font-size: 0.85rem;">Đen/ M</p>
                                <p class="cart-item-price fw-bold mb-0" style="font-size: 0.95rem;">850.000đ</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mt-3">
                                <div class="d-flex flex-column gap-3">
                                    <div class="quantity-pill d-flex align-items-center justify-content-between rounded-pill border px-3 py-1"
                                        style="width: 100px;">
                                        <button class="btn p-0 border-0" style="font-size:1rem;">-</button>
                                        <span class="fw-medium" style="font-size:0.95rem;">1</span>
                                        <button class="btn p-0 border-0" style="font-size:1rem;">+</button>
                                    </div>
                                    <button
                                        class="btn p-0 border-0 text-dark d-flex align-items-center gap-2 cart-delete-btn"
                                        style="font-size:0.9rem;">
                                        <i class="fa-regular fa-trash-can"></i> <span class="text-muted">Xoá</span>
                                    </button>
                                </div>
                                <div class="cart-item-total fw-bold mt-1" style="font-size: 0.95rem;">
                                    Tổng: 850.000đ
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Item 2 -->
                    <div class="d-flex cart-item pb-4 mb-4 border-bottom">
                        <div class="cart-item-img-wrap me-4 flex-shrink-0">
                            <img src="{{ asset('images/product2.png') }}" alt="Áo Sơ Mi" class="img-fluid object-fit-cover"
                                style="width: 120px; height: 160px; border-radius: 4px;">
                        </div>
                        <div class="cart-item-info flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="cart-item-title fw-semibold mb-1" style="font-size: 1rem;">Áo Sơ Mi Cộc Tay Cổ
                                    Đức</h4>
                                <p class="cart-item-variant text-muted mb-2" style="font-size: 0.85rem;">Đen/ M</p>
                                <p class="cart-item-price fw-bold mb-0" style="font-size: 0.95rem;">850.000đ</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mt-3">
                                <div class="d-flex flex-column gap-3">
                                    <div class="quantity-pill d-flex align-items-center justify-content-between rounded-pill border px-3 py-1"
                                        style="width: 100px;">
                                        <button class="btn p-0 border-0" style="font-size:1rem;">-</button>
                                        <span class="fw-medium" style="font-size:0.95rem;">1</span>
                                        <button class="btn p-0 border-0" style="font-size:1rem;">+</button>
                                    </div>
                                    <button
                                        class="btn p-0 border-0 text-dark d-flex align-items-center gap-2 cart-delete-btn"
                                        style="font-size:0.9rem;">
                                        <i class="fa-regular fa-trash-can"></i> <span class="text-muted">Xoá</span>
                                    </button>
                                </div>
                                <div class="cart-item-total fw-bold mt-1" style="font-size: 0.95rem;">
                                    Tổng: 850.000đ
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Item 3 -->
                    <div class="d-flex cart-item pb-4 mb-4 border-bottom">
                        <div class="cart-item-img-wrap me-4 flex-shrink-0">
                            <img src="{{ asset('images/product2.png') }}" alt="Áo Sơ Mi" class="img-fluid object-fit-cover"
                                style="width: 120px; height: 160px; border-radius: 4px;">
                        </div>
                        <div class="cart-item-info flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="cart-item-title fw-semibold mb-1" style="font-size: 1rem;">Áo Sơ Mi Cộc Tay Cổ
                                    Đức</h4>
                                <p class="cart-item-variant text-muted mb-2" style="font-size: 0.85rem;">Đen/ M</p>
                                <p class="cart-item-price fw-bold mb-0" style="font-size: 0.95rem;">850.000đ</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mt-3">
                                <div class="d-flex flex-column gap-3">
                                    <div class="quantity-pill d-flex align-items-center justify-content-between rounded-pill border px-3 py-1"
                                        style="width: 100px;">
                                        <button class="btn p-0 border-0" style="font-size:1rem;">-</button>
                                        <span class="fw-medium" style="font-size:0.95rem;">1</span>
                                        <button class="btn p-0 border-0" style="font-size:1rem;">+</button>
                                    </div>
                                    <button
                                        class="btn p-0 border-0 text-dark d-flex align-items-center gap-2 cart-delete-btn"
                                        style="font-size:0.9rem;">
                                        <i class="fa-regular fa-trash-can"></i> <span class="text-muted">Xoá</span>
                                    </button>
                                </div>
                                <div class="cart-item-total fw-bold mt-1" style="font-size: 0.95rem;">
                                    Tổng: 850.000đ
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <span class="fw-bold" style="font-size: 0.9rem;">2 sản phẩm</span>
                            </div>
                            <hr class="my-3 border-secondary border-opacity-25">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted" style="font-size: 0.9rem;">Tạm tính</span>
                                <span class="fw-medium" style="font-size: 0.95rem;">1.259.000đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted" style="font-size: 0.9rem;">Phí vận chuyển</span>
                                <span class="text-muted" style="font-size: 0.9rem;">chưa bao gồm phí vận chuyển</span>
                            </div>
                            <hr class="my-3 border-secondary border-opacity-25">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="fw-bold" style="font-size: 1rem;">Tổng đơn đặt hàng</span>
                                <span class="fw-bold" style="font-size: 1.1rem;">1.259.000đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <h3 class="checkout-title fw-bold mb-3" style="font-size: 1.05rem;">THÔNG TIN GIAO HÀNG</h3>
                    <div class="shipping-info-box mb-5">
                        <input type="text" class="form-control custom-input mb-3" placeholder="Họ và tên*">
                        <div class="row gx-3 mb-3">
                            <div class="col-sm-6 mb-3 mb-sm-0"><input type="email" class="form-control custom-input"
                                    placeholder="Email*"></div>
                            <div class="col-sm-6"><input type="text" class="form-control custom-input"
                                    placeholder="Số điện thoại*"></div>
                        </div>
                        <textarea class="form-control custom-input mb-3" rows="3" placeholder="Địa chỉ nhà*"></textarea>
                        <div class="row gx-2 mb-3">
                            <div class="col-4">
                                <select class="form-select custom-input text-muted">
                                    <option selected>Chọn Tỉnh/ Thành phố</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select custom-input text-muted">
                                    <option selected>Chọn Quận/ Huyện</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select custom-input text-muted">
                                    <option selected>Chọn Phường/ Xã</option>
                                </select>
                            </div>
                        </div>
                        <input type="text" class="form-control custom-input mb-3"
                            placeholder="Ghi chú thêm: (cơ quan làm việc, giao giờ hành chính)">
                    </div>

                    <!-- Payment Method -->
                    <h3 class="checkout-title fw-bold mb-3" style="font-size: 1.05rem;">PHƯƠNG THỨC THANH TOÁN</h3>
                    <div class="payment-method-box mb-5">
                        <label class="payment-box border rounded px-4 py-3 d-flex align-items-center gap-3 w-100 m-0">
                            <input class="form-check-input mt-0 custom-radio" type="radio" name="paymentMethod"
                                style="width: 1.2rem; height: 1.2rem;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-truck-fast fs-5"></i>
                                <span class="fw-medium text-dark" style="font-size: 0.95rem;">Thanh toán khi nhận hàng
                                    (COD)</span>
                            </div>
                        </label>
                    </div>

                    <!-- Checkout Button -->
                    <button class="btn btn-dark w-100 rounded-pill py-3 fw-medium" style="font-size: 1.05rem;">Đặt
                        Hàng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
