<?php
include 'cfg.php';

// Sprawdź, czy jest żądanie edycji
$edytujId = isset($_GET['edytuj_id']) ? $_GET['edytuj_id'] : null;
$edytowanaKategoria = null;
if ($edytujId) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT nazwa, matka FROM sklep WHERE id = ?");
    $stmt->bind_param("i", $edytujId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edytowanaKategoria = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nazwa']) && !$edytujId) {
        DodajKategorie($_POST['nazwa']);
    } elseif (isset($_POST['edytuj_id'])) {
        EdytujKategorie($_POST['edytuj_id'], $_POST['nowaNazwa'], $_POST['nowaMatka']);
    }
    header('Location: sklep.php');
    exit();
}

if (isset($_GET['usun_id'])) {
    UsunKategorie($_GET['usun_id']);
    header('Location: sklep.php');
    exit();
}

function DodajKategorie($nazwa, $matka = 0) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO sklep (nazwa, matka) VALUES (?, ?)");
    $stmt->bind_param("si", $nazwa, $matka);
    $stmt->execute();
    $stmt->close();
    $conn->close(); 
}

function UsunKategorie($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM sklep WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

function EdytujKategorie($id, $nowaNazwa, $nowaMatka = 0) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE sklep SET nazwa = ?, matka = ? WHERE id = ?");
    $stmt->bind_param("sii", $nowaNazwa, $nowaMatka, $id);
    $stmt->execute();
    $stmt->close();
}

function PokazKategorie($matka = 0, $poziom = 0) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id, nazwa FROM sklep WHERE matka = ?");
    $stmt->bind_param("i", $matka);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        echo str_repeat(" - ", $poziom) . $row["nazwa"] . " <a href='sklep.php?edytuj_id=" . $row["id"] . "'>Edytuj</a> <a href='sklep.php?usun_id=" . $row["id"] . "'>Usuń</a><br>";
        PokazKategorie($row["id"], $poziom + 1);
    }
    $stmt->close();
}

// Sprawdź, czy jest żądanie edycji
$edytujId = isset($_GET['edytuj_id']) ? $_GET['edytuj_id'] : null;
$edytowanaKategoria = null;
if ($edytujId) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT nazwa, matka FROM sklep WHERE id = ?");
    $stmt->bind_param("i", $edytujId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edytowanaKategoria = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Zarządzanie Kategoriami</title>
</head>
<body>
    <h1>Zarządzanie Kategoriami</h1>
    <?php if ($edytujId && $edytowanaKategoria): ?>
        <form action="sklep.php?edytuj_id=<?php echo $edytujId; ?>" method="post">
            <input type="hidden" name="edytuj_id" value="<?php echo $edytujId; ?>">
            Nazwa Kategorii: <input type="text" name="nowaNazwa" value="<?php echo $edytowanaKategoria['nazwa']; ?>">
            Matka Kategorii: <input type="text" name="nowaMatka" value="<?php echo $edytowanaKategoria['matka']; ?>">
            <input type="submit" value="Zaktualizuj Kategorię">
        </form>
    <?php else: ?>
        <form action="sklep.php" method="post">
            Nazwa Kategorii: <input type="text" name="nazwa">
            <input type="submit" value="Dodaj Kategorię">
        </form>
    <?php endif; ?>
    <hr>
    <h2>Lista Kategorii</h2>
    <?php PokazKategorie(); ?>
</body>
</html>