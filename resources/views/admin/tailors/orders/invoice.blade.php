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

        .th-sl {
            width: 50px;
            text-align: center;
        }

        .th-amount {
            width: 120px;
            text-align: right;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center; font-weight: bold; margin-bottom: 20px;">
        Top Ten Points – Tailor & Fabrics
    </h2>

    <hr>

    <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>

    @if ($orders->isNotEmpty())
        <p><strong>Customer:</strong> {{ $orders->first()->customer->name }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 60px;">SL</th>
                <th>Service Name</th>
                <th style="width: 120px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>{{ $order->garmentType->name }}</td>
                    <td>
                        {{ number_format($order->price, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Total: {{ number_format($totalAmount, 2) }}</h3>

</body>

</html>
