<?php

/* Database connection settings */
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'pokeSystem';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    printf("Connection failed: %s\n", $mysqli->connect_error);
    die();
}