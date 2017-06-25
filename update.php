<?php

require 'db.php';
session_start();
//Increment poke by 1
$poke = $mysqli->escape_string($_POST['poke']);
$email = $mysqli->escape_string($_POST['email']);
$id = $mysqli->escape_string($_POST['id']);
$mysqli->query("UPDATE users SET poke=poke+1 WHERE id='$id'") or die($mysqli->error);

//send mail
$username = $_SESSION['username'];
$result = $mysqli->query("SELECT email FROM users where username='$username'") or die($mysqli->error);
$me = $result->fetch_assoc();
$to = $email; // user's email
$from = $me['email']; // my email
$subject = "Poke";
$message = $username." pokina tave.";
mail($to, $subject, $message);
