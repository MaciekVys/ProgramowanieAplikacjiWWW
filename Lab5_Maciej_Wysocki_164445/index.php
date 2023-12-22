<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Maciej Wysocki" />
    <link rel="stylesheet" href="css/Strona główna.css" type="text/css"/>
    <script src="/myWebsite/js/kolorujtlo.js" type="text/javascript"></script>
    <script src="/myWebsite/js/timedate.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Football</title>
</head>
<body onload="startclock()">
    
    <div id="container">
        <header>
            <div id="header">
                <h2>Football.pl</h2>
                <div id="time">
                    <div id="data" ></div>
                    <div id="zegarek"></div> 
                </div>
            </div>
        </header>
        <div class="menu">
            <ol>
                <li><a  class="active">Menu</a></li>
                <li><a  href="kontakt.html">Kontakt</a></li>
                <li><a  href="historia.html">Historia Piłki Nożnej</a></li>
                <li><a  href="Reprezentacja.html">Reprezentacja Polski</a></li>
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
        <div class="base-main">
            <div class="left-side" >
                <img class="left-bar" src="images/left.jpg" width="380" height="450">
            </div>
            <div class="main">
                <section>
                    <article>
                        <h1>Czym jest piłka nożna?</h1>
                        <p><img class="left" width="260" height="300" src="images/pilka.jpg">Piłka nożna (futbol, ang. football, association football, soccer) – gra zespołowa,
                            w której dwie drużyny starają się zdobyć w określonym czasie jak najwięcej
                            punktów poprzez wbicie piłki do bramki przeciwnika,
                            najpopularniejsza dyscyplina sportowa z około 4 miliardami fanów na całym świecie.
                            Od 1900 dyscyplina olimpijska. Na całym świecie w 2009 w rozgrywkach udział brało 265 milionów zawodników i zawodniczek oraz 5 milionów sędziów należących do 206 lokalnych związków zrzeszonych w FIFA. 
                            W meczach piłkarskich uczestniczą dwie rywalizujące ze sobą drużyny. Celem gry jest umieszczenie piłki w bramce przeciwnika. 
                            Zwycięża drużyna, która w regulaminowym czasie gry (dwie połowy po 45 minut każda w rozgrywkach seniorów) zdobędzie więcej bramek. Mecze piłkarskie odbywają się na prostokątnym, pokrytym murawą boisku. 
                            Rozmiary boiska to 45 do 90 m szerokości i od 90 do 120 m długości, lecz boisko nie może być kwadratowe. 
                             Od 14 marca 2008 każde nowe boisko powinno mieć 105 metrów długości i 68 metrów szerokości. 
                            Decyzję tę przyjął Komitet Wykonawczy FIFA na podstawie przepisów opracowanych przez IFAB instytucję odpowiedzialną za przepisy gry w piłkę nożną.
                            Drużyna piłkarska składa się z 11 zawodników (aby zespół został dopuszczony do meczu musi być ich co najmniej 7) i zazwyczaj 7 rezerwowych (w finałach mistrzostw świata i mistrzostw Europy – 12). 
                            Wśród graczy wyróżniamy bramkarza 
                            i graczy z pola: obrońców, pomocników i napastników.<img class="right" width="300" height="320" src="images/pilkarz.jpg"> Podział graczy z pola na pozycje jest czysto umowny, w aktualnie stosowanych strategiach gry często następuje podczas meczu płynna wymiana między nimi. 
                            Bramkarz jest jedynym zawodnikiem, który może dotykać i łapać piłkę rękami w czasie gry, jednak zgodnie z przepisami może to mieć miejsce jedynie we własnym polu karnym.
                            W przypadku rozmyślnego zagrania piłki ręką przez bramkarza poza własnym polem karnym, jego drużyna zostaje ukarana rzutem wolnym bezpośrednim (zobacz Wykroczenia i kary), tak samo jak w przypadku pozostałych zawodników. 
                            Wbrew powszechnie panującej opinii, ukaranie bramkarza za rozmyślne zagranie piłki ręką w czasie gry poza własnym polem karnym karą indywidualną w postaci żółtej lub czerwonej kartki, może mieć miejsce jedynie w ściśle określonych w przepisach gry sytuacjach. 
                            Bramkarzowi nie wolno zagrywać piłki ręką po podaniu jej do 
                            niego przez współpartnera z wrzutu oraz po podaniu od współpartnera nogą, jednak jeśli intencją zawodnika z drużyny nie było podanie piłki do bramkarza, a wynikło to z przypadku, w takiej sytuacji bramkarz może zagrywać ręką, nieważne, którą częścią ciała zagrał współpartner.
                        </p>
                    </article>
                </section>
            </div>  
            <div class="right-side">
                <img class="right-bar" src="images/right.jpg" width="310" height="450">
            </div> 
        </div>
    </div>
<?
    $nr_indeksu = ‘1234567’;
    $nrGrupy = ‘X’;
    echo ‘Autor: Jan Kowalski ‘.$nr_indeksu.’ grupa ‘.$nrGrupy.’ <br /><br />’;
?>
</body>
