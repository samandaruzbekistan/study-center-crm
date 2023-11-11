@extends('teacher.header')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <style>
        .pagination {
            height: 36px;
            margin: 0;
            padding: 0;
        }

        .pager, .pagination ul {
            margin-left: 0;
            *zoom: 1
        }

        .pagination ul {
            padding: 0;
            display: inline-block;
            *display: inline;
            margin-bottom: 0;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05)
        }

        .pagination li {
            display: inline
        }

        .pagination a {
            float: left;
            padding: 0 12px;
            line-height: 30px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-left-width: 0
        }

        .pagination .active a, .pagination a:hover {
            background-color: #f5f5f5;
            color: #94999E
        }

        .pagination .active a {
            color: #94999E;
            cursor: default
        }

        .pagination .disabled a, .pagination .disabled a:hover, .pagination .disabled span {
            color: #94999E;
            background-color: transparent;
            cursor: default
        }

        .pagination li:first-child a, .pagination li:first-child span {
            border-left-width: 1px;
            -webkit-border-radius: 3px 0 0 3px;
            -moz-border-radius: 3px 0 0 3px;
            border-radius: 3px 0 0 3px
        }

        .pagination li:last-child a {
            -webkit-border-radius: 0 3px 3px 0;
            -moz-border-radius: 0 3px 3px 0;
            border-radius: 0 3px 3px 0
        }

        .pagination-centered {
            text-align: center
        }

        .pagination-right {
            text-align: right
        }

        .pager {
            margin-bottom: 18px;
            text-align: center
        }

        .pager:after, .pager:before {
            display: table;
            content: ""
        }

        .pager li {
            display: inline
        }

        .pager a {
            display: inline-block;
            padding: 5px 12px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            border-radius: 15px
        }

        .pager a:hover {
            text-decoration: none;
            background-color: #f5f5f5
        }

        .pager .next a {
            float: right
        }

        .pager .previous a {
            float: left
        }

        .pager .disabled a, .pager .disabled a:hover {
            color: #999;
            background-color: #fff;
            cursor: default
        }

        .pagination .prev.disabled span {
            float: left;
            padding: 0 12px;
            line-height: 30px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-left-width: 0
        }

        .pagination .next.disabled span {
            float: left;
            padding: 0 12px;
            line-height: 30px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-left-width: 0
        }

        .pagination li.active, .pagination li.disabled {
            float: left;
            padding: 0 12px;
            line-height: 30px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-left-width: 0
        }

        .pagination li.active {
            background: #364E63;
            color: #fff;
        }

        .pagination li:first-child {
            border-left-width: 1px;
        }
    </style>
@endpush

@section('subjects')
    active
@endsection
@section('section')

    <main class="content edit-forma" style="padding-bottom: 0; display: none">
        <div class="container-fluid p-0">
            <div class="col-md-8 col-xl-9">
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><span id="st_name" class="text-danger"></span> ni boshqa guruhga ko'chirish</h5>
                        </div>
                        <div class="card-body h-100">
                            <form action="{{ route('teacher.move.student') }}" method="post">
                                @csrf
                                <input type="hidden" name="attach_id" id="attach_id">
                                <div class="mb-3">
                                    <label for="district" class="form-label">Sinf</label> <sup class="text-danger">*</sup>
                                    <select  name="class_id" required class="form-select">
                                        <option disabled="" selected="" hidden>Tanlang</option>
                                        @foreach($subjects as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" text-end">
                                    <button type="button" class="btn btn-danger cancel1">Bekor qilish</button>
                                    <button type="submit" class="btn btn-success">Ko'chirish</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <main class="content teachers">
        <div class="container-fluid p-0">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-3"><span class="text-danger">{{ $attachs[0]->subject_name }}</span>
                            guruhi haqida malumotlar</h5>
                        <div class="card-actions float-end" role="tablist">
                            <a class="btn btn-sm btn-light active" data-bs-toggle="list" href="#students" role="tab"
                               aria-selected="true">
                                O'quvchilar
                            </a>
                            <a class="btn btn-sm btn-light" data-bs-toggle="list" href="#payments" role="tab"
                               aria-selected="true">
                                To'lov jadvali
                            </a>

                            <a class="btn btn-sm btn-light" data-bs-toggle="list" href="#payments_by_month" role="tab"
                               aria-selected="true">
                                Tushumlar
                            </a>
                            <a class="btn btn-sm btn-light" data-bs-toggle="list" href="#sms" role="tab"
                               aria-selected="true">
                                SMS xizmati
                            </a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade  " id="payments" role="tabpanel">
                            <table class="table table-striped table-hover table-responsive" id="old-data">
                                <thead>
                                <tr>
                                    <th>Oy</th>
                                    <th>To'langan</th>
                                    <th>To'lanmagan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $id => $payment)
                                    <tr>
                                        <td><a href="#" class="detail" subject_id="{{ $attachs[0]->subject_id }}"
                                               month="{{ $payment->month }}">{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</a>
                                        </td>
                                        <td class="text-success">{{  number_format($payment->total, 0, '.', ' ') }}
                                            so'm
                                        </td>
                                        <td class="text-danger">{{  number_format($payment->debt, 0, '.', ' ') }}so'm
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="p-3" id="new-data" style="display: none">
                                <table class="table table-striped table-hover table-responsive"
                                       id="tbl_exporttable_to_xls">
                                    <thead>
                                    <tr>
                                        <td>O'qituvchi:</td>
                                        <th id="teacher_name">O'qituvchi</th>
                                    </tr>
                                    <tr>
                                        <td>O'quv oyi:</td>
                                        <th id="month_name">O'qituvchi</th>
                                    </tr>
                                    <tr>
                                        <td>Guruh:</td>
                                        <th id="group_name">O'qituvchi</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ismi</th>
                                        <th>To'langan</th>
                                        <th>Qarzdorlik</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    </tbody>
                                </table>
                                <div class="text-end">
                                    <button class="btn btn-success" onclick="ExportToExcel('xlsx')">Excel yuklash
                                    </button>
                                    <button class="btn btn-primary" id="back">Orqaga</button>
                                </div>
                            </div>
                        </div>
                        <div class=" tab-pane fade  " id="sms">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col-6">
                                        <h6 class="card-title">Barchaga sms jo'natish</h6>
                                        <form action="{{ route('sms.subject') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="subject_id"
                                                   value="{{ $attachs[0]->subject_id }}">
                                            <textarea required name="message" class="form-control" rows="3"
                                                      placeholder="Xabar matni"></textarea>
                                            <input type="submit" class="btn btn-success mt-3" value="yuborish">
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="card-title">Qarzdorlarga sms jo'natish</h6>
                                        <form action="{{ route('cashier.sms.debt') }}" method="post">
                                            <input type="hidden" name="subject_id"
                                                   value="{{ $attachs[0]->subject_id }}">
                                            <select class="form-select mb-3" name="month" id="monthInput">
                                                <option selected="" value="all">Oyni tanlang</option>
                                                @foreach($payments as $id => $payment)
                                                    @if($payment->debt > 0)
                                                        <option
                                                            value="{{ $payment->month }}">{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @csrf
                                            <textarea required name="message" class="form-control" rows="3"
                                                      placeholder="Xabar matni"></textarea>
                                            <button type="submit" style="display: none" id="realButton">s</button>
                                            <button type="button" class="btn btn-success mt-3" id="fakeButton">
                                                Yuborish
                                            </button>
                                        </form>
                                    </div>
                                    {{--                                    <div class="col mt-3 border-top bg-light">--}}
                                    {{--                                        <table class="table table-hover">--}}
                                    {{--                                            <thead>--}}
                                    {{--                                            <tr>--}}
                                    {{--                                                <th>Kelamagan</th>--}}
                                    {{--                                                <th>Telefon</th>--}}
                                    {{--                                            </tr>--}}
                                    {{--                                            </thead>--}}
                                    {{--                                            <form action="{{ route('teacher.attendances.check') }}" method="post">--}}
                                    {{--                                                @csrf--}}
                                    {{--                                            <tbody id="tbody">--}}
                                    {{--                                            @foreach($attachs as $attach)--}}
                                    {{--                                                <tr>--}}
                                    {{--                                                    <td>--}}
                                    {{--                                                        <label class="form-check">--}}
                                    {{--                                                            <input class="form-check-input" name="student_ids[]" type="checkbox" value="{{ $attach->student->id }}">--}}
                                    {{--                                                            <span class="form-check-label">--}}
                                    {{--                                                              {{ $attach->student->name }}--}}
                                    {{--                                                            </span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                    </td>--}}
                                    {{--                                                    <td>{{ $attach->student->phone }}</td>--}}
                                    {{--                                                </tr>--}}
                                    {{--                                            @endforeach--}}
                                    {{--                                            </tbody>--}}
                                    {{--                                            </form>--}}
                                    {{--                                        </table>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                        <div class=" tab-pane fade active show " id="students">
                            <table class="table table-striped table-hover w-100 table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>F.I.Sh</th>
                                    <th class="d-none d-sm-table-cell">Telefon</th>
                                    <th>Ko'chirish</th>
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                @foreach($attachs as $id => $attach)
                                    <tr>
                                        <td>
                                            {{ $id+1 }}
                                        </td>
                                        <td>{{ $attach->student->name }}</td>
                                        <td class="d-none d-sm-table-cell">
                                            +{{ preg_replace('/(\d{3})(\d{2})(\d{3})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $attach->student->phone) }}</td>
                                        <td>
                                            <button id="{{ $attach->id }}" ismi="{{ $attach->student->name }}"
                                                    class="btn btn-info transfer-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-refresh-ccw align-middle">
                                                    <polyline points="1 4 1 10 7 10"></polyline>
                                                    <polyline points="23 20 23 14 17 14"></polyline>
                                                    <path
                                                        d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class=" tab-pane fade active " id="payments_by_month">
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sana</th>
                                    <th>Summa</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payments_success as $id => $payment)
                                    <tr>
                                        <td>
                                            {{ $id+1 }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                                        <td class="text-success">{{  number_format($payment->total, 0, '.', ' ') }}
                                            so'm
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection


@section('js')
    <script>
        $(".transfer-btn").on("click", function() {
            let id = $(this).attr('id');
            let ism = $(this).attr('ismi');
            $('#attach_id').val(id);
            $('#st_name').text(ism);
            $('.edit-forma').show();
            $('.teachers').hide();
        });

        $(".cancel1").on("click", function() {
            event.stopPropagation();
            $('.edit-forma').hide();
            $('.teachers').show();
        });



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
        });

        $(document).on('click', '#fakeButton', function () {
            let val = $('#monthInput').val();
            if (val === "all") {
                const notyf = new Notyf();

                notyf.error({
                    message: 'O\'quv oyi tanlamadi',
                    duration: 5000,
                    dismissible: true,
                    position: {
                        x: 'center',
                        y: 'top'
                    },
                });
            } else {
                $('#realButton').click();
            }
        });


        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
            return dl ?
                XLSX.write(wb, {bookType: type, bookSST: true, type: 'base64'}) :
                XLSX.writeFile(wb, fn || ('xisobot.' + (type || 'xlsx')));
        }

        $(document).on('click', '.detail', function () {
            let month = $(this).attr('month');
            let subject_id = $(this).attr('subject_id');

            let formattedMonth = moment(month).locale('uz').format('MMMM YYYY');

            $.ajax({
                url: '{{ route('teacher.payment.details') }}',
                method: 'GET',
                data: {month: month, subject_id: subject_id},
                success: function (data) {
                    $('#teacher_name').text(data[0].teacher.name);
                    $('#month_name').text(formattedMonth);
                    $('#group_name').text(data[0].subject.name);
                    const tableBody = $("#tbody");
                    tableBody.empty();
                    let countdown = 0;
                    data.forEach(detail => {
                        countdown++;
                        const newRow = `
                            <tr>
                                <td>${countdown}</td>
                                <td>${detail.student.name}</td>
                                <td><b>${detail.amount_paid}</b> so'm</td>
                                <td><b>${detail.amount}</b> so'm</td>
                            </tr>
                        `;
                        tableBody.append(newRow);
                    });
                    $('#old-data').hide();
                    $('#new-data').show();
                }
            });
        });

        @if($errors->any())
        const notyf = new Notyf();

        @foreach ($errors->all() as $error)
        notyf.error({
            message: '{{ $error }}',
            duration: 5000,
            dismissible: true,
            position: {
                x: 'center',
                y: 'top'
            },
        });
        @endforeach

        @endif


        @if(session('success') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'SMS xabar yuborildi',
            duration: 5000,
            dismissible: true,
            position: {
                x: 'center',
                y: 'top'
            },
        });
        @endif

        @if(session('error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Xatolik! Balansni tekshiring',
            duration: 5000,
            dismissible: true,
            position: {
                x: 'center',
                y: 'top'
            },
        });
        @endif

        @if(session('username_error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Xatolik! Bunday ism mavjud',
            duration: 5000,
            dismissible: true,
            position: {
                x: 'center',
                y: 'top'
            },
        });
        @endif
    </script>
@endsection
