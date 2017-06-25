<?php
require 'db.php';
session_start();
// Check if user is logged in using the session variable
if ($_SESSION['logged_in'] != 1) {
    header("location: login.php");
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
        <script src="js/poke.js"></script>
        <meta charset="utf-8">
        <title>Baksnotojas 2000</title>
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
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="jumbotron">
                        <div id="msg" class="alert alert-success" style="display: none;">Laiškas sėkmingai išsiųstas</div>
                        <?php if ($_SESSION['message'] != "") { ?>
                        <div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" style="color: black!important;">&times;</a>
                            <?= $_SESSION['message'] ?></div>
                        <?php } $_SESSION['message'] = "" ?>
                        <h2>Vartotojai</h2>
                        <div class="table-row header">
                            <div class="wrapper attributes">
                                <div class="wrapper title-comment">
                                    <div class="column comment">Vardas</div>
                                    <div class="column comment">Pavardė</div>
                                    <div class="column comment">El. paštas</div>
                                    <div class="column comment">Poke skaičius</div>
                                    <div class="column comment"></div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $result = $mysqli->query("SELECT * FROM users") or die($mysqli->error);
                        while ($users = $result->fetch_assoc()) {
                            ?>
                            <form class="form-horizontal" role="form" name="table" method="post" data-counter="<?php echo $users['id'] ?>">
                                <div class="table-row">
                                    <div class="wrapper attributes">
                                        <div class="wrapper title-comment">
                                            <form class="form-horizontal" role="form" name="table" method="post" data-counter="<?=$users['id'] ?>">
						<input class="hidden" type="text" name="id" value="<?=$users['id']?>">
                                                <div class="column comment"><?= $users['first_name'] ?><input class="hidden" type="text" name="first_name"></div>
                                                <div class="column comment"><?= $users['last_name'] ?><input class="hidden" type="text" name="last_name"></div>
                                                <div class="column comment"><?= $users['email'] ?><input class="hidden" type="email" name="email" id="email_<?=$users['id']?>" value="<?=$users['email']?>"></div>
                                                <div class="column comment"><input class="clear" type="text" name="poke" id="poke_<?php echo $users['id'] ?>" value="<?=$users['poke']?>"></div>
                                                <div class="column comment"><input class="btn btn-primary registerbutton" type="submit" value="Poke" id="submit_<?=$users['id']?>" name="submit"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>   
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>