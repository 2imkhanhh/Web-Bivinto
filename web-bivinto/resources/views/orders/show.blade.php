@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code)

@push('styles')
    <style>
        .order-detail-page {
            min-height: 70vh;
            background-color: #FFFDF8;
        }
        .order-detail-card {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.05);
        }
        .step-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
        }
        .step-progress::before {
            content: "";
            position: absolute;
            top: 15px;
            left: 5%;
            right: 5%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 25%;
        }
        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        .step-item.active .step-icon {
            background-color: #00bfa5;
        }
        .step-label {
            font-size: 0.85rem;
            color: #757575;
        }
        .step-item.active .step-label {
            color: #00bfa5;
            font-weight: 500;
        }
        .order-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #eee;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .order-detail-card {
                padding: 1.25rem;
            }
            .step-progress::before {
                top: 12px;
            }
            .step-icon {
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
            }
            .step-label {
                font-size: 0.7rem;
                text-align: center;
            }
            .header-card {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-detail-page py-4">
        <div class="container" style="max-width: 1000px;">
            <div class="mb-3">
                <a href="/don-hang" class="text-decoration-none text-dark"><i class="fa-solid fa-chevron-left me-2"></i> Trở lại danh sách</a>
            </div>

            <!-- Header -->
            <div class="order-detail-card d-flex justify-content-between align-items-center header-card">
                <div>
                    <h5 class="mb-1 fw-bold">ĐƠN HÀNG #{{ $order->order_code }}</h5>
                    <div class="text-muted" style="font-size: 0.9rem;">Đặt ngày: {{ $order->created_at->format('d-m-Y H:i') }}</div>
                </div>
                <div>
                    @php
                        $statusText = '';
                        switch($order->status) {
                            case 'pending': $statusText = 'CHỜ XÁC NHẬN'; break;
                            case 'confirmed': $statusText = 'ĐÃ XÁC NHẬN'; break;
                            case 'shipping': $statusText = 'ĐANG GIAO HÀNG'; break;
                            case 'completed': $statusText = 'HOÀN THÀNH'; break;
                            case 'cancelled': $statusText = 'ĐÃ HỦY'; break;
                        }
                    @endphp
                    <span class="badge bg-dark px-2 py-1" style="font-size: 0.75rem;">{{ $statusText }}</span>
                </div>
            </div>

            <!-- Progress (Chỉ hiện nếu không bị hủy) -->
            @if($order->status !== 'cancelled')
            <div class="order-detail-card">
                <div class="step-progress">
                    <div class="step-item {{ in_array($order->status, ['pending', 'confirmed', 'shipping', 'completed']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="fa-solid fa-file-invoice"></i></div>
                        <div class="step-label">Chờ Xác Nhận</div>
                    </div>
                    <div class="step-item {{ in_array($order->status, ['confirmed', 'shipping', 'completed']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="fa-solid fa-box"></i></div>
                        <div class="step-label">Đã Xác Nhận</div>
                    </div>
                    <div class="step-item {{ in_array($order->status, ['shipping', 'completed']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="fa-solid fa-truck-fast"></i></div>
                        <div class="step-label">Đang Giao Hàng</div>
                    </div>
                    <div class="step-item {{ $order->status === 'completed' ? 'active' : '' }}">
                        <div class="step-icon"><i class="fa-solid fa-check"></i></div>
                        <div class="step-label">Đã Giao</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Địa chỉ nhận hàng -->
            <div class="order-detail-card">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-location-dot me-2"></i> Địa Chỉ Nhận Hàng</h6>
                <div class="ms-4">
                    <div class="fw-bold mb-1">{{ $order->name }}</div>
                    <div class="text-muted mb-1">{{ $order->phone }}</div>
                    <div class="text-muted mb-1">{{ $order->address }}</div>
                    <div class="text-muted mb-1">{{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</div>
                    @if($order->note)
                        <div class="mt-2 fst-italic">Ghi chú: {{ $order->note }}</div>
                    @endif
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="order-detail-card">
                <h6 class="fw-bold mb-4"><i class="fa-solid fa-box-open me-2"></i> Sản Phẩm</h6>
                
                @foreach($order->items as $item)
                    @php
                        $primaryImage = $item->product->images->where('is_primary', true)->first();
                        $imagePath = $primaryImage ? $primaryImage->image_path : ($item->product->images->first() ? $item->product->images->first()->image_path : null);
                        $imageUrl = $imagePath ? asset('storage/' . $imagePath) : asset('images/product2.png');
                        
                        $variantText = '';
                        if ($item->color && $item->size) {
                            $variantText = $item->color->color_name . ' / ' . $item->size->size_name;
                        }
                    @endphp
                    <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ $imageUrl }}" class="order-item-img rounded me-3" alt="{{ $item->product->name }}">
                        <div class="flex-fill">
                            <h6 class="mb-1">
                                <a href="/chi-tiet-san-pham/{{ $item->product->slug }}" class="text-dark text-decoration-none">{{ $item->product->name }}</a>
                            </h6>
                            @if($variantText)
                                <div class="text-muted" style="font-size: 0.85rem;">Phân loại: {{ $variantText }}</div>
                            @endif
                            <div class="fw-medium mt-1">{{ number_format($item->price, 0, ',', '.') }}đ x {{ $item->quantity }}</div>
                        </div>
                        <div class="text-end fw-bold">
                            {{ number_format($item->total, 0, ',', '.') }}đ
                        </div>
                    </div>
                @endforeach

                <!-- Tổng tiền -->
                <div class="row justify-content-end mt-4 pt-3 border-top">
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tổng tiền hàng</span>
                            <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span>{{ $order->shipping_fee == 0 ? '0đ' : number_format($order->shipping_fee, 0, ',', '.') . 'đ' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top align-items-center">
                            <span class="fw-bold">Thành tiền</span>
                            <span class="fs-4 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-end text-muted mt-3" style="font-size: 0.85rem;">
                    Phương thức thanh toán: Thanh toán khi nhận hàng
                </div>
            </div>

        </div>
    </div>
@endsection
