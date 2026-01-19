<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Withdrawal Request {{ ucfirst($newStatus) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table th, .info-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 14px;
            color: #6c757d;
        }
        .note {
            background-color: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Withdrawal Request {{ ucfirst($newStatus) }}</h2>
        <p>Your withdrawal request has been {{ $newStatus }}.</p>
    </div>

    <div class="content">
        <h3>Withdrawal Details</h3>
        <table class="info-table">
            <tr>
                <th>Amount</th>
                <td>{{ showAmount($withdrawal->amount) }}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{ $withdrawal->method }}</td>
            </tr>
            <tr>
                <th>Account Information</th>
                <td>{{ $withdrawal->account_info }}</td>
            </tr>
            <tr>
                <th>Previous Status</th>
                <td><span class="status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span></td>
            </tr>
            <tr>
                <th>Current Status</th>
                <td><span class="status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span></td>
            </tr>
            <tr>
                <th>Request Date</th>
                <td>{{ $withdrawal->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            <tr>
                <th>Updated Date</th>
                <td>{{ $withdrawal->updated_at->format('M d, Y h:i A') }}</td>
            </tr>
        </table>

        @if($withdrawal->note)
        <div class="note">
            <h4>Admin Note:</h4>
            <p>{{ $withdrawal->note }}</p>
        </div>
        @endif

        @if($newStatus === 'approved')
        <p><strong>Great news!</strong> Your withdrawal request has been approved and the amount will be processed according to your payment method.</p>
        @elseif($newStatus === 'rejected')
        <p><strong>Notice:</strong> Your withdrawal request has been rejected. If you have any questions, please contact our support team.</p>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
        <p>If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>
