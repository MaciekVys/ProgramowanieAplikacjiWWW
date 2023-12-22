<?php
include_once '../cfg.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
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


function ListaPodstron() {
    $conn = db_connect(); 
    $query = "SELECT * FROM page_list ORDER BY id DESC LIMIT 100"; 
    $result = mysqli_query($conn, $query);

    echo '<div class="lista-podstron">';
    echo '<ul>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li>';
        echo "ID: " . htmlspecialchars($row['id']) . " - Tytuł: " . htmlspecialchars($row['page_title']);
        echo " <a href='edytuj_podstrone.php?id=" . $row['id'] . "'>Edytuj</a>"; // Przykładowy link do edycji
        echo " <a href='usun_podstrone.php?id=" . $row['id'] . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>Usuń</a>"; // Link do usunięcia z potwierdzeniem
        echo '</li>';
    }
    echo '</ul>';
    echo '</div>';

    mysqli_close($conn);
}



function EdytujPodstrone($id) {
    $conn = db_connect(); // Funkcja połączenia z bazą danych
    $query = "UPDATE page_list SET tytul = ?, tresc = ? WHERE id = ? LIMIT 1";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssi", $tytul, $tresc, $id);
        $stmt->execute();
        $stmt->close();
        echo "Podstrona została zaktualizowana.";
    } else {
        echo "Błąd podczas aktualizacji podstrony.";
    }

    $conn->close();

    echo "<form method='post' action='edytuj_podstrone_skrypt.php'>";  // Ustaw odpowiednią akcję
    echo "<input type='hidden' name='id' value='$id'>";
    echo "<p>Tytuł: <input type='text' name='page_title' value='$page_title'></p>";
    echo "<p>Treść: <textarea name='page_content'>$page_content</textarea></p>";
    echo "<p>Aktywna: <input type='checkbox' name='aktywna' ".($aktywna ? "checked" : "")."></p>";
    echo "<p><input type='submit' value='Zapisz zmiany'></p>";
    echo "</form>";

    mysqli_close($conn);
}

function DodajNowaPodstrone() {
    $conn = db_connect(); // Funkcja połączenia z bazą danych
    $query = "INSERT INTO page_list (page_title, page_content) VALUES (?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $tytul, $tresc);
        $stmt->close();
        echo "Podstrona została dodana.";
    } else {
        echo "Błąd podczas dodawania podstrony.";
    }

    $conn->close();

    echo '
    <form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
        <label for="tytul">Tytuł:</label><br>
        <input type="text" id="tytul" name="tytul"><br>
        <label for="tresc">Treść:</label><br>
        <textarea id="tresc" name="tresc"></textarea><br>
        <input type="submit" value="Dodaj podstronę">
    </form>
    ';
}
function UsunPodstrone($id) {
    $conn = db_connect(); // Funkcja połączenia z bazą danych
    $query = "DELETE FROM page_list WHERE id = ? LIMIT 1";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "Podstrona została usunięta.";
    } else {
        echo "Błąd podczas usuwania podstrony.";
    }

    $conn->close();
}

session_start();

if (isset($_POST['login']) && isset($_POST['pass'])) {
    
    if ($_POST['login'] == $login && $_POST['pass'] == $pass) {
        $_SESSION['loggedin'] = true;
        echo 'Zalogowano pomyślnie';
        // Tutaj kod do wywołania dalszych metod administracyjnych
    } else {
        echo 'Błędny login lub hasło';
        FormularzLogowania();
    }
} else {
    FormularzLogowania();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="Content-Language" content="pl"/>
    <meta name="Author" content="Patryk Bachanek"/>
    <title>Admin panel</title>
</head>
<body>
<?php

if ($_SESSION['zalogowany'] === true) {
    ListaPodstron();
    echo '<form method="post"><input type="submit" name="Dodaj" value="Dodaj"></form>';
    if (isset($_POST['Dodaj'])) {
        DodajNowaPodstrone();
    }
    if (isset($_POST['update'])) {
        $id = $_POST['id_to_update'];
        EdytujPodstrone($id);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
        $id = $_POST['id_to_update'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $status = isset($_POST["status"]) ? 1 : 0;
        $conn = db_connect();
        $stmt = $conn->prepare("UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ? LIMIT 1");

        $stmt->bind_param('ssii', $title, $content, $status, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo 'Zmiany zostały wprowadzone';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo 'Brak zmian do zapisania';
        }
        $conn->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DodajNowaPodstrone'])) {
        $nowyTytul = $_POST["nowyTytul"];
        $nowaZawartosc = $_POST["nowaZawartosc"];

        $conn = db_connect();
        $stmt = $conn->prepare("INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)");

        $statusNowejPodstrony = 1; // Domyślnie ustaw na aktywną, możesz dostosować według potrzeb

        $stmt->bind_param('ssi', $nowyTytul, $nowaZawartosc, $statusNowejPodstrony);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo 'Nowa podstrona została dodana.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo 'Błąd podczas dodawania nowej podstrony.';
        }
        $conn->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $idToDelete = $_POST['id_to_update'];
        UsunPodstrone($idToDelete);
    }
}

?>
</body>
</html>