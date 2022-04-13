<?php
require_once "database.php";
session_start();

// Login Check
if (isset($_GET['name']) && $_GET['name'] == 'attendanceTable') {
    $id = $_SESSION['id'];
    $sql_query = "select * from users where id='$id'";
    $table = mysqli_query($link, $sql_query);
    $count = mysqli_num_rows($table);
    if ($count == 0) {
        echo json_encode([
            'status' => false,
            'message' => 'No Data Found'
        ]);
    } else {
        $row = mysqli_fetch_assoc($table);
        $sql_query = "select * from attendance";
        if ($row['role'] == 't') {
            $sql_query .= " where teacher_id='$id'";
        } else {
            $sql_query .= " where student_id='$id'";
        }
        $columns = $_POST["columns"];
        $date = $columns[3]['search']['value'];
        if ($date) {
            $date = explode('|', $date);
            $startDate = explode('=', $date[0]);
            $endDate = explode('=', $date[1]);
            if ($startDate[1] != '') {
                if (strstr($sql_query, 'and')) {
                    $sql_query .= " date > $startDate";
                } else {
                    $sql_query .= " and date > $startDate";
                }
            }
            if ($endDate[1] != '') {
                if (strstr($sql_query, 'and')) {
                    $sql_query .= " date < $startDate";
                } else {
                    $sql_query .= " and date < $startDate";
                }
            };
        } else {
            $today = date('d/m/y');
            $sql_query .= " and date=$today";
        }
        $table = mysqli_query($link, $sql_query);
        $count = mysqli_num_rows($table);
        if ($count == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No Data Found 2'
            ]);
        } else {
            $row = mysqli_fetch_assoc($table);
            $_SESSION["loggedIn"] = true;
            $_SESSION["id"] = $row['id'];
            echo json_encode([
                'status' => true,
                'message' => 'Logged in successfully'
            ]);
        }
    }
}