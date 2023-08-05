@extends('cashier.header')

@section('students')
    active
@endsection
@section('section')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">O'quvchini guruhga biriktirish</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cashier.attach') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="inputEmail4">O'quvchi</label>
                                    <input readonly type="text" class="form-control" id="inputEmail4" placeholder="O'quvchi ismi" value="{{ $student->name }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="inputEmail4">Guruh</label>
                                    <select class="form-select "  id="subject">
                                        <option value="0">Tanlang...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="price">Kurs narxi</label>
                                    <input name="amount" type="number" required class="form-control" id="price" placeholder="" value="">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="inputAddress2">O'qituvchi</label>
                                    <input type="text" class="form-control" id="teacherName" readonly>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="inputCity">Kelgan sanasi</label>
                                    <input type="date" name="data" value="{{ date('Y-m-d') }}" class="form-control" id="inputCity">
                                </div>
                            </div>
                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                            <input type="hidden" name="teacher_id" id="teacher_id">
                            <input type="hidden" name="subject_id" id="subject_id">
                            <button type="button" class="btn btn-success" id="fake-button"><i class="align-middle" data-feather="user-check"></i> Biriktirish</button>
                            <button type="submit" id="submit-button" class="d-none"> Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('js')
    <script>
        $(document).on('change', '#subject', function() {
            let selectedId = $(this).val();
            $.ajax({
                url: '{{ route('cashier.subject') }}/' + selectedId,
                method: 'GET',
                success: function (data) {
                    $('#price').val(data.price);
                    $('#subject_id').val(data.id);
                    $('#teacher_id').val(data.teacher.id);
                    $('#teacherName').val(data.teacher.name);
                },
            });
        });

        $(document).on('click', '#fake-button', function() {
            let subject = $('#subject').val();
            if (subject == 0){
                const notyf = new Notyf();

                notyf.error({
                    message: 'Guruhni tanlang',
                    duration: 5000,
                    dismissible : true,
                    position: {
                        x : 'center',
                        y : 'top'
                    },
                });
            }
            else{
                $('#submit-button').click();
            }
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
    </script>
@endsection


