<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f9f9f9; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #000; text-transform: uppercase; }
        .success-icon { color: #198754; font-size: 24px; margin-bottom: 10px; }
        .info-box { background-color: #fcf8e3; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #f0ad4e; }
        .info-box p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .total-row th, .total-row td { font-weight: bold; font-size: 16px; border-top: 2px solid #333; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #000; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>BIVINTO STORE</h2>
            <p style="color: #198754; font-weight: bold;">Đơn hàng của bạn đã được xác nhận!</p>
        </div>

        <p>Xin chào <strong>{{ $order->name }}</strong>,</p>
        <p>Cảm ơn bạn đã mua sắm tại Bivinto. Chúng tôi rất vui thông báo rằng đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được xác nhận và đang trong quá trình chuẩn bị để giao đến bạn.</p>

        <div class="info-box">
            <p><strong>Thông tin giao hàng:</strong></p>
            <p>{{ $order->name }} | {{ $order->phone }}</p>
            <p>{{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
        </div>

        <h3>Chi tiết đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        @if($item->color || $item->size)
                            <br><small style="color: #666;">Phân loại: {{ $item->color ? $item->color->color_name : '' }} - {{ $item->size ? $item->size->size_name : '' }}</small>
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
                    <td class="text-right">{{ $order->shipping_fee == 0 ? '0 đ' : number_format($order->shipping_fee, 0, ',', '.') . ' đ' }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Tổng thanh toán:</td>
                    <td class="text-right" style="color: #d9534f;">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/don-hang/' . $order->order_code) }}" class="btn" style="color: #ffffff !important; text-decoration: none;">Theo Dõi Đơn Hàng</a>
        </div>

        <div class="footer">
            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ hotline: <strong>0123 456 789</strong> hoặc trả lời trực tiếp email này.</p>
            <p>&copy; {{ date('Y') }} Bivinto Store. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
