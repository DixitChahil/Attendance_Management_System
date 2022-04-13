<?php
require_once 'controller/database.php';
session_start();
if (!isset($_SESSION["loggedIn"])) {
    header('Location:login.php', true);
    exit();
}

$sql_query = "select * from subjects order by name asc";
$result = mysqli_query($link, $sql_query);

$subjects = [];
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $subjects = $row;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Student</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link href="css/toastr.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: transparent;
            height: auto;
            display: block;
        }

        .emailerror, .mobileerror {
            color: red;
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
                <a class="nav-link" href="<?php echo $baseUrl; ?>">Home <span class="sr-only">Home</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ">
            <li class="nav-item">
                <a class="nav-link" href="student.php">Student</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="forms">
        <div class="form signup">
            <span class="title">Add Student</span>

            <form action="#" id="signUpFrm" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter student ID" required autocomplete="new-ID"
                                   name="studentId" class="sid">
                            <i class="uil uil-user"></i>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter student name" required autocomplete="new-name"
                                   name="name" id="sname">
                            <i class="uil uil-user"></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter your email" required autocomplete="new-email"
                                   name="email" id="semail">

                            <i class="uil uil-envelope"></i>
                        </div>
                        <label id="emailerror" class="emailerror hide" for="email">
                            Email address is already taken, please enter different</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter your phone number" required autocomplete="new-mobile"
                                   name="phone" onkeypress="return isNumber(event);" maxlength="10" id="smobile">
                            <i class="uil uil-mobile-android"></i>
                        </div>
                        <label id="mobileerror" class="mobileerror hide" for="phone">
                            Mobile number is already taken, please enter different</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <select name="subjectId" id="subjectId">
                                <option value="" selected>Select your subject</option>
                                <?php
                                if (count($subjects) > 0) {
                                    foreach ($result as $item) {
                                        ?>
                                        <option value="<?php echo $item['id']; ?>">
                                            <?php echo $item['subjectId'] . " - " . $item['name']; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                            <i class="uil uil-book-alt"></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="password" placeholder="Create a password" required autocomplete="new-password"
                                   name="password" id="SignUpPassword">
                            <i class="uil uil-lock icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="password" class="password" placeholder="Confirm a password" required
                                   name="confPassword" autocomplete="new-password" id="CSignUpPassword">
                            <i class="uil uil-lock icon"></i>
                        </div>
                    </div>
                </div>

                <div class="input-field button">
                    <input type="button" value="Add Student" class="signUpBtn">
                    <button class="btn waitBtnSignUp hide" type="button" disabled>
                        <div class="loader"></div>
                        <span class="sr-only">Please wait...</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/toastr.min.js"></script>

<script>
    document.querySelector(".container").classList.add('active');

    jQuery.validator.addMethod("myEmail", function (value, element) {
        var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return regex.test(value);
    });

    jQuery.validator.addMethod('dropdown', function (value) {
        return (value != '');
    }, "");

    $('#loginFrm').validate({
        rules: {
            newEmail: {
                required: true,
                myEmail: true,
            },
            password: {
                required: true
            }
        },
        messages: {
            newEmail: {
                required: "Please enter email ID",
                myEmail: "Please enter valid email ID",
            },
            password: {
                required: "Please enter password",
            },
        },
        errorPlacement: function (error, element) {
            if (element.parent().hasClass('input-field')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    $(document).on("click", ".loginBtn", function () {
        if ($("#loginFrm").valid()) {
            console.log('form valid');
            $.ajax({
                type: "POST",
                url: base_url + "controller/authController.php?name=login",
                data: $('#loginFrm').serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $(".waitBtn").removeClass('hide');
                    $(".loginBtn").addClass('hide');
                },
                success: function (data) {
                    if (data.status) {
                        console.log('success');
                        toastr.success(data.message);
                        window.location.replace(base_url + "index.php");
                    } else {
                        console.log('error');
                        toastr.error(data.message);
                    }
                    $(".waitBtn").addClass('hide');
                    $(".loginBtn").removeClass('hide');
                }
            });
        } else {
            return false;
        }
    });

    $('#signUpFrm').validate({
        rules: {
            tName: {
                required: true
            },
            email: {
                required: true,
                myEmail: true,
            },
            phone: {
                required: true,
            },
            subjectId: {
                required: true,
                dropdown: true
            },
            password: {
                required: true
            },
            confPassword: {
                required: true,
                equalTo: "#SignUpPassword"
            },
        },
        messages: {
            tName: {
                required: "Please enter name",
            },
            email: {
                required: "Please enter email ID",
                myEmail: "Please enter valid email ID",
            },
            phone: {
                required: "Please enter phone",
            },
            subjectId: {
                required: "Please select subject",
                dropdown: "Please select subject"
            },
            password: {
                required: "Please enter password",
            },
            confPassword: {
                required: "Please enter password again",
                equalTo: 'Please enter same password'
            }
        },
        errorPlacement: function (error, element) {
            if (element.parent().hasClass('input-field')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    var student = false;

    $(document).on("click", ".signUpBtn", function () {
        if ($("#signUpFrm").valid()) {
            console.log('form valid');
            console.log(student);
            if (!student) {
                $.ajax({
                    type: "GET",
                    url: "controller/authController.php?name=check_exists_email",
                    data: 'email=' + encodeURI($('#semail').val()),
                    dataType: 'json',
                    success: function (data) {
                        if (!data) {
                            $(".emailerror").removeClass('hide');
                            return false;
                        } else {
                            $(".emailerror").addClass('hide');
                            $.ajax({
                                type: "GET",
                                url: "controller/authController.php?name=check_exists_mobile",
                                data: 'phone=' + $('#smobile').val(),
                                dataType: 'json',
                                success: function (data) {
                                    if (!data) {
                                        $(".mobileerror").removeClass('hide');
                                        return false;
                                    } else {
                                        $(".mobileerror").addClass('hide');
                                        signUp();
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                signUp();
            }
        } else {
            return false;
        }
    });

    $(document).ready(function () {
        $(".sid").blur(function () {

            $.ajax({
                type: "POST",
                url: "controller/StudentController.php?name=check_student_id",
                data: 'sid=' + $(this).val(),
                dataType: 'json',
                beforeSend: function () {
                    $(".waitBtn").removeClass('hide');
                    $(".loginBtn").addClass('hide');
                },
                success: function (data) {
                    if (data.status) {
                        student = true;
                        console.log('success');
                        $(":input").not(".sid").attr("readonly", true);

                        $("#sname").val(data.data.name);
                        $("#semail").val(data.data.email);
                        $("#smobile").val(data.data.phone);
                        $("#subjectId").val(data.data.subjectId);
                        $("#tid").val(data.data.teacherId);
                        $("#SignUpPassword").val(data.data.password);
                        $("#CSignUpPassword").val(data.data.password);
                    } else {
                        student = false;
                        console.log('error');
                        $("#sname").val('');
                        $("#semail").val('');
                        $("#smobile").val('');
                        $("#subjectId").val('');
                        $("#tid").val('');
                        $("#SignUpPassword").val('');
                        $("#CSignUpPassword").val('');
                        // toastr.error(data.message);
                    }
                    $(".waitBtn").addClass('hide');
                    $(".loginBtn").removeClass('hide');
                }
            });


        });
    });

    function signUp() {
        $.ajax({
            type: "POST",
            url: "controller/StudentController.php?name=signup&student=" + student,
            data: $('#signUpFrm').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $(".waitBtnSignUp").removeClass('hide');
                $(".signUpBtn").addClass('hide');
            },
            success: function (data) {
                if (data.status) {
                    console.log('success');
                    toastr.success(data.message);
                    $('#signUpFrm')[0].reset();
                } else {
                    console.log('error');
                    toastr.error(data.message);
                }
                $(".waitBtnSignUp").addClass('hide');
                $(".signUpBtn").removeClass('hide');
            }, error: function () {
                $(".waitBtnSignUp").addClass('hide');
                $(".signUpBtn").removeClass('hide');
            }
        });
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function chkemail(email) {
        $.ajax({
            type: "GET",
            url: "controller/authController.php?name=check_exists_email",
            data: 'email=' + encodeURI(email),
            dataType: 'json',
            success: function (data) {
                if (!data) {
                    $(".emailerror").removeClass('hide');
                    return false;
                } else {
                    $(".emailerror").addClass('hide');
                    return true;
                }
            }
        });
    }

    function chkmobile(phone) {
        $.ajax({
            type: "GET",
            url: "controller/authController.php?name=check_exists_mobile",
            data: 'phone=' + phone,
            dataType: 'json',
            success: function (data) {
                if (!data) {
                    $(".mobileerror").removeClass('hide');
                    return false;
                } else {
                    $(".mobileerror").addClass('hide');
                    return true;
                }
            }
        });
    }
</script>
</body>
</html>