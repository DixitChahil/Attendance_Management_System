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
            'data'=>$row,
        ]);
    } else {
        echo json_encode([
            'status' => false,
        ]);

    }
}
