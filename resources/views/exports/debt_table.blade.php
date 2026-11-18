<table>
    <thead>
    <tr>
        <th>FISH</th>
        <th>Guruh</th>
        @foreach($months as $month)
            <th>{{ $monthLabels[$month] }}</th>
        @endforeach
        <th>Jami qarz</th>
        <th>O'qituvchi</th>
        <th>Telefon</th>
        <th>Ota-ona telefoni</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row['student_name'] }}</td>
            <td>{{ $row['subject_name'] }}</td>
            @foreach($months as $month)
                <td>{{ $row['by_month'][$month] > 0 ? $row['by_month'][$month] : '' }}</td>
            @endforeach
            <td>{{ $row['total'] }}</td>
            <td>{{ $row['teacher_name'] }}</td>
            <td>{{ $row['phone'] }}</td>
            <td>{{ $row['parents_phone'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Jami</th>
        <th></th>
        @foreach($months as $month)
            <th>{{ $monthTotals[$month] }}</th>
        @endforeach
        <th>{{ $grandTotal }}</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
</table>
