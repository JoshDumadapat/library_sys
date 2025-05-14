<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #246484;
            padding-bottom: 20px;
        }

        .report-title {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
            color: #246484;
        }

        .report-subtitle {
            font-size: 16px;
            margin-bottom: 5px;
            color: #6c757d;
        }

        .report-details {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 14px;
        }

        th {
            background-color: #246484;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f1f8ff;
            border-radius: 8px;
            border-left: 4px solid #246484;
        }

        .summary h3 {
            color: #246484;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .summary p {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 70px;
            text-align: center;
        }

        .badge-paid {
            background-color: #28a745;
            color: white;
        }

        .badge-unpaid {
            background-color: #dc3545;
            color: white;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 15px;
        }

        .print-btn {
            background-color: #246484;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }

        .print-btn:hover {
            background-color: #1a4f6b;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .report-container {
                box-shadow: none;
                padding: 0;
            }

            .action-buttons {
                display: none;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="header">
            <div class="report-title">Fines Management Report</div>
            <div class="report-subtitle">Generated on: {{ $reportDate }}</div>
            @if($filterStatus != 'all')
            <div class="report-subtitle">Filter: {{ ucfirst($filterStatus) }} fines only</div>
            @endif
        </div>

        <div class="report-details">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Member</th>
                        <th>Fines Count</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['transaction_id'] }}</td>
                        <td>{{ $transaction['member_name'] }}</td>
                        <td>{{ $transaction['fines_count'] }}</td>
                        <td>₱{{ number_format($transaction['total_amount'], 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $transaction['status'] }}">
                                {{ ucfirst($transaction['status']) }}
                            </span>
                        </td>
                        <td>{{ $transaction['transaction_date'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary">
            <h3>Summary</h3>
            <p><strong>Total Fines Amount:</strong> ₱{{ number_format($totalAmount, 2) }}</p>
            <p><strong>Paid Fines:</strong> ₱{{ number_format($paidAmount, 2) }}</p>
            <p><strong>Unpaid Fines:</strong> ₱{{ number_format($unpaidAmount, 2) }}</p>
            <p><strong>Pending Fines:</strong> ₱{{ number_format($pendingAmount, 2) }}</p>
        </div>

        <div class="footer">
            <p>Generated by Library Management System</p>
        </div>

        <div class="action-buttons">
            <button class="print-btn" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Bootstrap Icons for the print button -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <script>
        // Auto-print when page loads if print parameter exists
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                window.print();
            }
        });
    </script>
</body>

</html>