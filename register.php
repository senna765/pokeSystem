<?php
require 'db.php';

function filterText($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

session_start();
$_SESSION['message'] = '';
//the form has been submitted with post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //check if two passwords are equal to each other
    if ($_POST['password'] == $_POST['confirmpassword']) {
        if (preg_match('/^(?=.*[A-Z])(?=.*\d).*$/', $_POST['password'])) {//check regex for uppercase letter and number
            //define other variables with submitted values from $_POST
            $username = $mysqli->real_escape_string($_POST['username']);
            $first_name = $mysqli->real_escape_string($_POST['first_name']);
            $last_name = $mysqli->real_escape_string($_POST['last_name']);
            $email = $mysqli->real_escape_string($_POST['email']);
            //md5 hash password for security
            $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
            $hash = $mysqli->escape_string(md5(rand(0, 1000)));
            //set session variables to display on index page
            $_SESSION['username'] = $username;
            //create SQL query string for inserting data into the database
            $sql = $mysqli->query("SELECT username FROM users WHERE username='$username'") or die($mysqli->error);
            if ($sql->num_rows != 0) {
                $_SESSION['message'] = "Toks vartotojas jau egzistuoja!";
            } else {
                $sql = "INSERT INTO users(username, first_name, last_name, email, password, hash) "
                        . "VALUES('$username', '$first_name', '$last_name', '$email', '$password','$hash')" or die($mysqli->error);
                if ($mysqli->query($sql)) {
                    $_SESSION['message'] = "Jūs sėkmingai užsiregistravote";
                    $_SESSION['logged_in'] = true; // So we know the user has logged in
                    header("location: index.php");
                }
            }
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
        <title>Registracijos forma</title>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="index.php" class="navbar-brand">Baksnotojas 2000</a>
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
                            <h2>Registracija</h2>
                            <div class="form-group">
                                <label for="username" class="col-md-6 control-label">Prisijungimo vardas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="username" value="<?= (isset($_POST['username']) ? filterText($_POST['username']) : '') ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="col-md-6 control-label">Vardas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="first_name" value="<?= (isset($_POST['first_name']) ? filterText($_POST['first_name']) : '') ?>"required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-md-6 control-label">Pavardė</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="text" name="last_name" value="<?= (isset($_POST['last_name']) ? filterText($_POST['last_name']) : '') ?>"required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-6 control-label">El. paštas</label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control" type="email" name="email" value="<?= (isset($_POST['email']) ? filterText($_POST['email']) : '') ?>"required>
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
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>