<?php
require 'db.php';
$_SESSION['message'] = "";
session_start();
// Check if user is logged in using the session variable
if ($_SESSION['logged_in'] != 1) {
    header("location: login.php");
}
$_SESSION['message'] = '';
//the form has been submitted with post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //check if two passwords are equal to each other
    if ($_POST['password'] == $_POST['confirmpassword']) {
        if (preg_match('/^(?=.*[A-Z])(?=.*\d).*$/', $_POST['password'])) {//check regex for uppercase letter and number
            $username = $_SESSION['username'];
            //define other variables with submitted values from $_POST
            $first_name = $mysqli->real_escape_string($_POST['first_name']);
            $last_name = $mysqli->real_escape_string($_POST['last_name']);
            $email = $mysqli->real_escape_string($_POST['email']);
            //md5 hash password for security
            $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
            $hash = $mysqli->escape_string(md5(rand(0, 1000)));
            //create SQL query string for inserting data into the database
            $sql = $mysqli->query("UPDATE users SET first_name='$first_name', last_name='$last_name'"
                    . ", email='$email', password='$password' WHERE username='$username'") or die($mysqli->error);
            $_SESSION['message'] = "Vartotojo duomenys pakeisti sėkmingai!";
            //redirect the user to welcome.php
            header("location: index.php");
        } else {
            $_SESSION['message'] = "Slaptažodį turi sudaryti viena didžioji raidė ir skaičius!";
        }
    } else {
        $_SESSION['message'] = "Nesutampa slaptažodis";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <script src="js/remove.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <meta charset="utf-8">
        <title>Profilio redagavimas</title>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a href="index.php" class="navbar-brand">Baksnotojas 2000</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span><?= $_SESSION['username'] ?></a></li>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Atsijungti</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <form class="form-horizontal" role="form" method="post">
                        <div class="jumbotron">
                            <?php if ($_SESSION['message'] != "") { ?>
                                <div class="alert alert-danger"><?= $_SESSION['message'] ?></div>
                            <?php } ?>
                            <h2>Profilio redagavimas</h2>
                            <div class="form-group">
                                <label for="username" class="col-md-6 control-label">Prisijungimo vardas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="username" value="<?= $_SESSION['username'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="col-md-6 control-label">Vardas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="first_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-md-6 control-label">Pavardė</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-6 control-label">El. paštas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-md-6 control-label">Slaptažodis</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="password" name="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword" class="col-md-6 control-label">Slaptažodžio pakartojimas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="password" name="confirmpassword" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary registerbutton" name="submit" value="Saugoti">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>