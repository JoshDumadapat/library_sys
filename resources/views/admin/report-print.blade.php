<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lending Report</title>
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

        .badge-borrowed {
            background-color: #0d6efd;
            color: white;
        }

        .badge-overdue {
            background-color: #dc3545;
            color: white;
        }

        .badge-returned {
            background-color: #28a745;
            color: white;
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
            <div class="report-title">Lending Report</div>
            <div class="report-subtitle">Generated on: {{ $reportDate }}</div>
            @if($search || $from_date || $to_date)
            <div class="report-subtitle">
                Filters:
                {{ $search ? "Search: $search" : '' }}
                {{ $from_date ? " | From: " . \Carbon\Carbon::parse($from_date)->format('F j, Y') : '' }}
                {{ $to_date ? " | To: " . \Carbon\Carbon::parse($to_date)->format('F j, Y') : '' }}
            </div>
            @endif
        </div>

        <div class="report-details">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Member</th>
                        <th>Date Borrowed</th>
                        <th>Due Date</th>
                        <th>Date Returned</th>
                        <th>Total Books</th>
                        <th>Fines</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->trans_ID }}</td>
                        <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->borrow_date)->format('F j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->due_date)->format('F j, Y') }}</td>
                        <td>{{ $report->return_date ? \Carbon\Carbon::parse($report->return_date)->format('F j, Y') : 'Not returned' }}</td>
                        <td>{{ $report->total_books }}</td>
                        <td>₱{{ number_format($report->total_fines, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $report->status }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary">
            <h3>Summary</h3>
            <p><strong>Total Books Borrowed:</strong> {{ $totalBooks }}</p>
            <p><strong>Total Fines Generated:</strong> ₱{{ number_format($totalFines, 2) }}</p>
        </div>

        <div class="footer">
            <p>Generated by Library Management System</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>