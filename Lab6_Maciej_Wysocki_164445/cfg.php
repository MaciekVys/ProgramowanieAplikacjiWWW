<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    $link = mysql_content($dbhost, $dbuser, $dbpass);
    if (!$link) echo '<b>przerwane połączenie </b>';
    if (!mysql_content($baza)) echo 'nie wybrano bazy';
?>