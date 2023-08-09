@extends('cashier.header')

@push('css')
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
            background: #364E63;
            color: #fff;
        }
        .pagination li:first-child {
            border-left-width: 1px;
        }
    </style>
@endpush

@section('students')
    active
@endsection
@section('section')


    <main class="content forma" style="padding-bottom: 0; display: none">
        <div class="container-fluid p-0">
            <div class="col-md-8 col-xl-9">
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Yangi o'quvchi qo'shish</h5>
                        </div>
                        <div class="card-body h-100">
                            <form action="{{ route('cashier.new.student') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">F.I.Sh <span class="text-danger">*</span></label>
                                    <input name="name" required type="text" class="form-control" placeholder="">
                                </div>
                                <label class="form-label">Telefon <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">+998</span>
                                    <input type="number" required name="phone" maxlength="9" class="form-control">
                                </div>
                                <div class=" text-end">
                                    <button type="button" class="btn btn-danger cancel">Bekor qilish</button>
                                    <button type="submit" class="btn btn-success">Qo'shish</button>
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
                        <h5 class="card-title mb-0"><span class="text-danger">{{ $attachs[0]->subject_name }}</span> guruhi haqida malumotlar</h5>
                        <div class="card-actions float-end" role="tablist"  >
                            <a class="btn btn-sm btn-light active" data-bs-toggle="list" href="#students" role="tab" aria-selected="true">
                                O'quvchilar
                            </a>
                            <a class="btn btn-sm btn-light" data-bs-toggle="list" href="#payments" role="tab" aria-selected="true">
                                To'lov jadvali
                            </a>

                            <a class="btn btn-sm btn-light" data-bs-toggle="list" href="#payments_by_month" role="tab" aria-selected="true">
                                To'lovlar
                            </a>


                            <input type="radio" class="btn-check" name="btnradio" id="30m" autocomplete="off">
                            <label class="btn btn-sm btn-light" for="30m">SMS xizmati</label>


                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade  " id="payments" role="tabpanel">
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Oy</th>
                                    <th>To'langan</th>
                                    <th>To'lanmagan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $id => $payment)
                                    <tr>
                                        <td>
                                            {{ $id+1 }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                                        <td class="text-success">{{  number_format($payment->total, 0, '.', ' ') }} so'm</td>
                                        <td class="text-danger">{{  number_format($payment->debt, 0, '.', ' ') }} so'm</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class=" tab-pane fade active show " id="students">
                            <table class="table table-striped table-hover w-100 table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>F.I.Sh</th>
                                    <th class="d-none d-sm-table-cell">Telefon</th>
                                    <th>Guruhga qo'shish</th>
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                @foreach($attachs as $id => $attach)
                                    <tr>
                                        <td>
                                            {{ $id+1 }}
                                        </td>
                                        <td><a href="{{ route('cashier.student') }}/{{ $attach->id }}">{{ $attach->student->name }}</a></td>
                                        <td class="d-none d-sm-table-cell">+{{ preg_replace('/(\d{3})(\d{2})(\d{3})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $attach->student->phone) }}</td>
                                        <td style="cursor: pointer"><a href="{{ route('cashier.add_to_subject') }}/{{ $attach->id }}" class="btn btn-success add-student"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus align-middle"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></a></td>
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
                                        <td class="text-success">{{  number_format($payment->total, 0, '.', ' ') }} so'm</td>
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
        $(document).on('click', '.new-student', function () {
            let sb_id = $(this).attr('id');
            $.ajax({
                url: '{{ route('cashier.subject') }}/' + sb_id,
                method: 'GET',
                success: function(data) {
                    $('#name').text(data.name);

                    $('.add-student').show();
                    $('.teachers').hide();
                }
            });
        });

        $(document).on('change', '#teacher', function() {
            let selectedId = $(this).val();
            if(selectedId === 'all'){
                window.location = "{{ route('cashier.subjects') }}";
            }
            $("#tbody").empty();

            $.ajax({
                url: '{{ route('cashier.teacher.subjects') }}/' + selectedId,
                method: 'GET',
                success: function(data) {
                    const tableBody = $("#tbody");
                    data.subjects.forEach(subject => {
                        const newRow = `
                            <tr>
                                <td>${subject.name}</td>
                                <td><b>${subject.price} so'm</b></td>
                                <td>${data.name}</td>
                                <td>${subject.lessons_count}</td>
                                <td class="edit-btn" style="cursor: pointer">
                                    <button class="btn btn-success new-student" id="${subject.id}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus align-middle">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="20" y1="8" x2="20" y2="14"></line>
                                            <line x1="23" y1="11" x2="17" y2="11"></line>
                                        </svg> Yangi o'quvchi
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(newRow);
                    });

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
            message: 'Yangi o\'quvchi qo\'shildi!',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif

        @if(session('username_error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Xatolik! Bunday ism mavjud',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif

        $(".add").on("click", function() {
            $('.forma').show();
            $('.teachers').hide();
        });

        $(".cancel").on("click", function() {
            event.stopPropagation();
            $('.forma').hide();
            $('.teachers').show();
        });

        $(".cancel1").on("click", function() {
            event.stopPropagation();
            $('.add-student').hide();
            $('.teachers').show();
        });

    </script>
@endsection
