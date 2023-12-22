<?php
    $nr_indeksu = '164445';
    $nrGrupy = 'ISI1';
    echo ' Maciej Wysocki '.$nr_indeksu.' grupa '.$nrGrupy.' <br/><br />';
    echo ' Zastosowanie metody include() <br/> ';

    include 'test.php';

    echo 'Maciej Wysocki '.$wiek.' lata i '.$wzrost.' cm wzrosu. <br/>';

    echo 'METODA REQUIRE_ONCE <br/>';

    $temp1 = require_once('once.php');
    echo $temp1. "<br/>";
    $temp2 = require_once('once.php');
    echo $temp2. "<br/>";

    echo 'METODA IF <br/>';

    $a = 10;
    $b = 10;
    if($a > $b)
        echo 'A jest wieksze niż b <br/>';
    elseif($a == $b)
        echo 'A jest rowne B <br/>';
    else
        echo 'A jest mnmiejsze niz B <br/>';

    echo 'METODA SWITCH <br/>';
    
    $i = '2';    
    switch ($i){
    case 0:
        echo "i jest rowne 1 <br/>";
        break;
    case 1:
        echo "i jest rowne 2 <br/>";
        break;
    case 2:
        echo "i jest rowne 2 <br/>";
        break;
    }

    echo 'METODA FOR <br/>';

    for ($i = 1; ; $i++) {
        if ($i > 10) {
            break;
        }
        echo $i.'<br/>';
    }

    echo 'METODA WHILE <br/>';

    $i = 1;
    while ($i <= 10) {
    echo $i++.'<br/>';
    } 
    echo 'METODA GET <br/>';

    $_GET['name'] = 'Maciej';
    echo 'Hello ' . htmlspecialchars($_GET['name']) . '! <br/>';
    echo 'METODA POST <br/>';
    $_POST['name'] = 'Maciej';
    echo 'Hello ' . htmlspecialchars($_GET['name']) . '! <br/>';

    session_start();
    $value = '100';
    $_SESSION['newsession'] = $value;

?>  
