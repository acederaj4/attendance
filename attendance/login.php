<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login | Attendance System</title>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #3498db, #2ecc71);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            width: 420px;
            text-align: center;
        }

        .login-container h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .login-container p {
            font-size: 14px;
            color: #777;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #2c3e50;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #219150;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #888;
        }

        .attendance-icon {
            font-size: 50px;
            color: #2ecc71;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="attendance-icon">🕘</div>
        <h2>Attendance Admin Login</h2>
        <p>Manage and monitor student attendance</p>
        
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">👤 Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="footer">
            &copy; <?= date('Y') ?> Student Attendance System
        </div>
    </div>

</body>
</html>
