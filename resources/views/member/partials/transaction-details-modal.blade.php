<h4>Transaction ID: {{ $transaction->trans_ID }}</h4>

<p><strong>Borrow Date:</strong> {{ \Carbon\Carbon::parse($transaction->borrow_date)->format('F j, Y') }}</p>
<p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($transaction->due_date)->format('F j, Y') }}</p>
<p><strong>Return Date:</strong>
    @if($transaction->return_date)
    {{ \Carbon\Carbon::parse($transaction->return_date)->format('F j, Y') }}
    @else
    Not returned yet
    @endif
</p>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaction->transDetails as $detail)
        <tr>
            <td>{{ $detail->book->title ?? 'N/A' }}</td>
            <td>{{ $detail->td_status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>