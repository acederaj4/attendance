<?php
include '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $name = $conn->real_escape_string($data->name);
    $student_id = $conn->real_escape_string($data->student_id);
    $course = $conn->real_escape_string($data->course);
    $year_level = $conn->real_escape_string($data->year_level);

    $query = "INSERT INTO students (name, student_id, course, year_level) 
              VALUES ('$name', '$student_id', '$course', '$year_level')";
    
    if ($conn->query($query)) {
        echo json_encode(["success" => true, "message" => "Student added successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}
?>
