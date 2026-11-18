<main class="content teachers">
    <div class="container-fluid p-0">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-2">
                        <div class="col-md-3">
                            <h5 class="card-title mb-0">Qarzdorlar jadvali</h5>
                            <small class="text-muted">Har bir o'quvchi va guruh bo'yicha oylik qarzdorlik (joriy oygacha)</small>
                        </div>
                        <div class="col-md-9">
                            <form method="GET" action="{{ route($indexRoute) }}" class="row g-2 justify-content-end">
                                <div class="col-auto">
                                    <label class="form-label mb-0 small">Dan</label>
                                    <input type="month" name="from" value="{{ $from }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-auto">
                                    <label class="form-label mb-0 small">Gacha</label>
                                    <input type="month" name="to" value="{{ $to }}" class="form-control form-control-sm">
                                </div>
                                @if($teacherList)
                                    <div class="col-auto">
                                        <label class="form-label mb-0 small">O'qituvchi</label>
                                        <select name="teacher_id" class="form-select form-select-sm">
                                            <option value="">Barcha o'qituvchilar</option>
                                            @foreach($teacherList as $teacher)
                                                <option value="{{ $teacher->id }}"
                                                    @if((string) $teacherId === (string) $teacher->id) selected @endif>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-auto">
                                    <label class="form-label mb-0 small">Guruh</label>
                                    <select name="subject_id" class="form-select form-select-sm">
                                        <option value="">Barcha guruhlar</option>
                                        @foreach($subjectList as $subject)
                                            <option value="{{ $subject->id }}"
                                                @if((string) $subjectId === (string) $subject->id) selected @endif>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto align-self-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Ko'rsatish</button>
                                    <a href="{{ route($exportRoute, array_filter(['from' => $from, 'to' => $to, 'subject_id' => $subjectId, 'teacher_id' => $teacherList ? $teacherId : null])) }}"
                                       class="btn btn-success btn-sm">Excel'ga yuklash</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="row mb-3 align-items-center g-2">
                        <div class="col-md-4">
                            <input type="text" id="debtSearch" class="form-control form-control-sm"
                                   placeholder="Qidirish: FISH, guruh{{ $teacherList ? " yoki o'qituvchi" : '' }}..." autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-secondary">
                                Qarzdorlar: <span id="debtCount">{{ count($rows) }}</span> ta
                            </span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-danger">
                                Umumiy qarz: <span id="debtSum">{{ number_format($grandTotal, 0, ',', ' ') }}</span> so'm
                            </span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark" id="debtSearchNote" style="display: none;">
                                qidiruv bo'yicha
                            </span>
                        </div>
                    </div>

                    @if(count($rows) === 0)
                        <div class="alert alert-info mb-0">
                            Tanlangan davrda qarzdor o'quvchi topilmadi.
                        </div>
                    @else
                        <div id="debtNoResult" class="alert alert-warning" style="display: none;">
                            Qidiruv bo'yicha hech narsa topilmadi.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm align-middle mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width: 45px;">#</th>
                                        <th style="min-width: 220px;">FISH</th>
                                        <th style="min-width: 120px;">Guruh</th>
                                        @foreach($months as $month)
                                            <th class="text-end" style="min-width: 90px;">{{ $monthLabels[$month] }}</th>
                                        @endforeach
                                        <th class="text-end" style="min-width: 110px;">Jami qarz</th>
                                        @if($teacherList)
                                            <th style="min-width: 160px;">O'qituvchi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $i => $row)
                                        <tr class="debt-row"
                                            data-search="{{ mb_strtolower($row['student_name'] . ' ' . $row['subject_name'] . ' ' . $row['teacher_name']) }}"
                                            data-total="{{ $row['total'] }}">
                                            <td class="debt-nr">{{ $i + 1 }}</td>
                                            <td>{{ $row['student_name'] }}</td>
                                            <td>{{ $row['subject_name'] }}</td>
                                            @foreach($months as $month)
                                                <td class="text-end debt-amount" data-amount="{{ $row['by_month'][$month] }}">
                                                    @if($row['by_month'][$month] > 0)
                                                        {{ number_format($row['by_month'][$month], 0, ',', ' ') }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-end">
                                                <strong>{{ number_format($row['total'], 0, ',', ' ') }}</strong>
                                            </td>
                                            @if($teacherList)
                                                <td>{{ $row['teacher_name'] }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="3" class="text-end">Jami:</th>
                                        @foreach($months as $month)
                                            <th class="text-end debt-month-total">
                                                {{ $monthTotals[$month] > 0 ? number_format($monthTotals[$month], 0, ',', ' ') : '—' }}
                                            </th>
                                        @endforeach
                                        <th class="text-end" id="debtGrandTotal">{{ number_format($grandTotal, 0, ',', ' ') }}</th>
                                        @if($teacherList)
                                            <th></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>

                <script>
                (function () {
                    var input = document.getElementById('debtSearch');
                    if (!input) return;

                    var rows         = Array.prototype.slice.call(document.querySelectorAll('.debt-row'));
                    var monthTotals  = document.querySelectorAll('.debt-month-total');
                    var grandTotalEl = document.getElementById('debtGrandTotal');
                    var countEl      = document.getElementById('debtCount');
                    var sumEl        = document.getElementById('debtSum');
                    var noteEl       = document.getElementById('debtSearchNote');
                    var noResultEl   = document.getElementById('debtNoResult');

                    function money(n) {
                        return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                    }

                    function apply() {
                        var q = input.value.trim().toLowerCase();
                        var visible = [];

                        rows.forEach(function (row) {
                            var match = q === '' || row.getAttribute('data-search').indexOf(q) !== -1;
                            row.style.display = match ? '' : 'none';
                            if (match) visible.push(row);
                        });

                        visible.forEach(function (row, i) {
                            var nr = row.querySelector('.debt-nr');
                            if (nr) nr.textContent = i + 1;
                        });

                        var grand = 0;
                        monthTotals.forEach(function (cell, col) {
                            var sum = 0;
                            visible.forEach(function (row) {
                                var amounts = row.querySelectorAll('.debt-amount');
                                if (amounts[col]) sum += parseFloat(amounts[col].getAttribute('data-amount')) || 0;
                            });
                            cell.textContent = sum > 0 ? money(sum) : '—';
                            grand += sum;
                        });

                        if (grandTotalEl) grandTotalEl.textContent = money(grand);
                        if (countEl) countEl.textContent = visible.length;
                        if (sumEl) sumEl.textContent = money(grand);
                        if (noteEl) noteEl.style.display = q === '' ? 'none' : '';
                        if (noResultEl) noResultEl.style.display = (q !== '' && visible.length === 0) ? '' : 'none';
                    }

                    input.addEventListener('input', apply);
                })();
                </script>
            </div>
        </div>
    </div>
</main>