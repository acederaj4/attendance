<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
    <div class="header">Add Student</div>

    <form id="studentForm" onsubmit="submitStudentForm(event)">
        <label for="name">Full Name:</label>
        <input type="text" id="name" required>

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" required>

        <label for="course">Course:</label>
        <select id="course" required>
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
        <select id="year_level" required>
            <option value="" disabled selected>Select year level</option>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
            <option value="4th Year">4th Year</option>
        </select>

        <button type="submit">Add Student</button>
    </form>

    <p id="responseMessage" style="margin-top:20px; font-weight: bold;"></p>
</div>

<script>
function submitStudentForm(e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const student_id = document.getElementById('student_id').value;
    const course = document.getElementById('course').value;
    const year_level = document.getElementById('year_level').value;

    axios.post('api/add_student.php', {
        name: name,
        student_id: student_id,
        course: course,
        year_level: year_level
    })
    .then(response => {
        const msg = document.getElementById('responseMessage');
        if (response.data.success) {
            msg.style.color = 'green';
            msg.textContent = "✅ Student added successfully!";
            document.getElementById('studentForm').reset();
        } else {
            msg.style.color = 'red';
            msg.textContent = "❌ Error: " + response.data.error;
        }
    })
    .catch(error => {
        console.error(error);
        document.getElementById('responseMessage').textContent = "⚠️ Failed to connect to API.";
    });
}
</script>

</body>
</html>
