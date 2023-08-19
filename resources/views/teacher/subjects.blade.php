@extends('teacher.header')


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
                            <form action="{{ route('teacher.new.subject') }}" method="post">
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
                                <button class="btn btn-primary add ms-2">+ Yangi guruh</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped text-center table-hover">
                        <thead>
                        <tr>
                            <th>Nomi</th>
                            <th>Tafsilotlar</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    {{ $subject->name }}
                                </td>
                                <td><a class="btn btn-primary" href="{{ route('teacher.subject.detail',['id' => $subject->id]) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
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

        $(".cancel1").on("click", function() {
            event.stopPropagation();
            $('.add-student').hide();
            $('.teachers').show();
        });
    </script>
@endsection
