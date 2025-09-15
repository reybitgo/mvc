<!-- C:\laragon\www\mvc\src\views\dashboard.php -->
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