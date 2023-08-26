<table>
    <thead>
    <tr>
        <th>Student</th>
        <th>Teacher</th>
        <th>Group</th>
        <th>O'quv oyi</th>
        <th>Qarzdorlik</th>
        <!-- Add more columns as needed -->
    </tr>
    </thead>
    <tbody>
    @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->student->name }}</td>
            <td>{{ $payment->teacher->name }}</td>
            <td>{{ $payment->attach->subject_name }}</td>
            <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
            <td>{{ $payment->amount }}</td>
            <!-- Add more columns as needed -->
        </tr>
    @endforeach
    </tbody>
</table>
