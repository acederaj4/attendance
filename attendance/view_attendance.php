<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) header("Location: login.php");

$result = $conn->query("
    SELECT a.date, s.name, s.course, s.year_level, a.status
    FROM attendance a
    JOIN students s ON a.student_id = s.student_id
    ORDER BY a.date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #ecf0f1;
        }

        h2 {
            margin-bottom: 15px;
            color: #333;
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
        <div class="header">Attendance Records</div>
        <h2>Attendance Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Year Level</th> <!-- Added -->
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td> <!-- Added -->
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
