<!-- C:\laragon\www\mvc\src\views\dashboard.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - MVC Auth</title>
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet">
    <!-- CoreUI Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/free.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar sidebar-dark sidebar-fixed bg-dark" id="sidebar">
        <div class="sidebar-brand d-md-down-none">
            <div class="sidebar-brand-full">
                <h4 class="text-white">MVC Auth</h4>
            </div>
            <div class="sidebar-brand-minimized">
                <div class="text-white">MA</div>
            </div>
        </div>

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-item">
                <a class="nav-link active" href="/dashboard">
                    <svg class="nav-icon">
                        <use xlink:href="#cil-speedometer"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/profile">
                    <svg class="nav-icon">
                        <use xlink:href="#cil-user"></use>
                    </svg>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#settings">
                    <svg class="nav-icon">
                        <use xlink:href="#cil-settings"></use>
                    </svg>
                    Settings
                </a>
            </li>
        </ul>

        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>

    <!-- Main Content -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <!-- Header -->
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).toggle()">
                    <svg class="icon icon-lg">
                        <use xlink:href="#cil-menu"></use>
                    </svg>
                </button>

                <div class="header-nav d-none d-md-flex">
                    <div class="nav-item py-1">
                        <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                    </div>
                    <div class="nav-item py-1">
                        <a class="nav-link" href="#">
                            Dashboard
                        </a>
                    </div>
                </div>

                <div class="header-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <div class="avatar-initial rounded-circle bg-primary">
                                    <?php echo strtoupper(substr($username ?? 'U', 0, 1)); ?>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0 pe-0 w-auto">
                            <div class="dropdown-header bg-body-secondary fw-semibold py-2">Account</div>
                            <a class="dropdown-item" href="/profile">
                                <svg class="icon me-2">
                                    <use xlink:href="#cil-user"></use>
                                </svg>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#settings">
                                <svg class="icon me-2">
                                    <use xlink:href="#cil-settings"></use>
                                </svg>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">
                                <svg class="icon me-2">
                                    <use xlink:href="#cil-account-logout"></use>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0 ms-2">
                        <li class="breadcrumb-item">
                            <span>Home</span>
                        </li>
                        <li class="breadcrumb-item active">
                            <span>Dashboard</span>
                        </li>
                    </ol>
                </nav>

                <!-- Welcome Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-0">Welcome back, <?php echo htmlspecialchars($username ?? 'User'); ?>!</h4>
                                        <div class="small text-body-secondary">
                                            You have successfully logged in to the MVC Authentication System.
                                        </div>
                                    </div>
                                    <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                                        <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <svg class="icon">
                                                    <use xlink:href="#cil-calendar"></use>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4 text-white bg-primary">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fs-4 fw-semibold">1</div>
                                    <div>Active Session</div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white p-0" type="button">
                                        <svg class="icon">
                                            <use xlink:href="#cil-settings"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <div class="bg-white bg-opacity-25 rounded p-2">
                                    <small>Session started: <?php echo date('M j, Y g:i A'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4 text-white bg-info">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fs-4 fw-semibold">100%</div>
                                    <div>Security Score</div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white p-0" type="button">
                                        <svg class="icon">
                                            <use xlink:href="#cil-shield-alt"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <div class="bg-white bg-opacity-25 rounded p-2">
                                    <small>CSRF Protection: ✓ Active</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4 text-white bg-warning">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fs-4 fw-semibold">Phase 2</div>
                                    <div>Auth System</div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white p-0" type="button">
                                        <svg class="icon">
                                            <use xlink:href="#cil-chart-pie"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <div class="bg-white bg-opacity-25 rounded p-2">
                                    <small>Input validation & Rate limiting</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4 text-white bg-danger">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fs-4 fw-semibold">0</div>
                                    <div>Security Alerts</div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white p-0" type="button">
                                        <svg class="icon">
                                            <use xlink:href="#cil-bell"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                                <div class="bg-white bg-opacity-25 rounded p-2">
                                    <small>All systems secure</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Quick Actions</strong>
                                <small class="text-body-secondary">Available features</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <svg class="icon icon-xl text-primary mb-3">
                                                    <use xlink:href="#cil-user"></use>
                                                </svg>
                                                <h6>View Profile</h6>
                                                <p class="text-body-secondary small">Manage your account details</p>
                                                <button class="btn btn-primary btn-sm">Coming Soon</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <svg class="icon icon-xl text-info mb-3">
                                                    <use xlink:href="#cil-settings"></use>
                                                </svg>
                                                <h6>Settings</h6>
                                                <p class="text-body-secondary small">Configure preferences</p>
                                                <button class="btn btn-info btn-sm">Coming Soon</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <svg class="icon icon-xl text-success mb-3">
                                                    <use xlink:href="#cil-shield-alt"></use>
                                                </svg>
                                                <h6>Security</h6>
                                                <p class="text-body-secondary small">Two-factor authentication</p>
                                                <button class="btn btn-success btn-sm">Phase 7</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-3 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <svg class="icon icon-xl text-warning mb-3">
                                                    <use xlink:href="#cil-account-logout"></use>
                                                </svg>
                                                <h6>Logout</h6>
                                                <p class="text-body-secondary small">Sign out securely</p>
                                                <a href="/logout" class="btn btn-warning btn-sm">Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="ms-auto">
                <span class="me-1">Powered by</span>
                <strong>MVC Auth System</strong>
                <span class="ms-1">© 2024</span>
            </div>
        </footer>
    </div>

    <!-- CoreUI Icons SVG -->
    <svg style="display: none;">
        <symbol id="cil-speedometer" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M326.1,231.9l-47.5,75.5a31.94,31.94,0,0,1-54.4,0l-47.5-75.5C150.5,174.7,126.3,239.9,126.3,239.9l-15.1,24.1a158.7,158.7,0,0,0-4.9,163.2A160,160,0,0,0,256,464c88.4,0,160-71.6,160-160C416,222.8,326.1,231.9,326.1,231.9Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM256,80A175.29,175.29,0,0,1,393.25,127.8L359.6,154.8A135.07,135.07,0,0,0,256,112c-74.25,0-134.58,60.33-134.58,134.58a133.6,133.6,0,0,0,2.83,27.8L89.6,287.2A175.25,175.25,0,0,1,81,256,175,175,0,0,1,256,80Z" />
        </symbol>
        <symbol id="cil-user" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM205.78,164.82C218.45,151.39,236.28,144,256,144s37.39,7.44,50.11,20.94S327,188.28,327,208.67c0,20.9-7.5,39.48-21.11,52.22S275.38,281,256,281s-37.56-7.44-49.78-20.11S185,229.57,185,208.67C185,188.28,193.11,178.28,205.78,164.82ZM256,432a175.49,175.49,0,0,1-126-53.22,122.91,122.91,0,0,1,35.14-33.44C190.63,329,222.89,320,256,320s65.37,9,90.83,25.34A122.87,122.87,0,0,1,382,378.78,175.45,175.45,0,0,1,256,432Z" />
        </symbol>
        <symbol id="cil-settings" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M245.151,168a88,88,0,0,0,0,176c48.549,0,88-39.451,88-88S293.7,168,245.151,168Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M464,230.909v50.182L424.875,298.4c-4.756,21.909-14.074,42.537-27.256,60.759L424.875,395.636,395.636,424.875l-36.477-27.256c-18.222,13.182-38.85,22.5-60.759,27.256L281.091,464H230.909L213.6,424.875c-21.909-4.756-42.537-14.074-60.759-27.256L116.364,424.875,87.125,395.636l27.256-36.477C101.2,340.937,91.883,320.309,87.125,298.4L48,281.091V230.909L87.125,213.6c4.756-21.909,14.074-42.537,27.256-60.759L87.125,116.364,116.364,87.125l36.477,27.256C171.063,101.2,191.691,91.883,213.6,87.125L230.909,48h50.182L298.4,87.125c21.909,4.756,42.537,14.074,60.759,27.256l36.477-27.256L424.875,116.364,397.619,152.841C410.8,171.063,420.117,191.691,424.875,213.6ZM256,120A136,136,0,1,0,392,256,136.15,136.15,0,0,0,256,120Z" />
        </symbol>
        <symbol id="cil-menu" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M80,96H432a16,16,0,0,0,0-32H80a16,16,0,0,0,0,32Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M80,272H432a16,16,0,0,0,0-32H80a16,16,0,0,0,0,32Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M80,448H432a16,16,0,0,0,0-32H80a16,16,0,0,0,0,32Z" />
        </symbol>
        <symbol id="cil-account-logout" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M77.155,272.034H351.75l-85.053,85.054a15.923,15.923,0,0,0,0,22.525l.707.707a15.923,15.923,0,0,0,22.525,0l112.734-112.734a15.923,15.923,0,0,0,0-22.525L289.929,131.327a15.923,15.923,0,0,0-22.525,0l-.707.707a15.923,15.923,0,0,0,0,22.525L351.75,239.612H77.155a15.923,15.923,0,0,0-15.923,15.923v.5A15.923,15.923,0,0,0,77.155,272.034Z" />
            <rect width="239.612" height="431.772" x="31.232" y="48.004" fill="var(--ci-primary-color, currentColor)" rx="15.923" ry="15.923" />
        </symbol>
        <symbol id="cil-shield-alt" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48,64,112V288a191.78,191.78,0,0,0,192,192,191.78,191.78,0,0,0,192-192V112ZM256,416A127.3,127.3,0,0,1,128,288V149.77L256,96l128,53.77V288A127.3,127.3,0,0,1,256,416Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M208,240l-48,48,32,32,64-64-64-64-32,32Z" />
        </symbol>
        <symbol id="cil-calendar" viewBox="0 0 512 512">
            <rect width="416" height="384" x="48" y="80" fill="none" stroke="var(--ci-primary-color, currentColor)" stroke-linejoin="round" stroke-width="32" rx="48" />
            <circle cx="296" cy="232" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="376" cy="232" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="296" cy="312" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="376" cy="312" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="136" cy="312" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="216" cy="312" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="136" cy="392" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="216" cy="392" r="24" fill="var(--ci-primary-color, currentColor)" />
            <circle cx="296" cy="392" r="24" fill="var(--ci-primary-color, currentColor)" />
            <path fill="none" stroke="var(--ci-primary-color, currentColor)" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M128,48v32m256-32v32" />
            <path fill="none" stroke="var(--ci-primary-color, currentColor)" stroke-linejoin="round" stroke-width="32" d="M464,160H48" />
        </symbol>
        <symbol id="cil-chart-pie" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256.05,80A176,176,0,0,0,124.16,387.84C171.73,435.41,235.14,456,300.05,436.87A176,176,0,0,0,432,256V80ZM272,432a160,160,0,1,1,0-320Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48V256L389.33,122.67A207.71,207.71,0,0,0,256,48Z" />
        </symbol>
        <symbol id="cil-bell" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M448,256c0-106-86-192-192-192S64,150,64,256c0,22.69-18.78,42.11-42.11,42.11A10.93,10.93,0,0,0,10.9,309C10.9,314.85,15.05,320,21.89,320H85.39a171.4,171.4,0,0,0,341.22,0h63.5c6.84,0,11-5.15,11-11C501.11,298.11,470.69,256,448,256ZM256,384a64,64,0,0,1-64-64h128A64,64,0,0,1,256,384Z" />
        </symbol>
    </svg>

    <!-- CoreUI JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CoreUI components
            const sidebarToggler = document.querySelector('.sidebar-toggler');
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', () => {
                    document.querySelector('.sidebar').classList.toggle('sidebar-narrow');
                });
            }
        });
    </script>
</body>

</html><!-- C:\laragon\www\mvc\src\views\dashboard.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .welcome-message {
            color: #28a745;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard</h1>
            <a href="/logout" class="logout-btn">Logout</a>
        </div>

        <div class="welcome-message">
            Welcome back, <?php echo htmlspecialchars($username ?? 'User'); ?>!
        </div>

        <p>You have successfully logged in to the MVC system.</p>

        <div style="margin-top: 30px;">
            <h3>Quick Actions:</h3>
            <ul>
                <li>View Profile (Coming Soon)</li>
                <li>Update Settings (Coming Soon)</li>
                <li>Manage Account (Coming Soon)</li>
            </ul>
        </div>
    </div>
</body>

</html>