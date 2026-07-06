@extends('layouts.app')

@section('title', 'Đơn Mua Của Tôi')

@push('styles')
    <style>
        .order-history-page {
            min-height: 70vh;
            background-color: #FFFDF8;
        }
        .order-tabs {
            background: #fff;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .order-tabs::-webkit-scrollbar {
            display: none;
        }
        .order-tab {
            padding: 1rem;
            color: #555;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            text-align: center;
            font-weight: 500;
        }
        .order-tab:hover {
            color: #000;
        }
        .order-tab.active {
            color: #000;
            border-bottom: 2px solid #000;
        }
        .order-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.05);
        }
        .order-status {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .status-pending { color: #f6a623; }
        .status-confirmed { color: #00bfa5; }
        .status-shipping { color: #2196f3; }
        .status-completed { color: #ee4d2d; }
        .status-cancelled { color: #9e9e9e; }
        
        .order-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #eee;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .order-card {
                padding: 1rem;
            }
            .order-card-header {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-history-page py-4">
        <div class="container" style="max-width: 1000px;">
            
            <div class="d-flex order-tabs mb-3 shadow-sm rounded flex-nowrap">
                <a href="/don-hang?status=all" class="flex-fill order-tab {{ $status == 'all' ? 'active' : '' }}">Tất cả</a>
                <a href="/don-hang?status=pending" class="flex-fill order-tab {{ $status == 'pending' ? 'active' : '' }}">Chờ xác nhận</a>
                <a href="/don-hang?status=confirmed" class="flex-fill order-tab {{ $status == 'confirmed' ? 'active' : '' }}">Chờ lấy hàng</a>
                <a href="/don-hang?status=shipping" class="flex-fill order-tab {{ $status == 'shipping' ? 'active' : '' }}">Đang giao</a>
                <a href="/don-hang?status=completed" class="flex-fill order-tab {{ $status == 'completed' ? 'active' : '' }}">Hoàn thành</a>
                <a href="/don-hang?status=cancelled" class="flex-fill order-tab {{ $status == 'cancelled' ? 'active' : '' }}">Đã hủy</a>
            </div>

            @if($orders->isEmpty())
                <div class="text-center bg-white p-5 rounded shadow-sm">
                    <img src="{{ asset('images/empty-order.png') }}" alt="No orders" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
                    <p class="text-muted">Chưa có đơn hàng</p>
                </div>
            @else
                @foreach($orders as $order)
                    @php
                        $statusText = '';
                        $statusClass = '';
                        switch($order->status) {
                            case 'pending': $statusText = 'CHỜ XÁC NHẬN'; $statusClass = 'status-pending'; break;
                            case 'confirmed': $statusText = 'ĐÃ XÁC NHẬN'; $statusClass = 'status-confirmed'; break;
                            case 'shipping': $statusText = 'ĐANG GIAO HÀNG'; $statusClass = 'status-shipping'; break;
                            case 'completed': $statusText = 'HOÀN THÀNH'; $statusClass = 'status-completed'; break;
                            case 'cancelled': $statusText = 'ĐÃ HỦY'; $statusClass = 'status-cancelled'; break;
                        }
                    @endphp
                    <div class="order-card">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 order-card-header">
                            <div>
                                <span class="fw-bold me-2">#{{ $order->order_code }}</span>
                                <span class="text-muted" style="font-size: 0.85rem;">{{ $order->created_at->format('d-m-Y H:i') }}</span>
                            </div>
                            <div class="order-status {{ $statusClass }}">
                                <i class="fa-solid fa-truck-fast me-1"></i> {{ $statusText }}
                            </div>
                        </div>

                        <!-- Lấy 1-2 sản phẩm tiêu biểu để hiển thị -->
                        @foreach($order->items->take(2) as $item)
                            @php
                                $primaryImage = $item->product->images->where('is_primary', true)->first();
                                $imagePath = $primaryImage ? $primaryImage->image_path : ($item->product->images->first() ? $item->product->images->first()->image_path : null);
                                $imageUrl = $imagePath ? asset('storage/' . $imagePath) : asset('images/product2.png');
                            @endphp
                            <div class="d-flex gap-3 mb-3">
                                <img src="{{ $imageUrl }}" class="order-item-img rounded" alt="{{ $item->product->name }}">
                                <div class="flex-fill">
                                    <h6 class="mb-1 text-truncate" style="max-width: 400px;">{{ $item->product->name }}</h6>
                                    <div class="text-muted" style="font-size: 0.85rem;">x{{ $item->quantity }}</div>
                                </div>
                                <div class="text-end fw-medium">
                                    {{ number_format($item->price, 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-center text-muted mb-3 border-top pt-2" style="font-size: 0.85rem;">
                                Xem thêm {{ $order->items->count() - 2 }} sản phẩm khác...
                            </div>
                        @endif

                        <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-center border-top pt-3 mt-2 gap-3">
                            <div class="text-end text-md-start">Thành tiền: <span class="fs-5 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span></div>
                            <a href="/don-hang/{{ $order->order_code }}" class="btn btn-outline-dark px-4 py-2 align-self-end align-self-md-auto">Xem Chi Tiết</a>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
@endsection
