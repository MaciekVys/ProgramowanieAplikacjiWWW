<?php

include_once 'cfg.php';

function DodajKategorie($nazwa, $matka = 0) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO kategorie (nazwa, matka) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("si", $nazwa, $matka);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }
    $conn->close();
}

function UsunKategorie($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM kategorie WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Kategoria została usunięta.";
        } else {
            echo "Błąd przy usuwaniu kategorii: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }
    $conn->close();
}

function EdytujKategorie($id, $nowaNazwa, $nowaMatka) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE kategorie SET nazwa = ?, matka = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sii", $nowaNazwa, $nowaMatka, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }
    $conn->close();
}
function GenerujDrzewoKategorii($matka = 0, $indent = 0)
{
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM kategorie WHERE matka = ?");
    if ($stmt) {
        $stmt->bind_param("i", $matka);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo str_repeat("&nbsp;&nbsp;", $indent); // Ustawia odpowiednią ilość spacji dla wcięcia
            echo "Kategoria: {$row['nazwa']}<br>";

            // Wywołaj rekurencyjnie funkcję dla podkategorii
            GenerujDrzewoKategorii($row['id'], $indent + 1);
        }
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }
    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj_kategorie'])) {
    $nazwaKategorii = $_POST['nazwa_kategorii'];
    $matkaKategorii = $_POST['matka_kategorii'];
    DodajKategorie($nazwaKategorii, $matkaKategorii);

}

function FormularzDodajKategorie(){
    $wynik = 
    '<form action="admin.php" method="post">
        Nazwa Kategorii: <input type="text" name="nazwa_kategorii" />
        ID Kategorii Nadrzędnej (0 dla kategorii głównej): <input type="number" name="matka_kategorii" value="0" />
        <input type="submit" name="submit_dodaj_kategorie" value="Dodaj Kategorię" />
    </form>';
    return $wynik;
}
function FormularzEdytujKategorie(){
    $wynik = 
    '<form action="admin.php" method="post">
        ID Kategorii: <input type="number" name="edytuj_id_kategorii" />
        Nowa Nazwa Kategorii: <input type="text" name="nowa_nazwa_kategorii" />
        Nowa ID Kategorii Nadrzędnej: <input type="number" name="nowa_matka_kategorii" />
        <input type="submit" name="submit_edytuj_kategorie" value="Edytuj Kategorię" />
    </form>';
    return $wynik;
}
function FormularzUsunKategorie(){
    $wynik = 
    '<form action="admin.php" method="post">
        ID Kategorii do Usunięcia: <input type="number" name="usun_id_kategorii" />
        <input type="submit" name="submit_usun_kategorie" value="Usuń Kategorię" />
    </form>';
    return $wynik;
}
?>