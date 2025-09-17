<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreUI Test - MVC Auth</title>
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- CoreUI Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/free.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-primary">CoreUI Framework Test</h1>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Test Form</h5>
                        <form>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="#cil-user"></use>
                                    </svg>
                                </span>
                                <input class="form-control" type="text" placeholder="Username" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="#cil-lock-locked"></use>
                                    </svg>
                                </span>
                                <input class="form-control" type="password" placeholder="Password" required>
                            </div>

                            <button class="btn btn-primary" type="submit">Test Button</button>
                            <button class="btn btn-secondary ms-2" type="button">Secondary</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="alert alert-success" role="alert">
                    <h6 class="alert-heading">Success Alert</h6>
                    If you can see this styled correctly, CoreUI is working!
                </div>

                <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> Test alert with icon
                    <svg class="icon ms-2">
                        <use xlink:href="#cil-warning"></use>
                    </svg>
                </div>

                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        Framework Status
                    </div>
                    <div class="card-body">
                        <p id="status">Checking CoreUI...</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Navigation Test</h3>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Test Nav</a>
                    <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login">Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="mt-4">
            <a href="/" class="btn btn-outline-primary">← Back to App</a>
            <a href="/security-test.php" class="btn btn-outline-secondary">Security Test</a>
        </div>
    </div>

    <!-- CoreUI Icons SVG -->
    <svg style="display: none;">
        <symbol id="cil-user" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM205.78,164.82C218.45,151.39,236.28,144,256,144s37.39,7.44,50.11,20.94S327,188.28,327,208.67c0,20.9-7.5,39.48-21.11,52.22S275.38,281,256,281s-37.56-7.44-49.78-20.11S185,229.57,185,208.67C185,188.28,193.11,178.28,205.78,164.82ZM256,432a175.49,175.49,0,0,1-126-53.22,122.91,122.91,0,0,1,35.14-33.44C190.63,329,222.89,320,256,320s65.37,9,90.83,25.34A122.87,122.87,0,0,1,382,378.78,175.45,175.45,0,0,1,256,432Z" />
        </symbol>
        <symbol id="cil-lock-locked" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M420,192H352V140.8C352,98.51,309.49,56,267.2,56H244.8C202.51,56,160,98.51,160,140.8V192H92a20,20,0,0,0-20,20V460a20,20,0,0,0,20,20H420a20,20,0,0,0,20-20V212A20,20,0,0,0,420,192ZM192,140.8c0-24.51,19.49-44,44-44h22.4c24.51,0,44,19.49,44,44V192H192ZM408,448H104V224H408Z" />
            <rect width="32" height="48" x="240" y="310" fill="var(--ci-primary-color, currentColor)" />
        </symbol>
        <symbol id="cil-warning" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M479.96,380.44,298.84,88.58a46.09,46.09,0,0,0-85.68,0L32.04,380.44C21.23,399.43,35.88,423.56,58.25,423.56H453.75C476.12,423.56,490.77,399.43,479.96,380.44ZM256,367.91a24,24,0,1,1,24-24A24,24,0,0,1,256,367.91Zm24-72a24,24,0,0,1-48,0V247.91a24,24,0,0,1,48,0Z" />
        </symbol>
    </svg>

    <!-- CoreUI JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusEl = document.getElementById('status');

            // Check if CoreUI classes are applied
            const hasContainer = document.querySelector('.container').classList.contains('container');
            const hasCard = document.querySelector('.card').classList.contains('card');
            const hasBtn = document.querySelector('.btn').classList.contains('btn');

            if (hasContainer && hasCard && hasBtn) {
                statusEl.innerHTML = '<span class="text-success">✅ CoreUI is loading correctly!</span>';
            } else {
                statusEl.innerHTML = '<span class="text-danger">❌ CoreUI styles not loading properly</span>';
            }
        });
    </script>
</body>
</html>