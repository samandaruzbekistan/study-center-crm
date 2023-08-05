
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

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
            <a class="sidebar-brand" href="/">
                <span class="align-middle">Ideal Study <br>O'quv markazi</span>
            </a>

            <ul class="sidebar-nav">


                <li class="sidebar-item @yield('home')">
                    <a class="sidebar-link" href="{{ route('cashier.home') }}">
                        <i class="align-middle" data-feather="arrow-down-circle"></i> <span class="align-middle">To'lov</span>
                    </a>
                </li>




                <li class="sidebar-header">
                    Boshqaruv
                </li>

                <li class="sidebar-item @yield('subjects')">
                    <a class="sidebar-link" href="{{ route('cashier.subjects') }}">
                        <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Guruhlar</span>
                    </a>
                </li>

                <li class="sidebar-item @yield('students')">
                    <a class="sidebar-link" href="{{ route('cashier.students') }}">
                        <i class="align-middle" data-feather="users"></i> <span class="align-middle">O'quvchilar</span>
                    </a>
                </li>

                <li class="sidebar-item @yield('profile')">
                    <a class="sidebar-link" href="{{ route('cashier.profile') }}">
                        <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profil</span>
                    </a>
                </li>


                <li class="sidebar-header">
                    Malumotlar
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="charts-chartjs.html">
                        <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">To'lovlar</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="maps-google.html">
                        <i class="align-middle" data-feather="map"></i> <span class="align-middle">O'quvchilar</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="maps-google.html">
                        <i class="align-middle" data-feather="map"></i> <span class="align-middle">Darslar</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="maps-google.html">
                        <i class="align-middle" data-feather="map"></i> <span class="align-middle">Davomat</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Xizmatlar
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="maps-google.html">
                        <i class="align-middle" data-feather="map"></i> <span class="align-middle">Sms xizmati</span>
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
            <form class="d-none d-sm-inline-block">
                <div class="input-group input-group-navbar">
                    <input type="text" id="modalSearchInput" class="form-control" placeholder="Search…" aria-label="Search">
                    <button class="btn" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </form>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle" data-feather="bell"></i>
                                <span class="indicator">4</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                            <div class="dropdown-menu-header">
                                4 New Notifications
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-danger" data-feather="alert-circle"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Update completed</div>
                                            <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                                            <div class="text-muted small mt-1">30m ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-warning" data-feather="bell"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Lorem ipsum</div>
                                            <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
                                            <div class="text-muted small mt-1">2h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-primary" data-feather="home"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Login from 192.186.1.8</div>
                                            <div class="text-muted small mt-1">5h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-success" data-feather="user-plus"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">New connection</div>
                                            <div class="text-muted small mt-1">Christina accepted your request.</div>
                                            <div class="text-muted small mt-1">14h ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all notifications</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle" data-feather="message-square"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                            <div class="dropdown-menu-header">
                                <div class="position-relative">
                                    4 New Messages
                                </div>
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div class="text-dark">Vanessa Tucker</div>
                                            <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                                            <div class="text-muted small mt-1">15m ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div class="text-dark">William Harris</div>
                                            <div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
                                            <div class="text-muted small mt-1">2h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div class="text-dark">Christina Mason</div>
                                            <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
                                            <div class="text-muted small mt-1">4h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div class="text-dark">Sharon Lessman</div>
                                            <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
                                            <div class="text-muted small mt-1">5h ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all messages</a>
                            </div>
                        </div>
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
                        referencesHtml += '<td>' + references[i].name + '</td>';
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
</script>
<div class="notyf" style="justify-content: flex-start; align-items: center;"></div>
<div class="notyf-announcer" aria-atomic="true" aria-live="polite" style="border: 0px; clip: rect(0px, 0px, 0px, 0px); height: 1px; margin: -1px; overflow: hidden; padding: 0px; position: absolute; width: 1px; outline: 0px;">Inconceivable!</div>
</body>

</html>
