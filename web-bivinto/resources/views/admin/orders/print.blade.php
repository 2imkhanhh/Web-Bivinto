<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Hóa Đơn #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 15px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-info h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 2px 0;
        }
        .order-info {
            text-align: right;
        }
        .order-info h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .customer-info h4 {
            margin: 0 0 5px 0;
            font-size: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
            display: inline-block;
        }
        .customer-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td.text-right, th.text-right {
            text-align: right;
        }
        td.text-center, th.text-center {
            text-align: center;
        }
        .totals {
            margin-top: 15px;
            width: 50%;
            float: right;
        }
        .totals table th, .totals table td {
            border-bottom: none;
            padding: 4px 10px;
        }
        .totals table tr.total th, .totals table tr.total td {
            border-top: 2px solid #000;
            font-size: 15px;
            font-weight: bold;
        }
        .footer {
            clear: both;
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #555;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            text-align: center;
        }
        .signature div {
            width: 45%;
        }
        @page {
            size: auto;
            margin: 10mm;
        }
        @media print {
            .invoice-box {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #000; color: #fff; border: none; cursor: pointer;">In Hóa Đơn</button>
    </div>
    
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h2>BIVINTO STORE</h2>
                <p>Website: bivinto.com</p>
                <p>Hotline: 0123 456 789</p>
            </div>
            <div class="order-info">
                <h3>HÓA ĐƠN BÁN LẺ</h3>
                <p><strong>Mã ĐH:</strong> #{{ $order->order_code }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Ngày in:</strong> {{ date('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="customer-info">
            <h4>Thông tin khách hàng</h4>
            <p><strong>Khách hàng:</strong> {{ $order->name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
            @if($order->note)
            <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Sản phẩm</th>
                    <th class="text-center">ĐVT</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-center">SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->color || $item->size)
                            <br><small style="color: #666;">({{ $item->color ? $item->color->color_name : '' }} - {{ $item->size ? $item->size->size_name : '' }})</small>
                        @endif
                    </td>
                    <td class="text-center">Cái</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <th class="text-right">Tạm tính:</th>
                    <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                </tr>
                <tr>
                    <th class="text-right">Phí vận chuyển:</th>
                    <td class="text-right">{{ $order->shipping_fee == 0 ? '0' : number_format($order->shipping_fee, 0, ',', '.') }} đ</td>
                </tr>
                <tr class="total">
                    <th class="text-right">TỔNG CỘNG:</th>
                    <td class="text-right">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                </tr>
            </table>
        </div>

        <div class="signature">
            <div>
                <strong>Khách hàng</strong>
                <p style="margin-top: 5px; font-style: italic; font-size: 12px;">(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <strong>Người bán hàng</strong>
                <p style="margin-top: 5px; font-style: italic; font-size: 12px;">(Ký, ghi rõ họ tên)</p>
            </div>
        </div>

        <div class="footer">
            <p>Cảm ơn quý khách đã mua sắm tại Bivinto!</p>
        </div>
    </div>
</body>
</html>
