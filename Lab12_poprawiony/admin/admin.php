<?php
// Włączenie konfiguracji
include_once '../cfg.php';
// Rozpoczęcie sesji
session_start();
// Funkcja do formularza logowania
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="logowanie">
                    <tr><td class="log4\'_t">[email]</td><td><input type="text" name="login_email" class="logowanie"/></td></tr>
                    <tr><td class="log4\'_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie"/></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}

// Funkcja do wyświetlania listy podstron
function ListaPodstron() {
    $conn = db_connect(); 
    $query = "SELECT * FROM page_list ORDER BY id DESC LIMIT 100"; 
    $result = mysqli_query($conn, $query);

    echo '<div class="lista-podstron">';
    echo '<ul>';
    while ($row = mysqli_fetch_assoc($result)) {
        $id = htmlspecialchars($row['id']);
        $title = htmlspecialchars($row['page_title']);

        echo '<li>';
        echo "$id - Tytuł: $title ";
        // Przycisk edytowania
        echo "<a href='admin.php?edit_id=$id'>Edytuj</a> ";
        // Przycisk usuwania
        echo "<a href='admin.php?delete_id=$id' onclick='return confirm(\"Czy na pewno chcesz usunąć tę podstronę?\")'>Usuń</a>";
        echo '</li>';
    }
    echo '</ul>';
    echo '</div>';

    mysqli_close($conn);
}

// Funkcja do edycji podstrony
function EdytujPodstrone($id) {
    $conn = db_connect();
    $query = "SELECT page_title, page_content, status FROM page_list WHERE id = ? LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($page_title, $page_content, $aktywna);
        $stmt->fetch();

        echo "<form method='post' action='admin.php'>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<p>Tytuł: <input type='text' name='page_title' value='$page_title'></p>";
        echo "<p>Treść: <textarea name='page_content'>$page_content</textarea></p>";
        echo "<p>Aktywna: <input type='checkbox' name='aktywna' " . ($aktywna ? "checked" : "") . "></p>";
        echo "<p><input type='submit' name='zapisz' value='Zapisz zmiany'></p>";
        echo "</form>";

        $stmt->close();
    } else {
        echo "Błąd podczas aktualizacji podstrony.";
    }

    $conn->close();
}

// Funkcja do dodawania nowej podstrony
function DodajNowaPodstrone(){
    echo '
    <form action="admin.php" method="post" style="text-align: center">
        <label for="tytul">Tytuł:</label><br>
        <input type="text" id="tytul" name="tytul"><br>
        <label for="alias">Alias:</label><br> <!-- Dodane pole dla aliasu -->
        <input type="text" id="alias" name="alias"><br>
        <label for="tresc">Treść:</label><br>
        <textarea id="tresc" name="tresc"></textarea><br>
        <input type="submit" name="submit_dodaj" value="Dodaj podstronę">
    </form>';
}

// Funkcja do usuwania podstrony
function UsunPodstrone($id) {
    $conn = db_connect();
    $query = "DELETE FROM page_list WHERE id = ? LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: admin.php");
        echo "Podstrona została usunięta.";
        $stmt->close();
    } else {
        echo "Błąd podczas usuwania podstrony.";
    }

    $conn->close();
}

// Sprawdzanie, czy użytkownik jest zalogowany
if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass) {
        $_SESSION['zalogowany'] = true;
    } else {
        echo FormularzLogowania();
    }
}

// Obsługa wylogowania
if (isset($_POST['wyloguj'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}


if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    EdytujPodstrone($id);
}
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    UsunPodstrone($id);

}
class ZarzadzajKategoriami
{
    private $conn;

    public function __construct()
    {
        // Utwórz połączenie z bazą danych
        $this->conn = db_connect();
    }

    public function __destruct()
    {
        // Zamknij połączenie z bazą danych po zakończeniu działania skryptu
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function DodajKategorie($nazwa, $matka = 0) {
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
    public function PokazKategorie()
    {
        $this->GenerujDrzewoKategorii();
    }
    private function GenerujDrzewoKategorii($matka = 0, $indent = 0)
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
                $this->GenerujDrzewoKategorii($row['id'], $indent + 1);
            }
            $stmt->close();
        } else {
            echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
        }
        $conn->close();
    }
}
$zarzadzajKategoriami = new ZarzadzajKategoriami();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj_kategorie'])) {
    $nazwaKategorii = $_POST['nazwa_kategorii'];
    $matkaKategorii = $_POST['matka_kategorii'];
    $zarzadzajKategoriami->DodajKategorie($nazwaKategorii, $matkaKategorii);
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edytuj_kategorie'])) {
    $idKategoriiDoEdycji = $_POST['edytuj_id_kategorii'];
    $nowaNazwaKategorii = $_POST['nowa_nazwa_kategorii'];
    $nowaMatkaKategorii = $_POST['nowa_matka_kategorii'];
    $zarzadzajKategoriami->EdytujKategorie($idKategoriiDoEdycji, $nowaNazwaKategorii, $nowaMatkaKategorii);
    header("Location: admin.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_usun_kategorie'])) {
    $idKategoriiDoUsuniecia = $_POST['usun_id_kategorii'];
    $zarzadzajKategoriami->UsunKategorie($idKategoriiDoUsuniecia);
    header("Location: admin.php");
    exit();
}



class ZarzadzajProduktami
{
    public function __construct()
    {
        // Utwórz połączenie z bazą danych
        $this->conn = db_connect();
    }

    public function __destruct()
    {
        // Zamknij połączenie z bazą danych po zakończeniu działania skryptu
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $data_wygasniecia, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
        $sql = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, data_wygasniecia, ilosc, status_dostepnosci, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssddsissis", $tytul, $opis, $cena_netto, $podatek_vat, $data_wygasniecia, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
            if (!$stmt->execute()) {
                echo "Błąd wykonania zapytania: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Błąd przy przygotowaniu zapytania: " . $this->conn->error;
        }
    }
    public function UsunProdukt($id) {
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
    public function EdytujProdukt($id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
        $sql = "UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc = ?, status_dostepnosci = ?, kategoria = ?, gabaryt = ?, zdjecie = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssddissssi", $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Błąd przy przygotowaniu zapytania: " . $this->conn->error;
        }
    }
    
    public function PokazProdukty() {
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
            $wynik .= "<a href='admin.php?edytuj_id=" . $row['id'] . "'>Edytuj</a> | ";
            $wynik .= "<a href='admin.php?usun_id=" . $row['id'] . "' onclick='return confirm(\"Czy na pewno chcesz usunąć ten produkt?\")'>Usuń</a><br><br>";
        }
        $conn->close();
        return $wynik;
    }

    public function PobierzDaneProduktu($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produkty WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produkt = $result->fetch_assoc();
        $stmt->close();
        return $produkt;
}

}
$zarzadzajProduktami = new ZarzadzajProduktami();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj_produkt'])) {
    $tytul = $_POST['tytul_produktu'];
    $opis = $_POST['opis_produktu'];
    $cena_netto = $_POST['cena_netto'];
    $podatek_vat = $_POST['podatek_vat'];
    $ilosc = $_POST['ilosc'];
    $status = $_POST['status'];
    $kategoria = $_POST['kategoria'];
    $gabaryt = $_POST['gabaryt'];
    $zdjecie = $_POST['zdjecie'];

    // Ustawienie daty wygaśnięcia na aktualną datę i godzinę
    $data_wygasniecia = date('Y-m-d H:i:s'); // format daty zgodny z MySQL

    // Wywołaj metodę dodającą nowy produkt do bazy danych.
    $zarzadzajProduktami->DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $data_wygasniecia, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);

    // Przekieruj z powrotem do strony admina, aby uniknąć powtórzenia operacji.
    header("Location: admin.php");
    exit;
}


if (isset($_GET['usun_id'])) {
    $idUsun = $_GET['usun_id'];
    $zarzadzajProduktami->UsunProdukt($idUsun);
    header("Location: admin.php");
    exit;
}
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
function addToCart($product_id, $quantity) {
    // Upewnij się, że sesja koszyka jest zainicjowana
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Połączenie z bazą danych
    $conn = db_connect();
    
    if ($conn) {
        // Przygotowanie zapytania SQL, aby uniknąć iniekcji SQL
        $stmt = $conn->prepare("SELECT tytul, cena_netto, podatek_vat, ilosc FROM produkty WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Sprawdzenie dostępnej ilości produktu w bazie danych
                $availableQuantity = $row['ilosc'];

                // Sprawdzenie, czy produkt jest już w koszyku
                if (isset($_SESSION['cart'][$product_id])) {
                    // Obliczenie nowej ilości po dodaniu
                    $newQuantity = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
                    
                    // Sprawdzenie, czy nowa ilość nie przekracza dostępnej ilości
                    if ($newQuantity > $availableQuantity) {
                        echo "Błąd: Nie można dodać do koszyka większej ilości produktu niż jest dostępna.";
                        return;
                    } else {
                        // Aktualizacja ilości produktu w koszyku
                        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
                    }
                } else {
                    // Sprawdzenie, czy dodawana ilość nie przekracza dostępnej ilości
                    if ($quantity > $availableQuantity) {
                        echo "Błąd: Nie można dodać do koszyka większej ilości produktu niż jest dostępna.";
                        return;
                    } else {
                        // Dodanie produktu do koszyka
                        $_SESSION['cart'][$product_id] = array(
                            'name' => $row['tytul'],
                            'price' => $row['cena_netto'],
                            'podatek_vat' => $row['podatek_vat'],
                            'quantity' => $quantity,
                        );
                    }
                }
            } else {
                echo "Błąd: Produkt o ID $product_id nie istnieje.";
            }
            $stmt->close();
        } else {
            echo "Błąd: Nie można przygotować zapytania.";
        }
        $conn->close();
    } else {
        echo "Błąd: Nie można połączyć się z bazą danych.";
    }
}


function calculateTotalWithVAT() {
    $total = 0;

    foreach ($_SESSION['cart'] as $product) {
        $netPrice = $product['price'];
        $vatRate = isset($product['podatek_vat']) ? $product['podatek_vat'] : 0; 
        $quantity = $product['quantity'];
        $grossPrice = $netPrice * (1 + $vatRate / 100);
        $total += $grossPrice * $quantity;
    }

    return $total;
}

function showCard() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Twój koszyk jest pusty.";
        return;
    }

    echo '<h1>Koszyk</h1>';
    echo '<table border="1">';
    echo '<tr><th>Tytuł</th><th>Cena netto</th><th>Ilość</th><th>Wartość z VAT</th><th>Akcje</th></tr>';

    $totalWithVAT = 0;

    foreach ($_SESSION['cart'] as $product_id => $product) {
        $netPrice = $product['price'];
        $vatRate = isset($product['podatek_vat']) ? $product['podatek_vat'] : 0;
        $quantity = $product['quantity'];
        
        // Obliczenie ceny brutto dla jednego produktu
        $grossPrice = $netPrice * (1 + $vatRate / 100);
        
        // Obliczenie wartości pozycji z VAT
        $subtotalWithVAT = $grossPrice * $quantity;
        $totalWithVAT += $subtotalWithVAT; // Dodanie do sumy całkowitej z VAT

        echo '<tr>';
        echo '<td>' . htmlspecialchars($product['name']) . '</td>';
        echo '<td>' . htmlspecialchars($product['price']) . ' zł</td>';
        echo '<td>' . htmlspecialchars($product['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($subtotalWithVAT) . ' zł</td>'; // Wyświetlenie wartości z VAT
        echo '<td>';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="remove_product_id" value="' . $product_id . '">';
        echo '<input type="submit" value="Usuń">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    // Wyświetlenie całkowitej sumy koszyka z VAT
    echo '<tr><td colspan="4" style="text-align:right">Suma z VAT:</td><td>' . htmlspecialchars($totalWithVAT) . ' zł</td></tr>';
    echo '</table>';
}



function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Usuń produkt z koszyka
        echo "Produkt o ID $product_id został usunięty z koszyka.";
    } else {
        echo "Nie znaleziono produktu o ID $product_id w koszyku.";
    }
}




/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="../css/styleadmin.css">
    <meta http-equiv="Content-Language" content="pl"/>
    <meta name="Author" content="Maciej Wysocki"/>
    <title>Panel Admina</title>
</head>
<body>
    <h2 style="text-align: center">Panel Zarządzania Podstronami</h2>
<?php
if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
    ListaPodstron();
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['zapisz'])) {
        $id = $_POST['id'];
        $tytul = $_POST['page_title'];
        $tresc = $_POST['page_content'];
        $aktywna = isset($_POST['aktywna']) ? 1 : 0;
    
        $conn = db_connect();
        $query = "UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ssii", $tytul, $tresc, $aktywna, $id);
            if ($stmt->execute()) {
                header("Location: admin.php");
                echo "Zmiany zostały zapisane.";
            } else {
                echo "Błąd podczas zapisu zmian: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
        }
        $conn->close();
    }
    
    if (isset($_POST['submit_dodaj'])) {
        $tytul = $_POST['tytul'];
        $alias = $_POST['alias'];
        $tresc = $_POST['tresc'];
    
        $conn = db_connect();
        $query = "INSERT INTO page_list (page_title, alias, page_content) VALUES (?, ?, ?)";
    
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sss", $tytul, $alias, $tresc);
            if ($stmt->execute()) {
                header("Location: admin.php");
                echo "Nowa podstrona została dodana.";
            } else {
                echo "Błąd podczas dodawania nowej podstrony.";
            }
            $stmt->close();
        } else {
            echo "Błąd podczas dodawania nowej podstrony.";
        }
    
        $conn->close();
    }
    DodajNowaPodstrone();
    
    echo'
    <h2 style="text-align: center">Panel Zarządzania Podstronami</h2>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_usun_kategorie'])) {
        $idKategoriiDoUsuniecia = $_POST['usun_id_kategorii'];
        UsunKategorie($idKategoriiDoUsuniecia);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edytuj_produkt'])) {
        $idProduktu = $_POST['id_produktu'];
        $tytul = $_POST['tytul_produktu'];
        $opis = $_POST['opis_produktu'];
        $cena_netto = $_POST['cena_netto'];
        $podatek_vat = $_POST['podatek_vat'];
        $ilosc = $_POST['ilosc'];
        $status = $_POST['status'];
        $kategoria = $_POST['kategoria'];
        $gabaryt = $_POST['gabaryt'];
        $zdjecie = $_POST['zdjecie'];

        // Wywołaj metodę aktualizującą produkt w bazie danych.
        $zarzadzajProduktami->EdytujProdukt($idProduktu, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);

        // Przekieruj z powrotem do strony admina, aby uniknąć powtórzenia operacji.
        header("Location: admin.php");
        exit;
    }


    $zarzadzajKategoriami->PokazKategorie();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];
    
            // Walidacja ID produktu i ilości
            if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
                echo "Błąd: Niepoprawne dane produktu.";
            } else {
                addToCart($product_id, $quantity);
                // Przekierowanie do admin.php po udanym dodaniu produktu do koszyka
                header('Location: admin.php');
                exit; // Zakończenie działania skryptu, aby zapobiec dalszemu wykonywaniu kodu
            }
            if (isset($_POST['remove_product_id'])) {
                $remove_product_id = $_POST['remove_product_id'];
                removeFromCart($remove_product_id);
            }
        } else {
            echo "Błąd: Brak wymaganych danych.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
        $product_id_to_remove = $_POST['remove_product_id'];
        removeFromCart($product_id_to_remove);
        // Opcjonalnie możesz przekierować użytkownika z powrotem na stronę koszyka, aby odświeżyć widok
        header('Location: admin.php');
        exit;
    }

    
    echo '
    <br/><br/>
        <!-- Formularz dodawania kategorii -->
        <form action="admin.php" method="post">
            Nazwa Kategorii: <input type="text" name="nazwa_kategorii" />
            ID Kategorii Nadrzędnej (0 dla kategorii głównej): <input type="number" name="matka_kategorii" value="0" />
            <input type="submit" name="submit_dodaj_kategorie" value="Dodaj Kategorię" />
        </form>
        <br/><br/>
        <!-- Formularz usuwania kategorii -->
        <form action="admin.php" method="post">
            ID Kategorii do Usunięcia: <input type="number" name="usun_id_kategorii" />
            <input type="submit" name="submit_usun_kategorie" value="Usuń Kategorię" />
        </form>
        <!-- Formularz edycji kategorii -->
        <form action="admin.php" method="post">
            ID Kategorii: <input type="number" name="edytuj_id_kategorii" />
            Nowa Nazwa Kategorii: <input type="text" name="nowa_nazwa_kategorii" />
            Nowa ID Kategorii Nadrzędnej: <input type="number" name="nowa_matka_kategorii" />
            <input type="submit" name="submit_edytuj_kategorie" value="Edytuj Kategorię" />
        </form>';
    echo '
        <h2 style="text-align: center">Panel Zarządzania Produktami</h2>
        
        <!-- Dodawanie nowego produktu -->
        
        <form method="post">
            Tytuł Produktu: <input type="text" name="tytul_produktu"/><br>
            Opis Produktu: <textarea name="opis_produktu"></textarea><br>
            Cena Netto: <input type="number" step="0.01" name="cena_netto" /><br>
            Podatek VAT: <input type="number" name="podatek_vat" /><br>
            Data Wygaśnięcia: <input type="datetime-local" name="data_wygasniecia" /><br>
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
        </form>
    

        <!-- Wyświetlanie produktów -->
        <h2>Lista Produktów</h2>';
        echo $zarzadzajProduktami->PokazProdukty();
        if (isset($_GET['edytuj_id'])) {
            $idProduktuDoEdycji = $_GET['edytuj_id'];
            $produktDoEdycji = $zarzadzajProduktami->PobierzDaneProduktu($idProduktuDoEdycji);
        
            // Wyświetl formularz wypełniony danymi produktu
            echo '
            <form method="post">
                <input type="hidden" name="id_produktu" value="'.$produktDoEdycji['id'].'" />
                Tytuł Produktu: <input type="text" name="tytul_produktu" value="'.$produktDoEdycji['tytul'].'"/><br>
                Opis Produktu: <textarea name="opis_produktu">'.$produktDoEdycji['opis'].'</textarea><br>
                Cena Netto: <input type="number" step="0.01" name="cena_netto" value="'.$produktDoEdycji['cena_netto'].'"/><br>
                Podatek VAT: <input type="number" name="podatek_vat" value="'.$produktDoEdycji['podatek_vat'].'"/><br>
                Ilość: <input type="number" name="ilosc" value="'.$produktDoEdycji['ilosc'].'"/><br>
                Status: <input type="text" name="status" value="'.$produktDoEdycji['status_dostepnosci'].'"/><br>
                Kategoria: <input type="text" name="kategoria" value="'.$produktDoEdycji['kategoria'].'"/><br>
                Gabaryt: <input type="text" name="gabaryt" value="'.$produktDoEdycji['gabaryt'].'"/><br>
                Zdjęcie (URL): <input type="text" name="zdjecie" value="'.$produktDoEdycji['zdjecie'].'"/><br>
                <input type="submit" name="submit_edytuj_produkt" value="Zapisz zmiany" /><br>
            </form>';
    }
    echo'
        <h1>Dodaj produkt do koszyka</h1>
        <form method="post" action="admin.php">
            <label for="product_id">ID produktu:</label>
            <input type="text" id="product_id" name="product_id" required><br>
                
            <label for="quantity">Ilość:</label>
            <input type="number" id="quantity" name="quantity" min="1" required><br>
                
            <input type="submit" value="Dodaj do koszyka">
        </form>';
        
        showCard();
        
}

