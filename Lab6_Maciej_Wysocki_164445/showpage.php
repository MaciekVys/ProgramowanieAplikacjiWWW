<?php
// Podłącz plik cfg.php
include('cfg.php');

// Pobierz parametr z URL (możesz go dostosować do swojej struktury URL)
$idStrony = isset($_GET['id']) ? $_GET['id'] : 1;

// Zapytanie SQL do pobrania treści strony
$query = "SELECT tytul, zawartosc FROM tabela_stron WHERE id = :id LIMIT 1";
$stmt = $dbConnection->prepare($query);
$stmt->bindParam(':id', $idStrony, PDO::PARAM_INT);
$stmt->execute();

// Pobierz wynik
$strona = $stmt->fetch(PDO::FETCH_ASSOC);

// Sprawdź, czy strona istnieje
if (!$strona) {
    echo "Strona o podanym ID nie istnieje.";
    die();
}

// Wyświetl treść strony
echo "<h1>{$strona['tytul']}</h1>";
echo "<p>{$strona['zawartosc']}</p>";
?>
