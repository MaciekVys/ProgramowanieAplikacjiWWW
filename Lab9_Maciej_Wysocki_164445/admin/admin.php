<?php
include_once '../cfg.php';

session_start();

if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass) {
        $_SESSION['zalogowany'] = true;
    } else {
        echo 'Błędny login lub hasło';
    }
}

// Obsługa formularza edycji
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    EdytujPodstrone($_POST['id']);
}

// Obsługa formularza dodawania
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj'])) {
    DodajNowaPodstrone();
}

// Obsługa usuwania
if (isset($_GET['delete_id'])) {
    UsunPodstrone($_GET['delete_id']);
}

// Wyświetlanie Panelu Admina lub Formularza Logowania
if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
    PokazPanelAdmina();
} else {
    echo FormularzLogowania();
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $id = $_POST['id'];
    $tytul = $_POST['page_title'];
    $tresc = $_POST['page_content'];
    $aktywna = isset($_POST['aktywna']) ? 1 : 0;

    // Aktualizuj dane w bazie
    $conn = db_connect();
    $query = "UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssii", $tytul, $tresc, $aktywna, $id);
        $stmt->execute();
        $stmt->close();

        // Przekieruj z powrotem do panelu admina lub wyświetl komunikat
        header("Location: admin.php"); // Przykładowe przekierowanie
        exit;
    } else {
        echo "Błąd podczas aktualizacji podstrony.";
    }

    $conn->close();
}

function EdytujPodstrone($id) {
    $conn = db_connect();
    $query = "SELECT page_title, page_content, status FROM page_list WHERE id = ? LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($page_title, $page_content, $aktywna);
        $stmt->fetch();

        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<p>Tytuł: <input type='text' name='page_title' value='$page_title'></p>";
        echo "<p>Treść: <textarea name='page_content'>$page_content</textarea></p>";
        echo "<p>Aktywna: <input type='checkbox' name='aktywna' ".($aktywna ? "checked" : "")."></p>";
        echo "<p><input type='submit' value='Zapisz zmiany'></p>";
        echo "</form>";

        $stmt->close();
    } else {
        echo "Błąd podczas aktualizacji podstrony.";
    }

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dodaj'])) {
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];

    if (empty($tytul) || empty($tresc)) {
        echo "Tytuł i treść są wymagane.";
    } else {
        $conn = db_connect();
        $query = "INSERT INTO page_list (page_title, page_content) VALUES (?, ?)";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $tytul, $tresc);
            if ($stmt->execute()) {
                echo "Podstrona została dodana.";
                header("Location: admin.php"); // Opcjonalne przekierowanie
                exit;
            } else {
                echo "Błąd przy dodawaniu rekordu: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
        }
        $conn->close();
    }
}

function DodajNowaPodstrone() {
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];
    $alias = $_POST['alias'];

    if (empty($tytul) || empty($alias) || empty($tresc)) {
        echo "Tytuł, alias i treść są wymagane.";
        return;
    }

    $conn = db_connect();
    $query = "INSERT INTO page_list (page_title, alias, page_content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sss", $tytul, $alias, $tresc);
        if ($stmt->execute()) {
            echo "Podstrona została dodana.";
            header("Location: admin.php");
            exit;
        } else {
            echo "Błąd przy dodawaniu rekordu: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Błąd przy przygotowaniu zapytania: " . $conn->error;
    }
    $conn->close();
}

function UsunPodstrone($id) {
    $conn = db_connect();
    $query = "DELETE FROM page_list WHERE id = ? LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "Podstrona została usunięta.";
        $stmt->close();
    } else {
        echo "Błąd podczas usuwania podstrony.";
    }

    $conn->close();
}

function PokazPanelAdmina() {
    echo "<h1>Panel Administratora</h1>";
    ListaPodstron();
    
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        EdytujPodstrone($edit_id);
    }

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        UsunPodstrone($delete_id);
    }

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
