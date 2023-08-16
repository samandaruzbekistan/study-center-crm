@extends('teacher.header')


@section('subjects')
    active
@endsection
@section('section')

    <main class="content teachers">
        <div class="container-fluid p-0">
            <div class="col-12 col-xl-12">
                <div class="card old">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Yo'qlamalar ro'yhati</h5>
                            </div>
                            <div class="col-6 text-end">
                                <h5 class="card-title mb-0"><button class="btn btn-primary" id="new">+ Qo'shish</button></h5>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped text-center table-hover">
                        <thead>
                        <tr>
                            <th>Sana</th>
                            <th>Tafsilotlar</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($attendances as $attendances)
                            <tr>
                                <td>
                                    {{ $attendances->date }}
                                </td>
                                <td><a class="btn btn-primary" href="{{ route('teacher.subject.detail',['id' => $subject_id]) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card forma" style="display: none">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4">
                                <h5 class="card-title mb-0">Yo'qlama</h5>
                            </div>
                            <div class="col-8 text-end">
                                <h5 class="card-title mb-0">Sana: <span class="text-danger">{{ date('d.m.Y') }} yil</span></h5>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('teacher.attendances.check') }}" method="post">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Kelamagan</th>
                                <th>Telefon</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                            @foreach($attachs as $attach)
                                <tr>
                                    <td>
                                        <label class="form-check">
                                            <input class="form-check-input" name="student_ids[]" type="checkbox" value="{{ $attach->student->id }}">
                                            <span class="form-check-label">
                                          {{ $attach->student->name }}
                                        </span>
                                        </label>
                                    </td>
                                    <td>{{ $attach->student->phone }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-end p-3">
                            <button type="button" class="btn btn-danger">Bekor qilish</button>
                            <button type="submit" class="btn btn-success">Saqlash</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection


@section('js')
    <script>
        $(document).on('click', '#new', function () {
            $('.old').hide();
            $('.forma').show();
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
            message: 'Malumotlar saqlandi',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif


        @if(session('error') == 1)
        const notyf = new Notyf();

        notyf.error({
            message: 'Malumotlar o\'zgarmaydi',
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

        $(".cancel1").on("click", function() {
            event.stopPropagation();
            $('.add-student').hide();
            $('.teachers').show();
        });

    </script>
@endsection
