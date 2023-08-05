@extends('cashier.header')

@section('profile')
    active
@endsection
@section('section')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Profile</h1>

            </div>
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profil malumotlari</h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('img/avatars/'.$user->photo) }}" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128">
                            <h5 class="card-title mb-0">{{ $user->name }}</h5>
                            <div class="text-muted mb-2">Kassir</div>

                            <div>
                                <a class="btn btn-primary btn-sm" href="#">Follow</a>
                                <a class="btn btn-primary btn-sm" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Message</a>
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">
                            <h5 class="h6 card-title">Vazifa</h5>
                            <a href="{{ route('cashier.home') }}" class="badge bg-primary me-1 my-1">To'lovlar</a>
                            <a href="{{ route('cashier.subjects') }}" class="badge bg-primary me-1 my-1">Yangi guruh</a>
                            <a href="{{ route('cashier.students') }}" class="badge bg-primary me-1 my-1">O'quvchilar</a>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">
                            <h5 class="h6 card-title">About</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1">
                                    <i class="align-middle me-1" data-feather="user"></i>F.I.SH: <a href="#">{{ $user->name }}</a>
                                </li>
                                <li class="mb-1">
                                    <i class="align-middle me-1" data-feather="briefcase"></i>Ish joyi: <a href="#">Ideal Study</a></li>
                                <li class="mb-1"><i class="align-middle me-1" data-feather="phone"></i>Telefon: <a href="#">{{ $user->phone }}</a></li>
                            </ul>
                        </div>
                        <hr class="my-0">
                        {{--                        <div class="card-body">--}}
                        {{--                            <h5 class="h6 card-title">Elsewhere</h5>--}}
                        {{--                            <ul class="list-unstyled mb-0">--}}
                        {{--                                <li class="mb-1"><a href="#">staciehall.co</a></li>--}}
                        {{--                                <li class="mb-1"><a href="#">Twitter</a></li>--}}
                        {{--                                <li class="mb-1"><a href="#">Facebook</a></li>--}}
                        {{--                                <li class="mb-1"><a href="#">Instagram</a></li>--}}
                        {{--                                <li class="mb-1"><a href="#">LinkedIn</a></li>--}}
                        {{--                            </ul>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
                <div class="col-md-8 col-xl-9">
                    <div class="">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Parolni yangilash</h5>
                            </div>
                            <div class="card-body h-100">
                                <form action="{{ route('cashier.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="username" value="{{ $user->username }}">
                                    <div class="mb-3">
                                        <label class="form-label">Yangi parol</label>
                                        <input required name="password1" type="password" class="form-control" placeholder="">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Yangi parolni takrorlang</label>
                                        <input required name="password2" type="password" class="form-control" placeholder="">
                                    </div>
                                    <div class=" text-end">
                                        <button type="submit" class="btn btn-primary">Yangilash</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Profil rasmini yangilash</h5>
                            </div>
                            <div class="card-body h-100">
                                <form action="{{ route('cashier.avatar') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="">
                                        <label class="form-label">Yangi rasm</label>
                                        <input class="form-control" type="file" name="photo" accept="image/*">
                                    </div>
                                    <small class="text-danger">Rasm hajmi 2 mb dan oshmasligi kerak</small>
                                    <div class=" text-end">
                                        <button type="submit" class="btn btn-primary">Yangilash</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection


@section('js')
    <script>
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

        @if(session('success') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'Parol muvaffaqiyatli yangilandi!',
            duration: 5000,
            dismissible : true,
            position: {
                x : 'center',
                y : 'top'
            },
        });
        @endif

        @if(session('success_photo') == 1)
        const notyf = new Notyf();

        notyf.success({
            message: 'Profil rasmi muvaffaqiyatli yangilandi!',
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
