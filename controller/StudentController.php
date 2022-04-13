<?php
require_once "database.php";
session_start();

// Check if email exists for signup
if (isset($_GET['name']) && $_GET['name'] == 'check_student_id') {
    $sid = $_POST["sid"];
    // Prepare a select statement
    $sql_query = "select * from users where studentId = '$sid'";
    $result = mysqli_query($link, $sql_query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => true,
            'data' => $row,
        ]);
    } else {
        echo json_encode([
            'status' => false,
        ]);
    }
}

// Signup User
if ($_GET['name'] == 'signup') {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $subjectId = $_POST["subjectId"];
    $studentId = $_POST["studentId"];
    $teacherId = $_SESSION['id'];
    $password = $_POST["password"];
    $hash = base64_encode($password);
    if ($_GET['student'] == 'false') {
        $query = "insert into users (name, subjectId, role, email, phone, password, studentId) 
            values('$name','$subjectId','s', '$email', '$phone', '$hash', '$studentId')";
        if ($link->query($query) == FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to add student please try again'
            ]);
            exit();
        } else {
            $studentId = $link->insert_id;
        }
    } else {
        $sql_query = "select * from users where email = '$email'";
        $result = mysqli_query($link, $sql_query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $studentId = $row['id'];
        }
    }
    $query = "insert into teacher_student_subject_relation (subject_id, teacher_id, student_id) 
            values('$subjectId','$teacherId', '$studentId')";
    if ($link->query($query) == FALSE) {
        echo json_encode([
            'status' => false,
            'message' => 'Failed to add student please try again'
        ]);
    } else {
        echo json_encode([
            'status' => true,
            'message' => 'Student added successfully'
        ]);
    }
}