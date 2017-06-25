<?php
require('db.php');
session_start();
$_SESSION['error'] = '';
//the form has been submitted with post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) { //user logging in
        /* User login process, checks if user exists and password is correct */
// Escape username to protect against SQL injections
        $username = $mysqli->escape_string($_POST['username']);
        $sql = $mysqli->query("SELECT * FROM users WHERE username='$username'") or die($mysqli->error);
        if ($sql->num_rows == 0) { // User doesn't exist
            $_SESSION['error'] = "Vartotojas su tokiu vartotojo vardu neegzistuoja!";
        } else { // User exists
            $user = $sql->fetch_assoc();
            if (password_verify($_POST['password'], $user['password'])) {
                $_SESSION['username'] = $user['username'];
                // This is how we'll know the user is logged in
                $_SESSION['logged_in'] = true;
                //redirect user to index.php
                header("location: index.php");
            } else {
                $_SESSION['error'] = "Įvedėte blogą slaptažodį, bandykite dar kartą!";
            }
        }
    }
    if (isset($_POST['register'])) {
        header("location: register.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/inputRequired.js"></script>
        <meta charset="utf-8">
        <title>Prisijungimo forma</title>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="index.php" class="navbar-brand">Baksnotojas 2000</a>
                </div>
                <div>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
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
                            <h2>Prisijungimas</h2>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="username" id="username" placeholder="Prisijungimo vardas" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Slaptažodis" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success loginbutton" name="login" value="Prisijungti">
                                <input type="submit" class="btn btn-primary loginbutton" name="register" value="Registruotis" onclick="window.location.href='register.php'">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>