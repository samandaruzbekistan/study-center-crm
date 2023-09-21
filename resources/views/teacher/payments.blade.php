@extends('teacher.header')
@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <style>
        .pagination{height:36px;margin:0;padding: 0;}
        .pager,.pagination ul{margin-left:0;*zoom:1}
        .pagination ul{padding:0;display:inline-block;*display:inline;margin-bottom:0;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.05);-moz-box-shadow:0 1px 2px rgba(0,0,0,.05);box-shadow:0 1px 2px rgba(0,0,0,.05)}
        .pagination li{display:inline}
        .pagination a{float:left;padding:0 12px;line-height:30px;text-decoration:none;border:1px solid #ddd;border-left-width:0}
        .pagination .active a,.pagination a:hover{background-color:#f5f5f5;color:#94999E}
        .pagination .active a{color:#94999E;cursor:default}
        .pagination .disabled a,.pagination .disabled a:hover,.pagination .disabled span{color:#94999E;background-color:transparent;cursor:default}
        .pagination li:first-child a,.pagination li:first-child span{border-left-width:1px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px;border-radius:3px 0 0 3px}
        .pagination li:last-child a{-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0}
        .pagination-centered{text-align:center}
        .pagination-right{text-align:right}
        .pager{margin-bottom:18px;text-align:center}
        .pager:after,.pager:before{display:table;content:""}
        .pager li{display:inline}
        .pager a{display:inline-block;padding:5px 12px;background-color:#fff;border:1px solid #ddd;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px}
        .pager a:hover{text-decoration:none;background-color:#f5f5f5}
        .pager .next a{float:right}
        .pager .previous a{float:left}
        .pager .disabled a,.pager .disabled a:hover{color:#999;background-color:#fff;cursor:default}
        .pagination .prev.disabled span{float:left;padding:0 12px;line-height:30px;text-decoration:none;border:1px solid #ddd;border-left-width:0}
        .pagination .next.disabled span{float:left;padding:0 12px;line-height:30px;text-decoration:none;border:1px solid #ddd;border-left-width:0}
        .pagination li.active, .pagination li.disabled {
            float:left;padding:0 12px;line-height:30px;text-decoration:none;border:1px solid #ddd;border-left-width:0
        }
        .pagination li.active {
            background: #3b7ddd;
            color: #fff;
        }
        .pagination li:first-child {
            border-left-width: 1px;
        }
    </style>
@endpush

@section('payments')
    active
@endsection
@section('section')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="card-title mb-0">To'lovlar ro'yhati</h5>
                            </div>
                            <div class="col-lg-6 d-flex justify-content-end">
                                <select class="form-select w-50"  id="month_id">
                                    <option value="0" disabled selected hidden>Oy ni tanlang</option>
                                    <option value="09">Sentabr</option>
                                    <option value="10">Oktabr</option>
                                    <option value="11">Noyabr</option>
                                    <option value="12">Dekabr</option>
                                    <option value="01">Yanvar</option>
                                    <option value="02">Fevral</option>
                                    <option value="03">Mart</option>
                                    <option value="04">Aprel</option>
                                    <option value="05">May</option>
                                    <option value="06">Iyun</option>
                                    <option value="07">Iyul</option>
                                    <option value="08">Avgust</option>
                                </select>
                                <button class="btn btn-danger add ms-2" id="back" style="display: none">Orqaga</button>
                                <button class="btn btn-success ms-2 " id="pdf" onclick="ExportToExcel('xlsx')" style="display: none"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save align-middle me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Excel export</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>O'quvchi</th>
                            <th>Narxi</th>
                            <th>Guruh</th>
                            <th>Sana</th>
                            <th>O'quv oyi</th>
                            <th>Turi</th>
                        </tr>
                        </thead>
                        <tbody id="old-data">
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('cashier.subject.students', ['subject_id' => $payment->id]) }}">{{ $payment->student->name }}</a>
                                </td>
                                <td><b>{{ number_format($payment->amount_paid, 0, '.', ' ') }}</b> so'm</td>
                                <td>{{ $payment->subject->name }}</td>
                                <td>{{ $payment->date }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                                @if($payment->type == 'cash')
                                    <td class=""><a href="#" class="badge bg-success me-1 my-1">Naqd</a></td>
                                @elseif($payment->type == 'credit_card')
                                    <td class=""><a href="#" class="badge bg-warning text-dark me-1 my-1">Karta</a></td>
                                @elseif($payment->type == 'click')
                                    <td class=""><a href="#" class="badge bg-info me-1 my-1">Click</a></td>
                                @else
                                    <td class=""><a href="#" class="badge bg-danger me-1 my-1">Bank</a></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tbody id="new-data" style="display: none"></tbody>
                    </table>
                </div>
                {{ $payments->links() }}

            </div>

        </div>
    </main>
    <input type="hidden" value="{{ session('id') }}" id="teacher_id">
@endsection

@section('js')
    <script>
        $(document).on('change', '#month_id', function () {
            let month = $('#month_id').val();
            let teacher_id = $('#teacher_id').val();
            let tableBody = $('#new-data');
            $.ajax({
                url: "{{ route('teacher.payment.month') }}",
                method: 'GET',
                data: {month:month,teacher_id:teacher_id},
                success: function(response) {
                    tableBody.empty();
                    response.forEach(payment => {
                        let formattedMonth = moment(payment.month).locale('uz').format('MMMM YYYY');
                        const formattedAmount = payment.amount_paid.toLocaleString('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });
                        let typeMoney = '';
                        console.log(payment.type)
                        if (payment.type === 'cash'){
                            typeMoney = `<td class=""><a href="#" class="badge bg-success me-1 my-1">Naqd</a></td>`;
                        }
                        else if(payment.type === 'credit_card'){
                            typeMoney = `<td class=""><a href="#" class="badge bg-warning text-dark me-1 my-1">Karta</a></td>`;
                        }
                        else if(payment.type === 'click'){
                            typeMoney = `<td class=""><a href="#" class="badge bg-info me-1 my-1">Click</a></td>`;
                        }
                        else{
                            typeMoney = `<td class=""><a href="#" class="badge bg-danger me-1 my-1">Bank</a></td>`;
                        }
                        const newRow = `
                            <tr>
                                <td>${payment.student.name}</td>
                                <td><b>${formattedAmount}</b></td>
                                <td>${payment.subject.name}</td>
                                <td>${payment.date}</td>
                                <td>${formattedMonth}</td>
                                ${typeMoney}
                            </tr>
                        `;
                        tableBody.append(newRow);
                    });
                    $('#old-data').hide();
                    $('#back').show();
                    $('#pdf').show();
                    $('#new-data').show();
                },
            });
        });

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('new-data');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || ('xisobot.' + (type || 'xlsx')));
        }

        (function (factory) {
            if (typeof define === 'function' && define.amd) {
                define(['moment'], factory); // AMD
            } else if (typeof exports === 'object') {
                module.exports = factory(require('../moment')); // Node
            } else {
                factory(window.moment); // Browser global
            }
        }(function (moment) {
            return moment.defineLocale('uz', {
                months: 'Yanvar_Fevral_Mart_Aprel_May_Iyun_Iyul_Avgust_Sentabr_Oktabr_Noyabr_Dekabr'.split('_'),
                monthsShort: 'янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек'.split('_'),
                weekdays: 'Якшанба_Душанба_Сешанба_Чоршанба_Пайшанба_Жума_Шанба'.split('_'),
                weekdaysShort: 'Якш_Душ_Сеш_Чор_Пай_Жум_Шан'.split('_'),
                weekdaysMin: 'Як_Ду_Се_Чо_Па_Жу_Ша'.split('_'),
                longDateFormat: {
                    LT: 'HH:mm',
                    L: 'DD/MM/YYYY',
                    LL: 'D MMMM YYYY',
                    LLL: 'D MMMM YYYY LT',
                    LLLL: 'D MMMM YYYY, dddd LT'
                },
                calendar: {
                    sameDay: '[Бугун соат] LT [да]',
                    nextDay: '[Эртага] LT [да]',
                    nextWeek: 'dddd [куни соат] LT [да]',
                    lastDay: '[Кеча соат] LT [да]',
                    lastWeek: '[Утган] dddd [куни соат] LT [да]',
                    sameElse: 'L'
                },
                relativeTime: {
                    future: 'Якин %s ичида',
                    past: 'Бир неча %s олдин',
                    s: 'фурсат',
                    m: 'бир дакика',
                    mm: '%d дакика',
                    h: 'бир соат',
                    hh: '%d соат',
                    d: 'бир кун',
                    dd: '%d кун',
                    M: 'бир ой',
                    MM: '%d ой',
                    y: 'бир йил',
                    yy: '%d йил'
                },
                week: {
                    dow: 1, // Monday is the first day of the week.
                    doy: 7  // The week that contains Jan 4th is the first week of the year.
                }
            });
        }));

        $(document).on('click', '#back', function () {
            $('#old-data').show();
            $('#new-data').hide();
            $('#back').hide();
            $('#pdf').hide();
        });

    </script>
@endsection
