<?php
require_once 'controller/database.php';
session_start();
if (isset($_SESSION["loggedIn"])) {
    header('Location:index.php', true);
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
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link href="css/toastr.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container">
    <div class="forms">
        <div class="form login">
            <span class="title">Login</span>

            <form action="#" id="loginFrm" method="post">
                <div class="input-field">
                    <input type="text" placeholder="Enter your email" required autocomplete="newEmail" name="newEmail"
                           id="newEmail">
                    <i class="uil uil-envelope"></i>
                </div>

                <div class="input-field">
                    <input type="password" id="password" placeholder="Enter your password" required
                           autocomplete="new-password" name="password">
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>

                <div class="input-field button">
                    <input type="button" value="Login Now" class="loginBtn">
                    <button class="btn waitBtn hide" type="button" disabled>
                        <div class="loader"></div>
                        <span class="sr-only">Please wait...</span>
                    </button>
                </div>
            </form>

            <div class="login-signup">
                    <span class="text">Not a member?
                        <a href="#" class="text signup-link">Signup now</a> (Only for teachers)
                    </span>
            </div>
        </div>

        <div class="form signup">
            <span class="title">Registration</span>

            <form action="#" id="signUpFrm" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter your name" required autocomplete="new-name"
                                   name="tName">
                            <i class="uil uil-user"></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter your email" required autocomplete="new-email"
                                   name="email">
                            <i class="uil uil-envelope"></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-field">
                            <input type="text" placeholder="Enter your phone number" required autocomplete="new-mobile"
                                   name="phone" onkeypress="return isNumber(event);" maxlength="10">
                            <i class="uil uil-mobile-android"></i>
                        </div>
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
                            <input type="text" placeholder="Enter your teacher ID" required autocomplete="new-tId"
                                   name="teacherId" maxlength="15">
                            <i class="uil uil-mobile-android"></i>
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
                                   name="confPassword" autocomplete="new-password">
                            <i class="uil uil-lock icon"></i>
                            <i class="uil uil-eye-slash showHidePw"></i>
                        </div>
                    </div>
                </div>

                <div class="input-field button">
                    <input type="button" value="Register Now" class="signUpBtn">
                    <button class="btn waitBtnSignUp hide" type="button" disabled>
                        <div class="loader"></div>
                        <span class="sr-only">Please wait...</span>
                    </button>
                </div>
            </form>

            <div class="login-signup">
                    <span class="text">Already have an account?
                        <a href="#" class="text login-link">Login now</a>
                    </span>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/toastr.min.js"></script>

<script>
    const container = document.querySelector(".container"),
        pwShowHide = document.querySelectorAll(".showHidePw"),
        pwFields = document.querySelectorAll(".password"),
        signup = document.querySelector(".signup-link"),
        login = document.querySelector(".login-link");

    pwShowHide.forEach(eyeIcon => {
        eyeIcon.addEventListener("click", () => {
            pwFields.forEach(pwField => {
                if (pwField.type === "password") {
                    pwField.type = "text";

                    pwShowHide.forEach(icon => {
                        icon.classList.replace("uil-eye-slash", "uil-eye");
                    })
                } else {
                    pwField.type = "password";

                    pwShowHide.forEach(icon => {
                        icon.classList.replace("uil-eye", "uil-eye-slash");
                    })
                }
            })
        })
    })

    signup.addEventListener("click", () => {
        container.classList.add("active");
        $(".waitBtnSignUp").addClass('hide');
        $(".signUpBtn").removeClass('hide');
        $(".waitBtn").addClass('hide');
        $(".loginBtn").removeClass('hide');
    })

    login.addEventListener("click", () => {
        container.classList.remove("active");
        $(".waitBtnSignUp").addClass('hide');
        $(".signUpBtn").removeClass('hide');
        $(".waitBtn").addClass('hide');
        $(".loginBtn").removeClass('hide');
    });

    const base_url = 'http://localhost/finalProject/';

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
                remote: base_url + "controller/authController.php?name=check_exists_email"
            },
            phone: {
                required: true,
                remote: base_url + "controller/authController.php?name=check_exists_mobile"
            },
            subjectId: {
                required: true,
                dropdown: true
            },
            teacherId: {
                required: true
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
                remote: "Email address is already taken, please enter different"
            },
            phone: {
                required: "Please enter phone",
                remote: "Phone number is already taken, please enter different"
            },
            subjectId: {
                required: "Please select subject",
                dropdown: "Please select subject"
            },
            teacherId: {
                required: "Please enter teacherId",
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

    $(document).on("click", ".signUpBtn", function () {
        if ($("#signUpFrm").valid()) {
            console.log('form valid');
            $.ajax({
                type: "POST",
                url: base_url + "controller/authController.php?name=signup",
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
                        login.click();
                        $('#signUpFrm')[0].reset();
                    } else {
                        console.log('error');
                        toastr.error(data.message);
                    }
                    $(".waitBtnSignUp").addClass('hide');
                    $(".signUpBtn").removeClass('hide');
                }
            });
        } else {
            return false;
        }
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
</body>
</html>