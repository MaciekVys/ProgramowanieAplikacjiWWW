<?php
// Włączenie plików konfiguracyjnych i funkcji pomocniczych
include('cfg.php');
include('showpage.php');

// Nawiązanie połączenia z bazą danych
$conn = db_connect();

// Konfiguracja raportowania błędów
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Wybór strony na podstawie parametru 'idp' przekazanego metodą GET
if($_GET['idp'] == '') 
{
    $strona = Show(3, $conn);
}
if ($_GET['idp'] == 'kontakt') 
{
    $strona = Show(5, $conn);
}
if ($_GET['idp'] == 'Filmy') 
{
    $strona = Show(4, $conn);
}
if ($_GET['idp'] == 'Najelpsi z najlepszych') 
{
    $strona = Show(7, $conn);
}
if ($_GET['idp'] == 'Ligi') 
{
    $strona = Show(6, $conn);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football</title>
    <meta name="Author" content="Maciej Wysocki" />
    <link rel="stylesheet" href="css/Style.css" type="text/css"/>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
</head>
<body onload="startclock()">
    <header>
        <div class="header">
            <h2>Football.pl</h2>
            <div id="time">
                <div id="data"></div>
                <div id="zegarek"></div> 
            </div>
        </div>
    </header>
    <div class="menu">
        <ol> 
            <li><a href="index.php?idp=">Strona Główna</a></li>                         
            <li><a href="index.php?idp=kontakt">Kontakt</a></li>
            <li><a href="index.php?idp=Najelpsi z najlepszych">Najlepsi z najlepszych</a></li>
            <li><a href="index.php?idp=Filmy">Filmy</a></li>
            <li><a href="index.php?idp=Ligi">Ligi</a></li>               
        </ol>
    </div>
    <?php
        // Wyświetlanie zawartości strony
        echo $strona;
    ?>
    <?php
        $nr_indeksu = '164445';
        $nrGrupy = '1';
        echo 'Autor: Maciej Wysocki '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';
    ?>
</body>
</html>
