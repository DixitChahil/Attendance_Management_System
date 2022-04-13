<?php
session_start();
if (!isset($_SESSION["loggedIn"])) {
    header('Location:login.php', true);
    exit();
}
include_once 'controller/database.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <!--    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link href="css/toastr.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
    <style>
        .container {
            height: 80vh;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-toggleable-sm navbar-inverse bg-primary mb-3">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="flex-row d-flex">
        <a class="navbar-brand mb-1" href="#">Attendance Management</a>
        <button type="button" class="hidden-md-up navbar-toggler" data-toggle="offcanvas"
                title="Toggle responsive left sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $baseUrl; ?>index.php">Home <span class="sr-only">Home</span></a>
            </li>
            <?php if ($_SESSION['role'] == 't') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="student.php">Student</a>
                </li>
            <?php } ?>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<!--<div class="container align-content-center d-flex justify-content-center align-items-center">-->
<!--    <b>Logged in Successfully.....</b>-->
<!--</div>-->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header head-bg-info">
                    <h4 class="m-b-0">Attendance</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 ml-3 mt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" id="start-date" class="form-control"
                                           data-date-format="dd/mm/yyyy" placeholder="Start Date">
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="start-date"><i
                                                    class="fas fa-calendar-alt"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" id="end-date" class="form-control" data-date-format="dd/mm/yyyy"
                                           placeholder="End Date">
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="end-date"><i
                                                    class="fas fa-calendar-alt"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="m-b-0 btn btn-skyblue" id="filter-btn">Go</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive path_report_print" id="table-responsive">
                        <table class="table table-striped table-bordered" id="work-insights-tbl">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Id</th>
                                <th>Student Name</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    const base_url = 'http://localhost/finalProject/';

    function render_patient_datatable() {
        let table = $('#work-insights-tbl').DataTable({
            "processing": true,
            "serverSide": true,
            'lengthChange': false,
            'info': false,
            'autoWidth': false,
            'ordering': false,
            'bPaginate': false,
            "dom": '<"patient-filter float-left">frtip<"bottom">',
            fnInitComplete: function () {
                if ($(this).find('tbody tr').length < 1) {
                    $('tfoot').hide();
                }
            },
            drawCallback: function (settings) {
                if (Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength) > 1) {
                    $('.dataTables_paginate').show();
                } else {
                    $('.dataTables_paginate').hide();
                }
                if ($(this).find('.dataTables_empty').length == 1) {
                    $('tfoot').hide();
                    if ($(".dataTables_filter input").val() == '') {
                        $('.dataTables_filter').hide();
                    } else {
                        $('.dataTables_filter').show();
                    }
                }
            },
            "ajax": {
                "url": "controller/tableController.php?name=attendanceTable",
                "dataType": "json",
                "type": "POST",
            },
            "bJQueryUI": true,
            "columns": [
                {"data": "sr_no"},
                {"data": "student_id"},
                {"data": "student_name"},
                {"data": "date"},
                {"data": "action"},
            ],
            columnDefs: [
                {className: 'text-center', targets: [0]}
            ]
        });
        return table;
    }

    $(document).ready(function () {

        $.fn.bootstrapDP = $.fn.datepicker.noConflict();

        $("#start-date").bootstrapDP({
            autoclose: true,
            todayHighlight: true
        });

        $("#end-date").bootstrapDP({
            autoclose: true,
            todayHighlight: true
        });

        table = render_patient_datatable();

        $("body").on("focus", "#start-date", function () {
            $(this).bootstrapDP({
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            }).on('changeDate', function (selected) {
                let startDate = new Date(selected.date.valueOf());
                $('#end-date').bootstrapDP('setStartDate', startDate);
                $(this).bootstrapDP('hide');
            }).on('clearDate', function (selected) {
                $('#end-date').bootstrapDP('setStartDate', null);
            });
        });

        $("body").on("focus", "#end-date", function () {
            $(this).bootstrapDP({
                autoclose: true,
                orientation: "bottom"
            }).on('changeDate', function (selected) {
                let endDate = new Date(selected.date.valueOf());
                $('#start-date').bootstrapDP('setEndDate', endDate);
                $(this).bootstrapDP('hide');
            }).on('clearDate', function (selected) {
                $('#start-date').bootstrapDP('setEndDate', null);
            });
        });

        $("body").on("click", "#filter-btn", function () {
            let start = $("#start-date").val();
            let end = $('#end-date').val();
            if (start !== '' || end !== '') {
                let date = 'start=' + start + '|end=' + end;
                table.columns(3).search(date).draw();
            } else {
                alert('Please select date');
            }
        });
    });
</script>


</body>
</html>
