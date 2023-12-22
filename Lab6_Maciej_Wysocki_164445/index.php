<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football</title>
    <meta name="Author" content="Maciej Wysocki" />
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="menu">
        <ol>
            <li><a href="index.php?idp=glowna">Menu</a></li>
            <li><a href="index.php?idp=kontakt">Kontakt</a> </li>
            <li><a href="index.php?idp=reprezentacja">Reprezentacja</a></li>
            <li><a href="index.php?idp=filmy">Filmy</a></li>               
            <li><a>Ligi</a>
                <ul>
                    <li><a href="">PKO Ekstraklasa</a></li>
                    <li><a href="">Primera Division</a></li>
                    <li><a href="">Bundesluga</a></li>
                    <li><a href="">Premier League</a></li>
                    <li><a href="">Serie A</a></li>
                    <li><a href="">Ligue 1</a></li>
                    <li><a href="">La Liga</a></li>
                </ul>
            </li>
        </ol>
    </div>
    <?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    if ($_GET['idp'] == 'glowna') $strona = 'html/glowna.html';
    if ($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
    if ($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';
    if ($_GET['idp'] == 'reprezentacja') $strona = 'html/reprezentacja.html';
    if ($_GET['idp'] == 'filmy' && file_exists('html/filmy.html')) $strona = 'html/filmy.html';
    ?>
    <?php include($strona); ?>
    <?php include('cfg.php'); ?>
</body>
</html>
