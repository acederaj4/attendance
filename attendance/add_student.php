<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) header("Location: login.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $course = $conn->real_escape_string($_POST['course']);
    $year_level = $conn->real_escape_string($_POST['year_level']);

    $conn->query("INSERT INTO students (name, student_id, course, year_level) VALUES ('$name', '$student_id', '$course', '$year_level')");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }

        .sidebar h3 {
            margin-bottom: 30px;
            font-size: 20px;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 15px 0;
            padding: 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            flex: 1;
        }

        .header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        form {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            max-width: 500px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #2ecc71;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>Welcome, <?= htmlspecialchars($_SESSION['admin']); ?></h3>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="add_student.php">➕ Add Student</a>
        <a href="mark_attendance.php">📝 Mark Attendance</a>
        <a href="view_attendance.php">📋 View Attendance</a>
         <a href="report.php">📊 Report</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="header">Add Student</div>

        <form method="post">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="student_id">Student ID:</label>
    <input type="text" id="student_id" name="student_id" required>

    <label for="course">Course:</label>
    <select id="course" name="course" required>
        <option value="" disabled selected>Select course</option>
        <option value="BSIT">BSIT</option>
        <option value="BSCRIM">BSCRIM</option>
        <option value="BSTM">BSTM</option>
        <option value="BEED">BEED</option>
        <option value="BSED">BSED</option>
        <option value="BSOA">BSOA</option>
        <option value="BSAIS">BSAIS</option>
    </select>

    <label for="year_level">Year Level:</label>
    <select id="year_level" name="year_level" required>
        <option value="" disabled selected>Select year level</option>
        <option value="1st Year">1st Year</option>
        <option value="2nd Year">2nd Year</option>
        <option value="3rd Year">3rd Year</option>
        <option value="4th Year">4th Year</option>
    </select>

    <button type="submit">Add Student</button>
</form>
    </div>

</body>
</html>
