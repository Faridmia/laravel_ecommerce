<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 20px;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #edf2f7;
        }
        .email-header {
            background-color: #f0f4f8;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #edf2f7;
        }
        .email-header h2 {
            margin: 0;
            font-size: 22px;
            color: #333333;
            font-weight: bold;
        }
        .email-body {
            padding: 30px;
            line-height: 1.6;
            font-size: 14px;
        }
        .email-body p {
            margin: 0 0 15px 0;
            color: #555555;
        }
        .order-details-list {
            margin: 15px 0 25px 0;
            padding-left: 20px;
            color: #555555;
        }
        .order-details-list li {
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            border-bottom: 2px solid #edf2f7;
            padding: 10px 0;
            text-align: left;
            font-weight: bold;
            color: #333333;
        }
        .items-table td {
            padding: 15px 0;
            border-bottom: 1px solid #edf2f7;
            vertical-align: top;
        }
        .items-table th.qty, .items-table td.qty {
            text-align: center;
            width: 80px;
        }
        .items-table th.price, .items-table td.price {
            text-align: right;
            width: 100px;
        }
        .summary-details {
            margin-top: 15px;
            color: #555555;
            line-height: 1.8;
        }
        .summary-details p {
            margin: 5px 0;
        }
        .email-footer {
            margin-top: 30px;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <h2>{{ $systemSettings->website_name ?? 'E-Commerce' }}</h2>
            </div>
            <div class="email-body">
                <p>Dear {{ $order->billing_first_name }},</p>
                <p>Thank you for your recent purchase with <strong>{{ $systemSettings->website_name ?? 'E-Commerce' }}</strong>. We are pleased to confirm your order.</p>
                
                <p><strong>Order Details:</strong></p>
                <ul class="order-details-list">
                    <li>Order Number: {{ $order->order_number }}</li>
                    <li>Date of Purchase: {{ date('d-m-Y', strtotime($order->created_at)) }}</li>
                </ul>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="qty">Quantity</th>
                            <th class="price">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product_name }}
                                    @php
                                        $attributes = [];
                                        if(!empty($item->size)) {
                                            $attributes[] = 'Size: ' . $item->size->name;
                                        }
                                        if(!empty($item->color)) {
                                            $attributes[] = 'Color: ' . $item->color->name;
                                        }
                                    @endphp
                                    @if(count($attributes) > 0)
                                        <br><span style="color: #777; font-size: 13px;">{{ implode(' | ', $attributes) }}</span>
                                    @endif
                                </td>
                                <td class="qty">{{ $item->quantity }}</td>
                                <td class="price">${{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary-details">
                    <p><strong>Shipping Name:</strong> {{ $order->shipping_charge > 0 ? 'Standard Shipping' : 'Free Shipping' }}</p>
                    <p><strong>Shipping Amount:</strong> ${{ number_format($order->shipping_charge, 2) }}</p>
                    @if($order->discount > 0)
                        <p><strong>Discount Amount:</strong> -${{ number_format($order->discount, 2) }}</p>
                    @endif
                    <p><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) === 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}</p>
                </div>

                <div class="email-footer">
                    <p>Thank you for choosing <strong>{{ $systemSettings->website_name ?? 'E-Commerce' }}</strong>. We appreciate your business.</p>
                    <p>Thanks,<br>{{ $systemSettings->website_name ?? 'E-Commerce' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
