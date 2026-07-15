@extends('layouts.app')

@section('title', 'Bivinto - Tra Cứu Đơn Hàng')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <h2 class="text-center fw-bold mb-4">Tra Cứu Đơn Hàng</h2>
            
            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('order.track.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="order_code" class="form-label fw-medium">Mã Đơn Hàng</label>
                    <input type="text" class="form-control py-2 custom-input" id="order_code" name="order_code" placeholder="VD: BVT260708XXXXX" required>
                </div>
                

                <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-medium" style="font-size: 0.95rem;">
                    TRA CỨU NGAY
                </button>
            </form>
            <div class="text-center mt-4 text-muted" style="font-size: 0.95rem;">
                Bạn đã có tài khoản? <a href="/tai-khoan" class="text-dark fw-medium">Đăng nhập</a> để xem chi tiết.
            </div>
        </div>
    </div>
</div>
@endsection
