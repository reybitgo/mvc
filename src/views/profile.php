<!-- C:\laragon\www\mvc\src\views\profile.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile - MVC Auth</title>
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet">
    <!-- CoreUI Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/free.min.css" rel="stylesheet">

    <style>
        .avatar-xl {
            width: 6rem;
            height: 6rem;
            font-size: 2rem;
        }

        .password-strength {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-strength.weak {
            color: #e55353;
        }

        .password-strength.medium {
            color: #f9b115;
        }

        .password-strength.strong {
            color: #2eb85c;
        }
    </style>
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
                <a class="nav-link" href="/dashboard">
                    <svg class="nav-icon">
                        <use xlink:href="#cil-speedometer"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/profile">
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
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </div>
                    <div class="nav-item py-1">
                        <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                    </div>
                    <div class="nav-item py-1">
                        <span class="nav-link">Profile</span>
                    </div>
                </div>

                <div class="header-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <div class="avatar-initial rounded-circle bg-primary">
                                    <?php echo strtoupper(substr($user['username'] ?? 'U', 0, 1)); ?>
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
                            <a href="/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <span>Profile</span>
                        </li>
                    </ol>
                </nav>

                <!-- Alert Messages -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg class="icon me-2">
                            <use xlink:href="#cil-warning"></use>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg class="icon me-2">
                            <use xlink:href="#cil-check-circle"></use>
                        </svg>
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Profile Info Card -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <div class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($user['username'] ?? 'U', 0, 2)); ?>
                                    </div>
                                </div>
                                <h4 class="card-title"><?php echo htmlspecialchars($user['username'] ?? 'Unknown'); ?></h4>
                                <p class="text-body-secondary"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

                                <div class="row text-center mt-4">
                                    <div class="col">
                                        <div class="border-end">
                                            <div class="fs-4 fw-semibold"><?php echo $user['days_active'] ?? 0; ?></div>
                                            <div class="text-uppercase text-body-secondary small">Days Active</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fs-4 fw-semibold">100%</div>
                                        <div class="text-uppercase text-body-secondary small">Security</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-toolbar justify-content-between" role="toolbar">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                            <svg class="icon me-1">
                                                <use xlink:href="#cil-user-plus"></use>
                                            </svg>
                                            Friends
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                            <svg class="icon me-1">
                                                <use xlink:href="#cil-envelope-open"></use>
                                            </svg>
                                            Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="card">
                            <div class="card-header">
                                <strong>Account Information</strong>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-body-secondary">Member Since:</td>
                                            <td class="fw-semibold"><?php echo $user['member_since'] ?? 'Unknown'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-body-secondary">User ID:</td>
                                            <td class="fw-semibold">#<?php echo $user['id'] ?? 'N/A'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-body-secondary">Account Status:</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-body-secondary">Last Updated:</td>
                                            <td class="fw-semibold"><?php echo date('M j, Y'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Settings -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <strong>Profile Settings</strong>
                                <small class="text-body-secondary">Update your account information</small>
                            </div>
                            <div class="card-body">
                                <form action="/profile/update" method="POST" id="profileForm" novalidate>
                                    <!-- CSRF Protection Token -->
                                    <?php if (isset($csrf_token)): ?>
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php endif; ?>

                                    <!-- Email Section -->
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <h5 class="card-title">Contact Information</h5>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label" for="username">Username</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" disabled>
                                            <div class="form-text">Username cannot be changed for security reasons.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label" for="email">Email Address</label>
                                        <div class="col-sm-9">
                                            <input class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                                type="email"
                                                id="email"
                                                name="email"
                                                value="<?php echo isset($old_input['email']) ? htmlspecialchars($old_input['email']) : htmlspecialchars($user['email'] ?? ''); ?>"
                                                required
                                                maxlength="100">
                                            <?php if (isset($errors['email'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php foreach ($errors['email'] as $error): ?>
                                                        <?php echo htmlspecialchars($error); ?><br>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Password Section -->
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <h5 class="card-title">Change Password</h5>
                                            <p class="text-body-secondary">Leave password fields empty if you don't want to change your password.</p>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label" for="current_password">Current Password</label>
                                        <div class="col-sm-9">
                                            <input class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>"
                                                type="password"
                                                id="current_password"
                                                name="current_password"
                                                maxlength="128">
                                            <?php if (isset($errors['current_password'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php foreach ($errors['current_password'] as $error): ?>
                                                        <?php echo htmlspecialchars($error); ?><br>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label" for="new_password">New Password</label>
                                        <div class="col-sm-9">
                                            <input class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>"
                                                type="password"
                                                id="new_password"
                                                name="new_password"
                                                maxlength="128">

                                            <!-- Password Strength Indicator -->
                                            <div id="password-strength" class="password-strength mt-2"></div>

                                            <div class="form-text">
                                                Must contain: 8+ characters, uppercase, lowercase, number, and special character
                                            </div>

                                            <?php if (isset($errors['new_password'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php foreach ($errors['new_password'] as $error): ?>
                                                        <?php echo htmlspecialchars($error); ?><br>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label" for="confirm_password">Confirm Password</label>
                                        <div class="col-sm-9">
                                            <input class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                                                type="password"
                                                id="confirm_password"
                                                name="confirm_password"
                                                maxlength="128">
                                            <?php if (isset($errors['confirm_password'])): ?>
                                                <div class="invalid-feedback">
                                                    <?php foreach ($errors['confirm_password'] as $error): ?>
                                                        <?php echo htmlspecialchars($error); ?><br>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Action Buttons -->
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary"
                                                type="submit"
                                                <?php echo isset($rate_limited) && $rate_limited ? 'disabled' : ''; ?>>
                                                <svg class="icon me-2">
                                                    <use xlink:href="#cil-check"></use>
                                                </svg>
                                                <?php echo isset($rate_limited) && $rate_limited ? 'Update Blocked' : 'Update Profile'; ?>
                                            </button>
                                            <button class="btn btn-secondary ms-2" type="reset">
                                                <svg class="icon me-2">
                                                    <use xlink:href="#cil-reload"></use>
                                                </svg>
                                                Reset
                                            </button>
                                            <a href="/dashboard" class="btn btn-outline-secondary ms-2">
                                                <svg class="icon me-2">
                                                    <use xlink:href="#cil-arrow-left"></use>
                                                </svg>
                                                Back to Dashboard
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <strong>Security Status</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="border-end h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-sm bg-success me-3">
                                                    <svg class="icon text-white">
                                                        <use xlink:href="#cil-shield-alt"></use>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">CSRF Protection</div>
                                                    <div class="text-body-secondary small">Active</div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-sm bg-success me-3">
                                                    <svg class="icon text-white">
                                                        <use xlink:href="#cil-lock-locked"></use>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">Password Encryption</div>
                                                    <div class="text-body-secondary small">Secure Hash</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar avatar-sm bg-success me-3">
                                                <svg class="icon text-white">
                                                    <use xlink:href="#cil-speedometer"></use>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">Rate Limiting</div>
                                                <div class="text-body-secondary small">Protected</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar avatar-sm bg-warning me-3">
                                                <svg class="icon text-white">
                                                    <use xlink:href="#cil-user-plus"></use>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">Two-Factor Auth</div>
                                                <div class="text-body-secondary small">Coming Soon</div>
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
        </symbol>
        <symbol id="cil-warning" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M479.96,380.44,298.84,88.58a46.09,46.09,0,0,0-85.68,0L32.04,380.44C21.23,399.43,35.88,423.56,58.25,423.56H453.75C476.12,423.56,490.77,399.43,479.96,380.44ZM256,367.91a24,24,0,1,1,24-24A24,24,0,0,1,256,367.91Zm24-72a24,24,0,0,1-48,0V247.91a24,24,0,0,1,48,0Z" />
        </symbol>
        <symbol id="cil-check-circle" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM364.25,186.29l-134.4,160a16,16,0,0,1-12,5.71h-.27a16,16,0,0,1-11.89-5.3l-57.6-64a16,16,0,1,1,23.78-21.4l45.29,50.32L339.75,165.71a16,16,0,0,1,24.5,20.58Z" />
        </symbol>
        <symbol id="cil-check" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M186.301,339.893,96,249.461l-32,30.507L186.301,402.295,448,140.506,416,110Z" />
        </symbol>
        <symbol id="cil-reload" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M463.5,224H436.622c-35.313-75.397-112.793-128-202.622-128C105.187,96,48,153.188,48,224s57.187,128,128,128c47.278,0,89.3-25.678,111.775-64h-63.55c-15.624,22.031-41.332,36-70.225,36a64,64,0,1,1,0-128c28.893,0,54.601,13.969,70.225,36H387.5l76-76Z" />
        </symbol>
        <symbol id="cil-arrow-left" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M217.9,256,345,129c9.4-9.4,9.4-24.6,0-33.9s-24.6-9.4-33.9,0L167,239c-9.4,9.4-9.4,24.6,0,33.9L311.1,417c9.4,9.4,24.6,9.4,33.9,0s9.4-24.6,0-33.9L217.9,256Z" />
        </symbol>
        <symbol id="cil-shield-alt" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48,64,112V288a191.78,191.78,0,0,0,192,192,191.78,191.78,0,0,0,192-192V112ZM256,416A127.3,127.3,0,0,1,128,288V149.77L256,96l128,53.77V288A127.3,127.3,0,0,1,256,416Z" />
        </symbol>
        <symbol id="cil-lock-locked" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M420,192H352V140.8C352,98.51,309.49,56,267.2,56H244.8C202.51,56,160,98.51,160,140.8V192H92a20,20,0,0,0-20,20V460a20,20,0,0,0,20,20H420a20,20,0,0,0,20-20V212A20,20,0,0,0,420,192ZM192,140.8c0-24.51,19.49-44,44-44h22.4c24.51,0,44,19.49,44,44V192H192ZM408,448H104V224H408Z" />
        </symbol>
        <symbol id="cil-user-plus" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M168,256a88,88,0,1,1,88,88A88.1,88.1,0,0,1,168,256Zm88-56a56,56,0,1,0,56,56A56.063,56.063,0,0,0,256,200Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M172.7,432H83.3c0-90.9,74.09-165.11,165.11-165.11h15.18C353.09,266.89,427.3,341.1,427.3,432H338c0-48.523-39.477-88-88-88h-1.38C200.139,344,172.7,383.477,172.7,432Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M400,112H368V80a16,16,0,0,0-32,0v32H304a16,16,0,0,0,0,32h32v32a16,16,0,0,0,32,0V144h32a16,16,0,0,0,0-32Z" />
        </symbol>
        <symbol id="cil-envelope-open" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M349.32,52.26C346.24,52.09,343.08,52,339.86,52H172.14c-3.22,0-6.38.09-9.46.26C48.74,58.39,48,169.15,48,171.47v268A52.61,52.61,0,0,0,100.61,492H411.39A52.61,52.61,0,0,0,464,439.43v-268c0-2.32-.74-113.08-114.68-119.21ZM171.47,84H340.53C384.6,84,416.5,108.83,422.2,139H89.8C95.5,108.83,127.4,84,171.47,84ZM432,439.43A20.59,20.59,0,0,1,411.39,460H100.61A20.59,20.59,0,0,1,80,439.43V171.47c0-.27.34-8.88,3.55-20.94L225.83,308.85a52.61,52.61,0,0,0,60.34,0L428.45,150.53C431.66,162.59,432,170.2,432,171.47Z" />
        </symbol>
    </svg>

    <!-- CoreUI JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('new_password');
            const strengthDiv = document.getElementById('password-strength');
            const form = document.getElementById('profileForm');

            // Password strength validation
            if (passwordInput && strengthDiv) {
                passwordInput.addEventListener('input', function(e) {
                    const password = e.target.value;

                    let strength = 0;
                    let feedback = [];

                    if (password.length >= 8) strength++;
                    else feedback.push('8+ characters');

                    if (/[A-Z]/.test(password)) strength++;
                    else feedback.push('uppercase');

                    if (/[a-z]/.test(password)) strength++;
                    else feedback.push('lowercase');

                    if (/[0-9]/.test(password)) strength++;
                    else feedback.push('number');

                    if (/[^A-Za-z0-9]/.test(password)) strength++;
                    else feedback.push('special character');

                    // Clear previous content
                    strengthDiv.innerHTML = '';

                    if (password.length === 0) {
                        return;
                    }

                    let className, text;

                    if (strength < 3) {
                        className = 'weak';
                        text = '● Weak - Missing: ' + feedback.join(', ');
                    } else if (strength < 5) {
                        className = 'medium';
                        text = '● Medium - Missing: ' + feedback.join(', ');
                    } else {
                        className = 'strong';
                        text = '● Strong password!';
                    }

                    strengthDiv.className = 'password-strength ' + className;
                    strengthDiv.textContent = text;
                });
            }

            // Form submission protection
            if (form) {
                let formSubmitted = false;
                form.addEventListener('submit', function(e) {
                    if (formSubmitted) {
                        e.preventDefault();
                        return false;
                    }
                    formSubmitted = true;

                    // Re-enable after 5 seconds
                    setTimeout(() => {
                        formSubmitted = false;
                    }, 5000);
                });
            }

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

</html>