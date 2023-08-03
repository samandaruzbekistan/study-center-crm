@extends('cashier.header')

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
                            <h5 class="card-title mb-0"><span id="name" class="text-danger"></span> ning parolni yangilash</h5>
                        </div>
                        <div class="card-body h-100">
                            <form action="{{ route('admin.update.teacher') }}" method="post">
                                @csrf
                                <input type="hidden" name="username" id="username">
                                <div class="mb-3">
                                    <label class="form-label">Yangi parol</label>
                                    <input required name="password1" type="password" class="form-control" placeholder="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Yangi parolni takrorlang</label>
                                    <input required name="password2" type="password" class="form-control" placeholder="">
                                </div>
                                <div class=" text-end">
                                    <button type="button" class="btn btn-danger cancel1">Bekor qilish</button>
                                    <button type="submit" class="btn btn-success">Yangilash</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                                <select class="form-select mb-3" style="width: auto; display: inline-block" name="teacher_id">
                                    <option value="all">Barchasi</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary add ms-2"><i class="align-middle" data-feather="user-plus"></i> Qo'shish</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nomi</th>
                            <th>Narxi</th>
                            <th>O'qituvchi</th>
                            <th>Darslar soni</th>
                            <th>Yangi o'quvchi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    {{ $subject->name }}
                                </td>
                                <td><b>{{ number_format($subject->price, 0, '.', ' ') }}</b> so'm</td>
                                <td>{{ $subject->teacher->name }}</td>
                                <td>{{ $subject->lessons_count }}</td>
                                <td class="edit-btn" style="cursor: pointer"><button class="btn btn-success"><i class="align-middle" data-feather="user-plus"></i> Yangi o'quvchi</button></td>
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
            $('.edit-forma').hide();
            $('.teachers').show();
        });

        $(".edit-btn").on("click", function() {
            let username = $(this).attr('id');
            let name = $(this).attr('data');
            $('#username').val(username);
            $('#name').text(name);
            $('.edit-forma').show();
            $('.teachers').hide();
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

        @if(session('password_error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Parollar bir xil emas!',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
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

        @if(session('change') == 2)
        const notyf = new Notyf();

        notyf.success({
            message: 'Parol muvaffaqiyatli o\'zgartirildi',
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
    </script>
@endsection
