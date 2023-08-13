
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    @stack('css')

    <title>Admin | Ideal Study</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/new-style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="/teacher/home">
                <img src="{{ asset('logo-white.png') }}" alt="Charles Hall" class="img-fluid " width="250">
            </a>

            <ul class="sidebar-nav">




                <li class="sidebar-header">
                    Boshqaruv
                </li>

                <li class="sidebar-item @yield('subjects')">
                    <a class="sidebar-link" href="{{ route('teacher.home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid align-middle"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> <span class="align-middle">Guruhlarim</span>
                    </a>
                </li>



                <li class="sidebar-item">
                    <a class="sidebar-link" href="maps-google.html">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check align-middle"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> <span class="align-middle">Davomat</span>
                    </a>
                </li>

                <li class="sidebar-item @yield('profile')">
                    <a class="sidebar-link" href="{{ route('cashier.profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user align-middle"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span class="align-middle">Profil</span>
                    </a>
                </li>
            </ul>


        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item">
                        <a class="nav-icon d-none d-lg-block" href="#" id="fullscreenLink">
                            <div class="position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize align-middle"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>

                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                            <img src="{{ asset('/img/avatars/') }}/{{ session('photo') }}" class="avatar img-fluid rounded me-1" alt="user photo" /> <span class="text-dark">{{ session('name') }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
                            <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('cashier.logout') }}">Chiqish</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="h-100" id="search-section" style="display: none">

        </div>

        <div class="h-100" id="main">
            @yield('section')
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0">
                            <a class="text-muted" href="#" target="_blank"><strong>Ideal Study</strong></a> - <a class="text-muted" href="#" target="_blank"><strong>O'quv markazi</strong></a>								&copy;
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a class="text-muted" href="https://t.me/Samandar_developer" target="_blank">Dasturchi: <span class="text-primary">Samandar Sariboyev</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('js')
<script>
    // Handle search input in modal
    $('#modalSearchInput').on('input', function() {
        var query = $(this).val();
        var loadingIndicator = $('#loadingIndicator');
        if (query.length === 0){
            $('#main').show();
            $('#search-section').hide();
        }
        if(query.length > 2){
            $('#search-section').empty();
            $.ajax({
                url: '{{ route('cashier.search') }}', // Replace with your backend route for handling search
                method: 'GET',
                data: { name: query },
                success: function(response) {
                    console.log(response);
                    var references = response; // Assign the response directly
                    var referencesHtml =
                        `<main class="content teachers">
                        <div class="container-fluid p-0">
                            <div class="col-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title mb-0">O'quvchilar ro'yhati</h5>
                                        <button class="btn btn-primary add ms-2">+ Yangi o'quvchi</button>
                                    </div>
                                    <table class="table table-striped table-hover table-responsive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>F.I.Sh</th>
                                            <th>Telefon</th>
                                            <th>Guruhga qo'shish</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">`;
                    let countdown = 0;
                    for (var i = 0; i < references.length; i++) {
                        countdown++;
                        referencesHtml += '<tr>';
                        referencesHtml += '<td>' + countdown + '</td>';
                        referencesHtml += '<td><a href="{{ route('cashier.student') }}/'+references[i].id+'">' + references[i].name + '</a></td>';
                        referencesHtml += '<td>' + references[i].phone + '</td>';
                        referencesHtml += `<td><a href="{{ route('cashier.add_to_subject') }}/`+references[i].id+`" class="btn btn-success add-student"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus align-middle"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></a></td>`;
                        referencesHtml += '</tr>';
                    }
                    referencesHtml +=   `</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>`;
                    // Display the references in the modal

                    $('#search-section').html(referencesHtml);
                    $('#main').hide();
                    $('#search-section').show();
                    // Hide the loading indicator
                    loadingIndicator.hide();
                },
                error: function() {
                    // Handle error case
                    // Hide the loading indicator
                    loadingIndicator.hide();
                }
            });
        }
        // Display the loading indicator
        loadingIndicator.show();

        // Make AJAX request to fetch search results


    });

    const fullscreenLink = document.getElementById('fullscreenLink');
    let isFullScreen = false;

    fullscreenLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default behavior of the link
        toggleFullScreen();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            exitFullScreen();
        }
    });

    function toggleFullScreen() {
        if (!isFullScreen) {
            requestFullScreen();
        } else {
            exitFullScreen();
        }
    }

    function requestFullScreen() {
        const element = document.documentElement;
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
        isFullScreen = true;
    }

    function exitFullScreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        isFullScreen = false;
    }
</script>
<div class="notyf" style="justify-content: flex-start; align-items: center;"></div>
<div class="notyf-announcer" aria-atomic="true" aria-live="polite" style="border: 0px; clip: rect(0px, 0px, 0px, 0px); height: 1px; margin: -1px; overflow: hidden; padding: 0px; position: absolute; width: 1px; outline: 0px;">Inconceivable!</div>
</body>

</html>
