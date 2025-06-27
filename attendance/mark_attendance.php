<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) header("Location: login.php");

// Handle delete student request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $del_student_id = $conn->real_escape_string($_POST['student_id']);
    $conn->query("DELETE FROM students WHERE student_id = '$del_student_id'");

    // Also delete attendance records for this student
    $conn->query("DELETE FROM attendance WHERE student_id = '$del_student_id'");

    header("Location: mark_attendance.php");
    exit();
}

$date = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    foreach ($_POST['status'] as $student_id => $status) {
        // Insert attendance only if student still exists
        $check = $conn->query("SELECT * FROM students WHERE student_id = '$student_id'");
        if ($check->num_rows > 0) {
            $conn->query("INSERT INTO attendance (student_id, date, status)
                          VALUES ('$student_id', '$date', '$status')");
        }
    }
    header("Location: dashboard.php");
    exit();
}

$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        .content {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #ecf0f1;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #27ae60;
        }

        .btn-delete {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* Wrap attendance form and delete buttons */
        .attendance-form {
            display: inline-block;
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
        <div class="header">Mark Attendance - <?= $date ?></div>
        <div class="content">
            <form method="post" style="margin-bottom: 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Delete Student</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><input type="radio" name="status[<?= $row['student_id'] ?>]" value="Present" required></td>
                            <td><input type="radio" name="status[<?= $row['student_id'] ?>]" value="Absent"></td>
                            <td>
                                <form method="post" onsubmit="return confirm('Delete student <?= htmlspecialchars($row['name']) ?>? This will also delete attendance records.');">
                                    <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                                    <button type="submit" name="delete_student" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit">Submit Attendance</button>
            </form>
        </div>
    </div>

</body>
</html>
