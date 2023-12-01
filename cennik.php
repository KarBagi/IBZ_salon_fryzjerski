<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>System rezerwacji do fryzjera</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel=“shortcut” href="icons/favicon.ico" type="image/x-ico">
    <script src="scripts/script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid&family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="icon" href="ikona.jpg" type="image/jpg">

    <?php
    require_once "dbconnect.php";

    $connection = @new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        echo "Błąd" . $connection->connect_error;
    }

    $sql = "SELECT NAZWA, CENA, CZAS_TRWANIA FROM usluga WHERE AKTYWNA = 1";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać cennika";
    }

    $cennik = [];

    if ($result->num_rows > 0) {
        $cennik = array();
        while ($row = $result->fetch_assoc()) {

            $nazwa = $row["NAZWA"];
            $cena = $row["CENA"];
            $czas = $row["CZAS_TRWANIA"];

            $cennik[] = array("nazwa" => $nazwa, "cena" => $cena, "czas_trwania" => $czas);

        }
    }

    $connection->close();

    echo '<script>';
    echo 'var cennik = ' . json_encode($cennik) . ';';
    echo '</script>';
    ?>
    <style>
        #calendar
        {
        text-align: center;
        }

        table {
            margin: auto;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            background-color: white;
            padding: 8px 20px 8px 20px;
        }

        th {
            background-color: #bbdefb;
        }
    </style>
</head>

<body>

    <div id="nav">
        <p>Zakład fryzjerski</p>


        <div class="navbarMenu">
            <a href="index.php">Strona główna</a>
            <a href="#news">Aktualności</a>
            <a href="cennik.php">Cennik</a>
            <a href="#kontakt">Kontakt</a>
            <a href="panel.html">Panel Administratora</a>
        </div>

    </div>
    <div id="calendar">
        <script>
            // Funkcja generująca tabelę
            function generujTabeleCennika() {
                var tabela = '<table>';
                tabela += '<tr><th>Nazwa</th><th>Cena</th><th>Czas Trwania</th></tr>';

                for (var i = 0; i < cennik.length; i++) {
                    tabela += '<tr>';
                    tabela += '<td>' + cennik[i].nazwa + '</td>';
                    tabela += '<td>' + cennik[i].cena + ' zł</td>';
                    tabela += '<td>' + cennik[i].czas_trwania + ' min</td>';
                    tabela += '</tr>';
                }

                tabela += '</table>';
                document.getElementById("calendar").innerHTML += tabela;
            }

            // Wywołanie funkcji do generowania tabeli
            generujTabeleCennika();
        </script>

    </div>

    <div id="footer">Strona rezerwacji do fryzjera na Projekt z internetowych baz danych. Autorzy: Szulc, Bagiński,
        Kipa.</div>


</body>

</html>