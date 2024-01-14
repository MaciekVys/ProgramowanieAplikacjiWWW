<?php

/**
 * Dane logowania do bazy danych.
 */
$login = 'admin'; // W prawdziwych aplikacjach unikać przechowywania hasła w kodzie.
$pass = 'root';   // Rozważ użycie zmiennych środowiskowych lub pliku konfiguracyjnego.

/**
 * Nawiązuje połączenie z bazą danych MySQL.
 */
function db_connect() {
    // Włączenie raportowania błędów dla MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Dane do połączenia z bazą danych
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'moja_strona';

    // Tworzenie nowego połączenia
    $conn = new mysqli($hostname, $username, $password, $db);

    // Ustawienie kodowania znaków
    $conn->set_charset('utf8mb4');

    // Sprawdzenie, czy połączenie się powiodło
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Zwrócenie obiektu połączenia
    return $conn;
}

?>
