<!DOCTYPE html>
<html>
<head>
    <title>AWS Cost Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>AWS Cost Report - {{ $month }}</h2>

    <table>
        <thead>
            <tr>
                <th>Linked Account</th>
                <th>Total Cost (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $row)
                <tr>
                    <td>{{ $row->LinkedAccountName }}</td>
                    <td>${{ number_format($row->total_cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
