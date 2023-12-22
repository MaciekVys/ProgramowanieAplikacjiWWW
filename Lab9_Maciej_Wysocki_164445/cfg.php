<?php

$login = 'admin';
$pass = 'root';

function db_connect()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'moja_strona';


    $conn = new mysqli($hostname, $username, $password, $db);

    $conn->set_charset('utf8mb4');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


