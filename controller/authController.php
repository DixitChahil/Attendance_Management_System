<?php
require_once "database.php";
session_start();
echo 'fghgffg';
// Check if email exists for signup
if (isset($_GET['name']) && $_GET['name'] == 'check_exists_email') {
    $email = $_GET["email"];
    echo $email;
    // Prepare a select statement
    $sql_query = "select * from users where email = '$email'";
    $result = mysqli_query($link, $sql_query);

    if (mysqli_num_rows($result) > 0) {
        echo false;
    } else {
        echo true;
    }
}
// Check if phone exists for signup
if (isset($_GET['name']) && $_GET['name'] == 'check_exists_mobile') {
    $phone = $_GET["phone"];
    // Prepare a select statement
    $sql_query = "select * from users where phone = '$phone'";
    $result = mysqli_query($link, $sql_query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(false);
    } else {
        echo json_encode(true);
    }
}

// Login Check
if ($_GET['name'] == 'login') {
    $email = $_POST["newEmail"];
    $password = $_POST["password"];
    $password = base64_encode($password);
    $sql_query = "select * from users where email='$email'AND password='$password'";
    $table = mysqli_query($link, $sql_query);
    $count = mysqli_num_rows($table);
    if ($count == 0) {
        echo json_encode([
            'status' => false,
            'message' => 'Please enter valid credentials'
        ]);
    } else {
        $_SESSION["loggedIn"] = true;
        echo json_encode([
            'status' => true,
            'message' => 'Logged in successfully'
        ]);
    }
}

// Signup User
if ($_GET['name'] == 'signup') {
    $email = $_POST["email"];
    $name = $_POST["tName"];
    $phone = $_POST["phone"];
    $subjectId = $_POST["subjectId"];
    $teacherId = $_POST["teacherId"];
    $password = $_POST["password"];
    $hash = base64_encode($password);
    $query = "insert into users (name, subjectId, teacherId, role, email, phone, password) 
            values('$name','$subjectId','$teacherId','t', '$email', '$phone', '$hash')";
    if ($link->query($query) == FALSE)
        echo json_encode([
            'status' => false,
            'message' => 'Failed to add user please try again'
        ]);
    else {
        echo json_encode([
            'status' => true,
            'message' => 'Signed Up Successfully'
        ]);
    }
}