<?php
require 'db.php';
session_start();
// Check if user is logged in using the session variable
if ($_SESSION['logged_in'] != 1) {
    header("location: login.php");
}
$_SESSION['error'] = "";

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

//the form has been submitted with post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Against removing required attr by inspect element
    if (!empty($_POST['username'] && $_POST['first_name'] && $_POST['last_name'] && $_POST['email'] && $_POST['password'])) {
        //check if two passwords are equal to each other
        if ($_POST['password'] == $_POST['confirmpassword']) {
            if (preg_match('/^(?=.*[A-Z])(?=.*\d).*$/', $_POST['password'])) {//check regex for uppercase letter and number
                $username = $_SESSION['username'];
                //define other variables with submitted values from $_POST
                $hash = md5(rand(0, 1000));
                //create SQL query string for inserting data into the database
                if (!($stmt = $mysqli->prepare("UPDATE users SET first_name=?, last_name=?"
                        . ", email=?, password=?, hash=? WHERE username=?"))) {
                    echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
                }
                if (!$stmt->bind_param("ssssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_BCRYPT), $hash, $username)) {
                    echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
                } else {
                    $stmt->close();
                    $_SESSION['message'] = "Vartotojo duomenys pakeisti sėkmingai!";
                    //redirect the user to welcome.php
                    header("location: index.php");
                }
            } else {
                $_SESSION['error'] = "Slaptažodį turi sudaryti viena didžioji raidė ir skaičius!";
            }
        } else {
            $_SESSION['error'] = "Nesutampa slaptažodis!";
        }
    } else {
        $_SESSION['error'] = "Visi laukai yra privalomi!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <script src="js/inputRequired.js"></script>
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
                        <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span><?= sanitizeString($_SESSION['username']) ?></a></li>
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
                            <?php if ($_SESSION['error'] != "") { ?>
                            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                            <?php } ?>
                            <h2>Profilio redagavimas</h2>
                            <div class="form-group">
                                <label for="username" class="col-md-6 control-label">Prisijungimo vardas</label>
                                <div class="col-md-4 input-group">
                                    <p class="h1>"><?=sanitizeString($_SESSION['username'])?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="col-md-6 control-label">Vardas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="first_name" id="first_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-md-6 control-label">Pavardė</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="last_name" id="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-6 control-label">El. paštas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="email" name="email" id="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-md-6 control-label">Slaptažodis</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="password" name="password" id="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword" class="col-md-6 control-label">Slaptažodžio pakartojimas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary registerbutton" name="submit" id="register" value="Saugoti">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>