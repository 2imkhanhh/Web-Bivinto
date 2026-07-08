@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công')

@push('styles')
    <style>
        .success-page {
            min-height: 60vh;
        }

        .success-icon {
            font-size: 5rem;
            color: #198754;
            margin-bottom: 1.5rem;
        }

        .order-id {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            margin-top: 1rem;
            border: 1px dashed #ccc;
        }

        .order-info-box {
            background-color: #FFFDF8;
            border-radius: 12px;
            padding: 2rem;
            text-align: left;
            margin-top: 2rem;
        }
    </style>
@endpush

@section('content')
    <div class="success-page d-flex align-items-center justify-content-center py-5">
        <div class="container text-center max-w-md" style="max-width: 600px;">
            <i class="fa-regular fa-circle-check success-icon"></i>
            <h1 class="fw-bold mb-3">Đặt Hàng Thành Công!</h1>
            <p class="text-muted mb-4">Cảm ơn bạn đã tin tưởng và mua sắm tại Bivinto. Đơn hàng của bạn đã được tiếp nhận và
                đang trong quá trình xử lý.</p>

            <div class="order-id">Mã đơn hàng: #{{ $order->order_code }}</div>

            @if(!auth()->check())
                <div class="alert alert-info mt-4 text-start rounded-3 border-0 bg-light" role="alert" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-circle-info me-2 text-primary"></i> 
                    Để theo dõi tình trạng giao hàng, bạn có thể sử dụng <strong>Mã đơn hàng</strong> và <strong>Email</strong> tại trang <a href="{{ route('order.track.form') }}" class="fw-bold text-primary text-decoration-none">Tra Cứu Đơn Hàng</a>.
                </div>
            @endif

            <div class="order-info-box mt-4">
                <h5 class="fw-bold mb-3">Thông tin giao hàng</h5>
                <p class="mb-1"><strong>Người nhận:</strong> {{ $order->name }}</p>
                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }},
                    {{ $order->district }}, {{ $order->province }}</p>
                <p class="mb-1"><strong>Phương thức thanh toán:</strong> Thanh toán khi nhận hàng (COD)</p>
                <hr class="my-3">
                <p class="mb-0 fs-5"><strong>Tổng thanh toán:</strong> <span
                        class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span></p>
            </div>

            <div class="mt-5 d-flex gap-3 justify-content-center">
                <a href="/san-pham" class="btn btn-dark rounded-pill px-4 py-2 fw-medium">Tiếp tục mua sắm</a>
                <a href="/" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium">Về trang chủ</a>
            </div>
        </div>
    </div>
@endsection
