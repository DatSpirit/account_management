<!DOCTYPE html>
<html>
<head>
    <title>Analytics Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4f46e5; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Report</h1>
        <p>Generated: {{ now()->format('d M Y, H:i') }}</p>
        <p>User: {{ $user->name }}</p>
    </div>

    <div class="stats">
        <h2>Summary</h2>
        <p>Total Spent: {{ number_format($analytics['total_spent']) }} VND</p>
        <p>Total Orders: {{ $analytics['total_orders'] }}</p>
        <p>Average Transaction: {{ number_format($analytics['avg_transaction']) }} VND</p>
        <p>Success Rate: {{ $analytics['success_rate'] }}%</p>
    </div>

    <h2>Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Order Code</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->created_at->format('d/m/Y') }}</td>
                <td>{{ $t->order_code }}</td>
                <td>{{ $t->product->name ?? 'N/A' }}</td>
                <td>{{ number_format($t->amount) }}</td>
                <td>{{ ucfirst($t->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>