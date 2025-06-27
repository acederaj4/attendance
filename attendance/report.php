<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) header("Location: login.php");

// Function to generate attendance report
function generateAttendanceReport($conn) {
    $sql = "
        SELECT 
            s.student_id,
            s.name,
            s.course,
            s.year_level,
            SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS total_present,
            SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS total_absent,
            COUNT(a.status) AS total_records
        FROM students s
        LEFT JOIN attendance a ON s.student_id = a.student_id
        GROUP BY s.student_id
        ORDER BY s.name ASC
    ";
    
    $result = $conn->query($sql);
    $report = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $percentage = ($row['total_records'] > 0) ? 
                round(($row['total_present'] / $row['total_records']) * 100, 2) : 0;

            $report[] = [
                'student_id' => $row['student_id'],
                'name' => $row['name'],
                'course' => $row['course'],
                'year_level' => $row['year_level'],
                'total_present' => $row['total_present'],
                'total_absent' => $row['total_absent'],
                'total_records' => $row['total_records'],
                'attendance_percentage' => $percentage
            ];
        }
    }

    return $report;
}

$reportData = generateAttendanceReport($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
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
            margin-bottom: 20px;
            border-radius: 6px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
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
        <div class="header">Attendance Summary Report</div>
        <h2>Attendance Summary Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                    <th>Total Records</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td>
                    <td><?= $row['total_present'] ?></td>
                    <td><?= $row['total_absent'] ?></td>
                    <td><?= $row['total_records'] ?></td>
                    <td><?= $row['attendance_percentage'] ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
