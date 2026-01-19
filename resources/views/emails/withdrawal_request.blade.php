<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Affiliate Withdrawal Request</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>New Affiliate Withdrawal Request</h2>
        <p>A new withdrawal request has been submitted by an affiliate user.</p>
    </div>

    <div class="content">
        <h3>Withdrawal Details</h3>
        <table class="info-table">
            <tr>
                <th>User Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>User Email</th>
                <td>{{ $user->email }}</td>
            </tr>
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
                <th>Status</th>
                <td><span class="status-pending">{{ ucfirst($withdrawal->status) }}</span></td>
            </tr>
            <tr>
                <th>Request Date</th>
                <td>{{ $withdrawal->created_at->format('M d, Y h:i A') }}</td>
            </tr>
        </table>

        <p><strong>Action Required:</strong> Please review this withdrawal request and take appropriate action from the admin panel.</p>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
