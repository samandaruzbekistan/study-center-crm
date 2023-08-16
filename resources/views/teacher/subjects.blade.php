@extends('teacher.header')


@section('subjects')
    active
@endsection
@section('section')

    <main class="content teachers">
        <div class="container-fluid p-0">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Guruhlar ro'yhati</h5>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped text-center table-hover">
                        <thead>
                        <tr>
                            <th>Nomi</th>
                            <th>Davomat</th>
                            <th>Tafsilotlar</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    {{ $subject->name }}
                                </td>
                                <td><a class="btn btn-success" href="{{ route('teacher.attendances',['subject_id' => $subject->id]) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check align-middle me-2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg></a></td>
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
