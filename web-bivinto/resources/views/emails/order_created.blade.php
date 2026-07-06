<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đơn hàng mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #000;
        }

        .info-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .total-row th,
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>BIVINTO STORE</h2>
            <p>Có một đơn hàng mới vừa được tạo!</p>
        </div>

        {{-- <p>Xin chào Admin,</p> --}}
        <p>Hệ thống vừa ghi nhận một đơn hàng mới từ khách hàng <strong>{{ $order->name }}</strong>.</p>

        <div class="info-box">
            <p><strong>Mã đơn hàng:</strong> #{{ $order->order_code }}</p>
            <p><strong>Thời gian đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }},
                {{ $order->province }}</p>
            @if ($order->note)
                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
            @endif
        </div>

        <h3>Chi tiết sản phẩm</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->product->name }}
                            @if ($item->color || $item->size)
                                <br><small style="color: #666;">Phân loại:
                                    {{ $item->color ? $item->color->color_name : '' }} -
                                    {{ $item->size ? $item->size->size_name : '' }}</small>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right">Tạm tính:</td>
                    <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">Phí vận chuyển:</td>
                    <td class="text-right">
                        {{ $order->shipping_fee == 0 ? '0 đ' : number_format($order->shipping_fee, 0, ',', '.') . ' đ' }}
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Tổng thanh toán:</td>
                    <td class="text-right" style="color: #d9534f;">
                        {{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn"
                style="color: #ffffff !important; text-decoration: none;">Xem Chi Tiết Đơn Hàng</a>
        </div>

        <div class="footer">
            <p>Đây là email tự động từ hệ thống Bivinto. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>

</html>
