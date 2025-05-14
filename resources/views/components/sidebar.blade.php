<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">
    <title>LunaBooks | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @vite(['resources/css/sidebar.css'])
</head>

<body>
    <div class="d-flex vh-100">
        <!-- Sidebar Wrapper -->
        <div class="sidebar-wrapper collapsed">
            <div class="card sidebar-card" style="margin-left: 12px; margin: right 50px;">
                <div class="card-body p-0">
                    <!-- Logo at the top -->
                    <div class="sidebar-logo text-center">
                        <img src="{{ asset('storage/images/sidebar/dlogo_icon.png') }}" alt="Logo Icon" class="sidebar-logo-icon">
                        <img src="{{ asset('storage/images/sidebar/dlogo_label.png') }}" alt="Logo Label" class="sidebar-logo-label">

                    </div>
                    <hr class="sidebar-hr">
                    <!-- Menu items -->
                    <div class="sidebar-menu">
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin.dashboard') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/dashboard.png') }}" alt="Dashboard" class="sidebar-icon"> <span>Dashboard</span></a></li>
                            <li><a href="{{ route('admin.manageBooks') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/manage.png') }}" alt="Manage Books" class="sidebar-icon"> <span>Manage&nbsp;Books</span></a></li>
                            <li>
                                <a href="{{ route('admin.requests.index') }}" class="sidebar-item">
                                    <img src="{{ asset('storage/images/sidebar/request.png') }}" alt="Requests" class="sidebar-icon">
                                    <span>Requests</span>
                                    @if($pendingRequestCount > 0)
                                    <span class="badge bg-danger ms-2">{{ $pendingRequestCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('admin.lend') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/lend.png') }}" alt="Lend" class="sidebar-icon"> <span>Lend</span></a></li>
                            <li><a href="{{ route('admin.return') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/return.png') }}" alt="Return" class="sidebar-icon"> <span>Return</span></a></li>
                            <li><a href="{{ route('admin.fines') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/fine.png') }}" alt="Fine" class="sidebar-icon"> <span>Fines</span></a></li>
                            <li><a href="{{ route('admin.members') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/members.png') }}" style="width: 22px;" alt="Members" class="sidebar-icon"> <span>Members</span></a></li>
                            <li><a href="{{ route('reports.lending') }}" class="sidebar-item">
                                    <img src="{{ asset('storage/images/sidebar/report.png') }}" style="width: 25px;" alt="Report" class="sidebar-icon">
                                    <span>Report</span>
                                </a></li>
                            <li><a href="{{ route('admin.employees') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/members.png') }}" style="width: 22px;" alt="Members" class="sidebar-icon"> <span>Employees</span></a></li>
                            <li><a href="{{ route('admin.settings') }}" class="sidebar-item"><img src="{{ asset('storage/images/sidebar/setting.png') }}" style="width: 24px;" alt="Settings" class="sidebar-icon"> <span>Settings</span></a></li>
                        </ul>
                    </div>


                    <!-- Logout at the bottom -->
                    <div class="sidebar-logout">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a href="#" class="sidebar-item" onclick="document.getElementById('logout-form').submit();">
                                <img src="{{ asset('storage/images/sidebar/out.png') }}" alt="Logout" class="sidebar-icon">
                                <span>Logout</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="content-column flex-grow-1 p-0">
            <!-- Top Row -->
            <div class="row mt-3 ">
                <div class="col">
                    <h4 class="fw-bold p-0 m-0">{{ Auth::user()->first_name.' '.Auth::user()->last_name ?? 'Guest' }}</h4>
                    <p class="p-0 m-0">{{ ucfirst(Auth::user()->role ?? 'N/A') }}</p>
                </div>
                <div class="col d-flex justify-content-end align-items-center" style="margin-right: 15px;">
                    <label class="switch shadow-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch to Dark Mode">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="slider round">
                            <i class="bi bi-sun-fill icon-toggle"></i>
                        </span>
                    </label>

                    <div class="dropdown position-relative">
                        <img src="{{ asset('storage/images/hero.jpg') }}" alt="Profile"
                            class="profile-img dropbtn">
                        <div class="dropdown-content">
                            <a href="#"><i class="bi bi-person me-2"></i>Profile</a>
                            <a href="#"><i class="bi bi-gear me-2"></i>Settings</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: rgb(54, 54, 54); border: none; background: none; width: 100%; text-align: left; padding: 12px 16px;">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Bottom Row -->
            <div class="row mt-3">
                <div class="col-12 pl-3 pr-0 pb-3 d-flex flex-column">
                    <div class="card content-area" style="border-radius: 12px; height: 969px; overflow-y: auto; border-bottom-right-radius:0px; border-top-right-radius:0px; width:100%;  ">
                        <div class="card-body p-3">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const toggle = document.getElementById('darkModeToggle');
        const icon = document.querySelector('.icon-toggle');
        const tooltipElement = document.querySelector('.switch');
        const body = document.body;

        // Check if dark mode was previously enabled in localStorage
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            icon.classList.remove('bi-sun-fill');
            icon.classList.add('bi-moon-fill');
            tooltipElement.setAttribute('title', 'Switch to Light Mode');
            toggle.checked = true; // Ensure the toggle is checked
        }

        // Dark mode toggle event listener
        toggle.addEventListener('change', function() {
            if (this.checked) {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-fill');
                tooltipElement.setAttribute('title', 'Switch to Light Mode');
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled'); // Save dark mode state
            } else {
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
                tooltipElement.setAttribute('title', 'Switch to Dark Mode');
                body.classList.remove('dark-mode');
                localStorage.removeItem('darkMode'); // Remove dark mode state
            }

            bootstrap.Tooltip.getInstance(tooltipElement).hide();
            bootstrap.Tooltip.getInstance(tooltipElement).setContent({
                '.tooltip-inner': tooltipElement.getAttribute('title')
            });
        });

        // Tooltip event listeners
        tooltipElement.addEventListener('mouseenter', function() {
            bootstrap.Tooltip.getInstance(tooltipElement).show();
        });

        tooltipElement.addEventListener('mouseleave', function() {
            bootstrap.Tooltip.getInstance(tooltipElement).hide();
        });
    </script>

</body>

</html>