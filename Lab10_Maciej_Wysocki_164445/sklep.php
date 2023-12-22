<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "moja_strona";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function DodajKategorie($nazwa, $matka = 0) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO kategorie (nazwa, matka) VALUES (?, ?)");
    $stmt->bind_param("si", $nazwa, $matka);
    $stmt->execute();
    $stmt->close();
}
function UsunKategorie($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM kategorie WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
function EdytujKategorie($id, $nowaNazwa, $nowaMatka) {
    global $conn;
    $stmt = $conn->prepare("UPDATE kategorie SET nazwa = ?, matka = ? WHERE id = ?");
    $stmt->bind_param("sii", $nowaNazwa, $nowaMatka, $id);
    $stmt->execute();
    $stmt->close();
}
function PokazKategorie($matka = 0, $poziom = 0) {
    global $conn;
    $sql = "SELECT id, nazwa FROM kategorie WHERE matka = $matka";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
        echo str_repeat(" - ", $poziom) . $row["nazwa"] . "<br>";
        PokazKategorie($row["id"], $poziom + 1);
    }
}
