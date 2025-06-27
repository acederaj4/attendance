<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) header("Location: login.php");

// Handle deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $del_date = $conn->real_escape_string($_POST['date']);
    $del_student_id = $conn->real_escape_string($_POST['student_id']);

    // Delete the attendance record for the given student and date
    $conn->query("DELETE FROM attendance WHERE student_id = '$del_student_id' AND date = '$del_date'");

    // Redirect to avoid resubmission on refresh
    header("Location: view_attendance.php");
    exit();
}

$result = $conn->query("
    SELECT a.date, s.name, s.student_id, a.status
    FROM attendance a
    JOIN students s ON a.student_id = s.student_id
    ORDER BY a.date DESC, s.name ASC
");

$totalRecords = $result->num_rows;
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

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .record-count {
            margin-bottom: 20px;
            font-weight: 600;
            color: #555;
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
        }

        th {
            background-color: #ecf0f1;
        }

        /* Status badge styles */
        .status-present {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-weight: bold;
        }

        .status-absent {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-weight: bold;
        }

        /* Delete button style */
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
        <div class="record-count">Total records: <?= $totalRecords ?></div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for Delete button -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= date("F j, Y", strtotime($row['date'])) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'Present'): ?>
                            <span class="status-present">Present</span>
                        <?php else: ?>
                            <span class="status-absent">Absent</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this record?');">
                            <input type="hidden" name="date" value="<?= htmlspecialchars($row['date']) ?>">
                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($row['student_id']) ?>">
                            <button type="submit" name="delete" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
