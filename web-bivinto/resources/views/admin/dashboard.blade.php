@extends('layouts.admin')

@section('title', 'Tổng quan | Admin Bivinto')
@section('page-title', 'Tổng quan')

@push('styles')
    <link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row g-3 mb-4">
    <!-- Thống kê cơ bản -->
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Tổng doanh thu</h6>
                    <h4 class="m-0 text-dark">45.5M đ</h4>
                </div>
                <div class="text-success">
                    <i class="fa-solid fa-money-bill-wave fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Đơn hàng mới</h6>
                    <h4 class="m-0 text-dark">128</h4>
                </div>
                <div class="text-primary">
                    <i class="fa-solid fa-cart-shopping fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Khách hàng</h6>
                    <h4 class="m-0 text-dark">1,245</h4>
                </div>
                <div class="text-info">
                    <i class="fa-solid fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Sản phẩm</h6>
                    <h4 class="m-0 text-dark">45</h4>
                </div>
                <div class="text-warning">
                    <i class="fa-solid fa-box fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Đơn hàng gần đây -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h6 class="m-0">Đơn hàng gần đây</h6>
                <a href="#" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#DH001</td>
                                <td>Nguyễn Văn A</td>
                                <td>03/07/2026</td>
                                <td>1,250,000 đ</td>
                                <td><span class="badge bg-success">Hoàn thành</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DH002</td>
                                <td>Trần Thị B</td>
                                <td>02/07/2026</td>
                                <td>850,000 đ</td>
                                <td><span class="badge bg-warning text-dark">Đang xử lý</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DH003</td>
                                <td>Lê Văn C</td>
                                <td>02/07/2026</td>
                                <td>3,100,000 đ</td>
                                <td><span class="badge bg-secondary">Chờ thanh toán</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DH004</td>
                                <td>Phạm Thị D</td>
                                <td>01/07/2026</td>
                                <td>450,000 đ</td>
                                <td><span class="badge bg-danger">Đã hủy</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sản phẩm bán chạy -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0">Sản phẩm bán chạy</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-shirt text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Áo Polo Nam</h6>
                                <small class="text-muted">450,000 đ</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark">124 đã bán</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-shirt text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Áo Sơ Mi Nam</h6>
                                <small class="text-muted">550,000 đ</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark">98 đã bán</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-vest text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Áo Len Cổ Lọ</h6>
                                <small class="text-muted">650,000 đ</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark">76 đã bán</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
