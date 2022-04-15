<?php
require_once "database.php";
session_start();

// Get Records for Attendance Table
if (isset($_GET['name']) && $_GET['name'] == 'attendanceTable') {
    $id = $_SESSION['id'];
    $today = date('m-d-Y');
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
        $role = $row['role'];

        $sql_query = "select s.name as sName, u.studentId, u.name, tss.subject_id, tss.teacher_id, a.date, a.attendance_type from subjects as s 
    left join attendance as a on a.subject_id = s.id
    right join teacher_student_subject_relation as tss on (tss.student_id = a.student_id and tss.subject_id = s.id)
    right join users as u on tss.student_id = u.id";

        if ($row['role'] == 't') {
            $sql_query .= " where tss.teacher_id='$id'";
        } else {
            $sql_query .= " where tss.student_id='$id'";
        }

        $columns = $_POST["columns"];
        $date = $columns[3]['search']['value'];

        $newString = strstr($sql_query, 'where');
        if ($date != '') {
            $date = explode('|', $date);
            $startDate = explode('=', $date[0]);
            $endDate = explode('=', $date[1]);
            if ($startDate[1] != '') {
                $startDate[1] = str_replace('/', '-', $startDate[1]);
                if (str_contains($newString, 'and')) {
                    $sql_query .= " a.date > '$startDate[1]'";
                } else {
                    $sql_query .= " and a.date > '$startDate[1]'";
                }
            }

            if ($endDate[1] != '') {
                $endDate[1] = str_replace('/', '-', $endDate[1]);
                if (str_contains($newString, 'and')) {
                    $sql_query .= " a.date < '$endDate[1]'";
                } else {
                    $sql_query .= " and a.date < '$endDate[1]'";
                }
            }
        } else {
            $sql_query .= " and (a.date='$today' or a.date is NULL)";
        }
        $totalData = mysqli_query($link, $sql_query);
        $totalCount = mysqli_num_rows($totalData);

        $sql_query .= " limit " .  $_POST['start'] . ',' . $_POST['length'];

        $table = mysqli_query($link, $sql_query);
        $count = mysqli_num_rows($table);

        if ($count == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No Data Found 2'
            ]);
        } else {
            $data = [];
            while ($row = $table->fetch_assoc()) {
                $data[] = $row;
            }
            $customArray = [];
            $i = 1;
            foreach ($data as $key => $item) {
                $sql_query = "select * from subjects where id='" . $item['subject_id'] . "'";
                $table = mysqli_query($link, $sql_query);
                $d = mysqli_fetch_assoc($table);
                $customArray[$key]['sr_no'] = $_POST['start'] + $i++;
                $customArray[$key]['student_id'] = ucwords($item['studentId']);
                $customArray[$key]['student_name'] = ucwords($item['name']);
                $customArray[$key]['date'] = ($item['date'] ?? date('m-d-Y'));
                $customArray[$key]['subject_name'] = $d['name'];
                if ($item['date']) {
                    if ($role == 's') {
                        $customArray[$key]['action'] = 'Attendance Submitted';
                    } else {
                        $customArray[$key]['action'] = '<span class="text-center">' . ($item['attendance_type'] == 'p' ? 'Present' : 'Absent') . '</span>';
                    }
                } else {
                    if ($role == 's') {
                        $customArray[$key]['action'] = "<span class='submitAtt' data-subId='" . $item['subject_id'] . "' 
                data-stId='" . $item['studentId'] . "' data-atType='p' data-tId='" . $item['teacher_id'] . "'>Present</span> / <span class='submitAtt' data-subId='" . $item['subject_id'] . "' data-stId='" . $item['studentId'] . "' data-atType='a'  data-tId='" . $item['teacher_id'] . "'>Absent</span>";
                    } else {
                        $customArray[$key]['action'] = "<span class='text-center'>Not Submitted</span>";
                    }
                }
            }
            echo json_encode([
                'draw' => $_POST['draw'],
                "recordsFiltered" => $totalCount,
                "recordsTotal" => $totalCount,
                'data' => $customArray
            ]);
        }
    }
}

// Submit Attendance
if (isset($_GET['name']) && $_GET['name'] == 'submit_attendance') {
    $stId = $_POST['stId'];
    $tId = $_POST['tId'];
    $type = $_POST['type'];
    $subId = $_POST['subId'];

    $sql_query = "select * from users where studentId='$stId'";
    $table = mysqli_query($link, $sql_query);
    $d = mysqli_fetch_assoc($table);
    $stId = $d['id'];
    $date = date('m-d-Y');

    $query = "insert into attendance (teacher_id ,student_id ,subject_id ,attendance_type ,date) 
        values('$tId', '$stId', '$subId', '$type', '$date')";
    if ($link->query($query)) {
        echo json_encode([
            'status' => false,
            'message' => 'Attendance submitted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Something went wrong'
        ]);
    }
}