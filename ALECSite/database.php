<?php

$host = "server1550.mysql.database.azure.com";
$dbname = "alecsitetest";
$username = "datateam@server1550";
$password = "consideritDunn1550";

$mysqli = new mysqli(hostname: $host,
    username: $username,
    password: $password,
    database: $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli; //hello 

