<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            border: 1px solid #000;
            padding: 8px;
        }
    </style>
</head>

<body>

  <h2 style="
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
">
    Top Ten Points – Tailor & Fabrics
</h2>
<hr>

    <p>Date: {{ now()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ number_format($order->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: {{ number_format($totalAmount, 2) }}</h3>

</body>

</html>
