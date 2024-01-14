<?php

include_once 'cfg.php';

function DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
    $conn = db_connect(); // Funkcja nawiązująca połączenie z bazą danych
    $sql = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc, status_dostepnosci, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssddisssd", $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }

    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj_produkt'])) {
    // Pobieranie i przetwarzanie danych z formularza

    DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie);

    // Wykonaj przekierowanie tylko jeśli nie było wcześniej żadnego wyjścia
 
    header("Location: admin.php");

    exit;
}
function UsunProdukt($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }

    $conn->close();
}

function EdytujProdukt($id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc = ?, status_dostepnosci = ?, kategoria = ?, gabaryt = ?, zdjecie = ? WHERE id = ?");
    
    if ($stmt) {
        // Upewnij się, że masz dziewięć 's' dla tekstowych i numerycznych wartości oraz 'i' dla id
        $stmt->bind_param("ssddisssdi", $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $id);
        $stmt->execute();
        header("Location: admin.php");
        exit;
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }

    $conn->close();
}


function PokazProdukty() {
    $wynik = '';
    $conn = db_connect();
    $query = "SELECT * FROM produkty";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $wynik .= "ID: " . $row['id'] . "<br>";
        $wynik .= "Tytuł: " . $row['tytul'] . "<br>";
        $wynik .="Opis: " . $row['opis'] . "<br>";
        $wynik .="Data wygaśnięcia: " . $row['data_wygasniecia'] . "<br>";
        $wynik .="Cena netto: " . $row['cena_netto'] . "<br>";
        $wynik .="Podatek VAT: " . $row['podatek_vat'] . "<br>";
        $wynik .="Ilość dostępnych sztuk: " . $row['ilosc'] . "<br>";
        $wynik .="Status dostępności: " . $row['status_dostepnosci'] . "<br>";
        $wynik .="Kategoria: " . $row['kategoria'] . "<br>";
        $wynik .="Gabaryt produktu: " . $row['gabaryt'] . "<br>";
        $wynik .="Zdjęcie: " . $row['zdjecie'] . "<br><br>";
    }
    $conn->close();
    return $wynik;
}
function FormularzDodajProdukt(){
    return '
    <form method="post">
        Tytuł Produktu: <input type="text" name="tytul_produktu"/><br>
        Opis Produktu: <textarea name="opis_produktu"></textarea><br>
        Cena Netto: <input type="number" step="0.01" name="cena_netto" /><br>
        Podatek VAT: <input type="number" name="podatek_vat" /><br>
        Ilość: <input type="number" name="ilosc" /><br>
        Status: <select name="status">
                    <option value="dostepny">Dostępny</option>
                    <option value="niedostepny">Niedostępny</option>
                    <option value="oczekujacy">Oczekujący</option>
                </select><br>
        Kategoria: <input type="text" name="kategoria" /><br>
        Gabaryt: <select name="gabaryt">
                    <option value="maly">Mały</option>
                    <option value="sredni">Średni</option>
                    <option value="duzy">Duży</option>
                 </select><br>
        Zdjęcie (URL): <input type="text" name="zdjecie" /><br>
        <input type="submit" name="submit_dodaj_produkt" value="Dodaj Produkt" />
    </form>';
}

function FormularzUsunProdukt() {
    $wynik =  '
    <form action="" method="post">
        ID Produktu do Usunięcia: <input type="number" name="usun_id_produktu" required />
        <input type="submit" name="submit_usun_produkt" value="Usuń Produkt" />
    </form>';
    return $wynik;
}
function FormularzEdytujProdukt() {
    $wynik = '
    <form action="" method="post">
        ID Produktu do Edycji: <input type="number" name="edytuj_id_produktu" required /><br>
        Nowy Tytuł Produktu: <input type="text" name="nowy_tytul_produktu" required /><br>
        Nowy Opis Produktu: <textarea name="nowy_opis_produktu" required></textarea><br>
        Nowa Cena Netto: <input type="number" step="0.01" name="nowa_cena_netto" required /><br>
        Nowy Podatek VAT: <input type="number" name="nowy_podatek_vat" required /><br>
        Nowa Ilość: <input type="number" name="nowa_ilosc" required /><br>
        Nowy Status: <select name="nowy_status">
                          <option value="dostepny">Dostępny</option>
                          <option value="niedostepny">Niedostępny</option>
                          <option value="oczekujacy">Oczekujący</option>
                      </select><br>
        Nowa Kategoria: <input type="number" name="nowa_kategoria" required /><br>
        Nowy Gabaryt: <select name="nowy_gabaryt">
                           <option value="maly">Mały</option>
                           <option value="sredni">Średni</option>
                           <option value="duzy">Duży</option>
                       </select><br>
        Nowe Zdjęcie (URL): <input type="text" name="nowe_zdjecie" /><br>
        <input type="submit" name="submit_edytuj_produkt" value="Edytuj Produkt" />
    </form>';
    return $wynik;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj_produkt'])) {
    $tytul = $_POST['tytul_produktu'];
    $opis = $_POST['opis_produktu'];
    $cena_netto = $_POST['cena_netto']; // Upewnij się, że jest to wartość zmiennoprzecinkowa.
    $podatek_vat = $_POST['podatek_vat']; // Upewnij się, że jest to wartość zmiennoprzecinkowa.
    $ilosc = $_POST['ilosc']; // Upewnij się, że jest to wartość całkowita.
    $status_dostepnosci = $_POST['status']; // Tutaj przekazujesz wartość tekstową.
    $kategoria = $_POST['kategoria']; // Tutaj przekazujesz wartość tekstową.
    $gabaryt = $_POST['gabaryt']; // Tutaj przekazujesz wartość tekstową.
    $zdjecie = $_POST['zdjecie']; // Jeśli przekazujesz URL, to będzie tekst, jeśli BLOB, to obsługa będzie inna.

    DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie);
    header("Location: admin.php");
    exit;
}

   
