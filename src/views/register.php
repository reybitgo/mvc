<!-- C:\laragon\www\mvc\src\views\register.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register - MVC Auth</title>
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet">
    <!-- CoreUI Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/free.min.css" rel="stylesheet">

    <style>
        .min-vh-100 {
            min-height: 100vh !important;
        }

        .bg-body-tertiary {
            background-color: #f8f9fa !important;
        }

        .card {
            border: 0;
            border-radius: 0.375rem;
        }

        .card-group {
            margin-bottom: 0;
        }

        .card-group .card {
            border-radius: 0.375rem !important;
        }

        .card-group .card:not(:last-child) {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .card-group .card:not(:first-child) {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .text-body-secondary {
            color: #8a93a2 !important;
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

<body class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-group d-block d-md-flex row">
                    <!-- Left Panel -->
                    <div class="card col-md-7 p-4 mb-0">
                        <div class="card-body">
                            <h1>Register</h1>
                            <p class="text-body-secondary">Create your account</p>

                            <!-- Global Error Messages -->
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg class="icon me-2">
                                        <use xlink:href="#cil-warning"></use>
                                    </svg>
                                    <div><?php echo htmlspecialchars($error); ?></div>
                                </div>
                            <?php endif; ?>

                            <!-- Success Messages -->
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <svg class="icon me-2">
                                        <use xlink:href="#cil-check-circle"></use>
                                    </svg>
                                    <div><?php echo htmlspecialchars($success); ?></div>
                                </div>
                            <?php endif; ?>

                            <!-- Registration Form -->
                            <form action="/register" method="POST" id="registerForm" novalidate>
                                <!-- CSRF Protection Token -->
                                <?php if (isset($csrf_token)): ?>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>

                                <!-- Username Field -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="#cil-user"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                                        type="text"
                                        name="username"
                                        id="username"
                                        placeholder="Username"
                                        value="<?php echo isset($old_input['username']) ? htmlspecialchars($old_input['username']) : ''; ?>"
                                        required
                                        maxlength="20"
                                        pattern="[a-zA-Z0-9_-]{3,20}">
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback">
                                            <?php foreach ($errors['username'] as $error): ?>
                                                <?php echo htmlspecialchars($error); ?><br>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text mb-3">
                                    <small class="text-body-secondary">3-20 characters: letters, numbers, hyphens, underscores only</small>
                                </div>

                                <!-- Email Field -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="#cil-envelope-open"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                        type="email"
                                        name="email"
                                        id="email"
                                        placeholder="Email"
                                        value="<?php echo isset($old_input['email']) ? htmlspecialchars($old_input['email']) : ''; ?>"
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

                                <!-- Password Field -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="#cil-lock-locked"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Password"
                                        required
                                        maxlength="128">
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php foreach ($errors['password'] as $error): ?>
                                                <?php echo htmlspecialchars($error); ?><br>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div id="password-strength" class="password-strength mb-3"></div>

                                <!-- Password Requirements -->
                                <div class="form-text mb-4">
                                    <small class="text-body-secondary">
                                        Must contain: 8+ characters, uppercase, lowercase, number, and special character
                                    </small>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-primary px-4"
                                            type="submit"
                                            <?php echo isset($rate_limited) && $rate_limited ? 'disabled' : ''; ?>>
                                            <?php echo isset($rate_limited) && $rate_limited ? 'Blocked' : 'Create Account'; ?>
                                        </button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="btn btn-link px-0" href="/login">Already have account?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="card col-md-5 text-white bg-primary py-5">
                        <div class="card-body text-center">
                            <div>
                                <h2>Sign up</h2>
                                <p>Join our platform and unlock powerful features designed to streamline your workflow and boost productivity.</p>
                                <div class="mb-4">
                                    <svg class="icon icon-xl">
                                        <use xlink:href="#cil-user-plus"></use>
                                    </svg>
                                </div>
                                <a class="btn btn-lg btn-outline-light mt-3" href="/">
                                    <svg class="icon me-2">
                                        <use xlink:href="#cil-home"></use>
                                    </svg>
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CoreUI Icons SVG -->
    <svg style="display: none;">
        <symbol id="cil-user" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM205.78,164.82C218.45,151.39,236.28,144,256,144s37.39,7.44,50.11,20.94S327,188.28,327,208.67c0,20.9-7.5,39.48-21.11,52.22S275.38,281,256,281s-37.56-7.44-49.78-20.11S185,229.57,185,208.67C185,188.28,193.11,178.28,205.78,164.82ZM256,432a175.49,175.49,0,0,1-126-53.22,122.91,122.91,0,0,1,35.14-33.44C190.63,329,222.89,320,256,320s65.37,9,90.83,25.34A122.87,122.87,0,0,1,382,378.78,175.45,175.45,0,0,1,256,432Z" />
        </symbol>
        <symbol id="cil-envelope-open" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M349.32,52.26C346.24,52.09,343.08,52,339.86,52H172.14c-3.22,0-6.38.09-9.46.26C48.74,58.39,48,169.15,48,171.47v268A52.61,52.61,0,0,0,100.61,492H411.39A52.61,52.61,0,0,0,464,439.43v-268c0-2.32-.74-113.08-114.68-119.21ZM171.47,84H340.53C384.6,84,416.5,108.83,422.2,139H89.8C95.5,108.83,127.4,84,171.47,84ZM432,439.43A20.59,20.59,0,0,1,411.39,460H100.61A20.59,20.59,0,0,1,80,439.43V171.47c0-.27.34-8.88,3.55-20.94L225.83,308.85a52.61,52.61,0,0,0,60.34,0L428.45,150.53C431.66,162.59,432,170.2,432,171.47Z" />
        </symbol>
        <symbol id="cil-lock-locked" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M420,192H352V140.8C352,98.51,309.49,56,267.2,56H244.8C202.51,56,160,98.51,160,140.8V192H92a20,20,0,0,0-20,20V460a20,20,0,0,0,20,20H420a20,20,0,0,0,20-20V212A20,20,0,0,0,420,192ZM192,140.8c0-24.51,19.49-44,44-44h22.4c24.51,0,44,19.49,44,44V192H192ZM408,448H104V224H408Z" />
            <rect width="32" height="48" x="240" y="310" fill="var(--ci-primary-color, currentColor)" />
        </symbol>
        <symbol id="cil-user-plus" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M168,256a88,88,0,1,1,88,88A88.1,88.1,0,0,1,168,256Zm88-56a56,56,0,1,0,56,56A56.063,56.063,0,0,0,256,200Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M172.7,432H83.3c0-90.9,74.09-165.11,165.11-165.11h15.18C353.09,266.89,427.3,341.1,427.3,432H338c0-48.523-39.477-88-88-88h-1.38C200.139,344,172.7,383.477,172.7,432Z" />
            <path fill="var(--ci-primary-color, currentColor)" d="M400,112H368V80a16,16,0,0,0-32,0v32H304a16,16,0,0,0,0,32h32v32a16,16,0,0,0,32,0V144h32a16,16,0,0,0,0-32Z" />
        </symbol>
        <symbol id="cil-warning" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M479.96,380.44,298.84,88.58a46.09,46.09,0,0,0-85.68,0L32.04,380.44C21.23,399.43,35.88,423.56,58.25,423.56H453.75C476.12,423.56,490.77,399.43,479.96,380.44ZM256,367.91a24,24,0,1,1,24-24A24,24,0,0,1,256,367.91Zm24-72a24,24,0,0,1-48,0V247.91a24,24,0,0,1,48,0Z" />
        </symbol>
        <symbol id="cil-check-circle" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM364.25,186.29l-134.4,160a16,16,0,0,1-12,5.71h-.27a16,16,0,0,1-11.89-5.3l-57.6-64a16,16,0,1,1,23.78-21.4l45.29,50.32L339.75,165.71a16,16,0,0,1,24.5,20.58Z" />
        </symbol>
        <symbol id="cil-home" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256.62,9.28a16,16,0,0,0-1.24,0A16,16,0,0,0,244,14.51L20,215.85A16,16,0,0,0,32,240H64V464a32,32,0,0,0,32,32H208a32,32,0,0,0,32-32V352h32V464a32,32,0,0,0,32,32H416a32,32,0,0,0,32-32V240h32a16,16,0,0,0,12-25.49L268,14.51A16,16,0,0,0,256.62,9.28ZM416,208a16,16,0,0,0-16,16V464H304V352a32,32,0,0,0-32-32H240a32,32,0,0,0-32,32V464H96V224a16,16,0,0,0-16-16H53.32L256,38.2,458.68,208Z" />
        </symbol>
    </svg>

    <!-- CoreUI JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthDiv = document.getElementById('password-strength');
            const form = document.getElementById('registerForm');

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
        });
    </script>
</body>

</html>