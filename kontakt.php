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

    $sql = "SELECT * FROM fryzjer WHERE ZATRUDNIONY = 1";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać fryzjerów";
    }

    $fryzjerzy = [];

    if ($result->num_rows > 0) {
        $fryzjerzy = array();
        while ($row = $result->fetch_assoc()) {

            $imie = $row["IMIE"];
            $nazwisko = $row["NAZWISKO"];
            $telefon = $row["NUMER_KONTAKTOWY"];

            $fryzjerzy[] = array("imie" => $imie, "nazwisko" => $nazwisko, "telefon" => $telefon);

        }
    }

    $connection->close();

    echo '<script>';
    echo 'var fryzjerzy = ' . json_encode($fryzjerzy) . ';';
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
            <a href="cennik.php">Cennik</a>
            <a href="kontakt.php">Kontakt</a>
            <a href="panel.php">Panel Administratora</a>
        </div>

    </div>
    <div id="calendar">
        <script>
            // Funkcja generująca tabelę
            function generujTabeleKontektow() {
                var tabela = '<table>';
                tabela += '<tr><th>Imię</th><th>Nazwisko</th><th>Telefon</th></tr>';

                for (var i = 0; i < fryzjerzy.length; i++) {
                    tabela += '<tr>';
                    tabela += '<td>' + fryzjerzy[i].imie + '</td>';
                    tabela += '<td>' + fryzjerzy[i].nazwisko + '</td>';
                    tabela += '<td>' + fryzjerzy[i].telefon + '</td>';
                    tabela += '</tr>';
                }

                tabela += '</table>';
                document.getElementById("calendar").innerHTML += tabela;
            }

            // Wywołanie funkcji do generowania tabeli
            generujTabeleKontektow();
        </script>

    </div>

    <div id="footer">Strona rezerwacji do fryzjera na Projekt z internetowych baz danych. Autorzy: Szulc, Bagiński,
        Kipa.</div>


</body>

</html>