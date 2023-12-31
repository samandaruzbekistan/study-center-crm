@extends('cashier.header')


@section('subjects')
    active
@endsection
@section('section')



    <main class="content forma" style="padding-bottom: 0; display: none">
        <div class="container-fluid p-0">
            <div class="col-md-8 col-xl-9">
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Yangi guruh ochish</h5>
                        </div>
                        <div class="card-body h-100">
                            <form action="{{ route('cashier.new.subject') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nomi <span class="text-danger">*</span></label>
                                    <input name="name" required type="text" class="form-control" placeholder="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Narxi <span class="text-danger">*</span></label>
                                    <input name="price" required type="number" class="form-control" placeholder="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Darslar soni <span class="text-danger">*</span></label>
                                    <input name="lessons_count" required type="number" value="12" class="form-control" placeholder="">
                                </div>
                                <label class="form-label">O'qituvchi <span class="text-danger">*</span></label>
                                <select class="form-select mb-3" name="teacher_id">
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
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

    <main class="content forma2" style="padding-bottom: 0; display: none">
        <div class="container-fluid p-0">
            <div class="col-md-8 col-xl-9">
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Guruhni taxrirlash</h5>
                        </div>
                        <div class="card-body h-100">
                            <form action="{{ route('cashier.edit.subject') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nomi <span class="text-danger">*</span></label>
                                    <input name="name" id="nomi" required type="text" class="form-control" placeholder="">
                                </div>
                                <input type="hidden" name="subject_id" id="sb_id">
                                <div class=" text-end">
                                    <button type="button" class="btn btn-danger cancel3">Bekor qilish</button>
                                    <button type="submit" class="btn btn-success">Taxrirlash</button>
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
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Guruhlar ro'yhati</h5>
                            </div>
                            <div class="col-6 text-end">
                                <i class="align-middle" data-feather="filter"></i>
                                <select class="form-select mb-3" style="width: auto; display: inline-block" id="teacher">
                                    <option value="all">Barchasi</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary add ms-2">+ Yangi guruh</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nomi</th>
                            <th>Narxi</th>
                            <th>O'qituvchi</th>
                            <th>Taxrirlash</th>
                            <th>O'chirish</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    <a href="{{ route('cashier.subject.students', ['subject_id' => $subject->id]) }}">{{ $subject->name }}</a>
                                </td>
                                <td><b>{{ number_format($subject->price, 0, '.', ' ') }}</b> so'm</td>
                                <td>{{ $subject->teacher->name }}</td>
                                <td><button id="{{ $subject->id }}" nomi="{{ $subject->name }}" price="{{ $subject->price }}" class="btn btn-warning text-dark edit-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></button></td>
                                <td><a href="{{ route('group.delete', ['id' => $subject->id]) }}" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
@endsection


@section('js')
    <script>
        $(document).on('click', '.edit-button', function () {
            let sb_id = $(this).attr('id');
            let nomi = $(this).attr('nomi');
            let price = $(this).attr('price');
            $('#nomi').val(nomi);
            $('#sb_id').val(sb_id);
            $('.forma2').show();
            $('.teachers').hide();
        });

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


        @if(session('add') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'Yangi guruh ochildi!',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif

        @if(session('edit') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'Guruh ma\'lumotlari taxrirlandi!',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif


        @if(session('attach_error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Guruhda o\'quvchilar mavjud emas!',
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
            message: 'Xatolik! Bunday login mavjud',
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

        $(".cancel3").on("click", function() {
            event.stopPropagation();
            $('.forma2').hide();
            $('.teachers').show();
        });

        $(".cancel1").on("click", function() {
            event.stopPropagation();
            $('.add-student').hide();
            $('.teachers').show();
        });

        @if(session('delete') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'Guruh tizimdan o\'chirildi',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif

        @if(session('not_delete') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Guruh o\'chirilmadi. To\'lovlari mavjud',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif
    </script>
@endsection
