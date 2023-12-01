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

    $sql = "SELECT * FROM usluga";
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

            $id = $row["USLUGA_ID"];
            $nazwa = $row["NAZWA"];
            $cena = $row["CENA"];
            $czas = $row["CZAS_TRWANIA"];
            $aktywna = $row["AKTYWNA"];

            $cennik[] = array("id" => $id, "nazwa" => $nazwa, "cena" => $cena, "czas_trwania" => $czas, "aktywna" => $aktywna);

        }
    }

    echo '<script>';
    echo 'var cennik = ' . json_encode($cennik) . ';';
    echo '</script>';

    $sql = "SELECT * FROM fryzjer";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać fryzjerow";
    }

    $fryzjerzy = [];

    if ($result->num_rows > 0) {
        $fryzjerzy = array();
        while ($row = $result->fetch_assoc()) {

            $id = $row["FRYZJER_ID"];
            $imie = $row["IMIE"];
            $nazwisko = $row["NAZWISKO"];
            $zatrudniony = $row["ZATRUDNIONY"];

            $fryzjerzy[] = array("id" => $id, "imie" => $imie, "nazwisko" => $nazwisko, "zatrudniony" => $zatrudniony);

        }
    }

    $connection->close();

    echo '<script>';
    echo 'var fryzjerzy = ' . json_encode($fryzjerzy) . ';';
    echo '</script>';
    ?>
    <style>
        .table {
            margin: auto;
            display: table;
        }

        .tr {
            display: table-row;
            text-align: left;
            background-color: white;

        }

        .td {
            padding: 8px 20px 8px 20px;
            display: table-cell;
            border: 1px solid #bababa;
        }

        .th {
            background-color: #bbdefb;
        }


        #panel {
            padding: 20px;
            width: 90%;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.315);
            border-radius: 10px;
        }

        .accordion {
            font-family: 'Rubik', sans-serif;
            font-size: 20px;
            background-color: #3b86f8;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            border: none;
            outline: none;
            transition: 0.4s;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active,
        .accordion:hover {
            background-color: #73abfd;
        }

        /* Style the accordion panel. Note: hidden by default */
        .form-container {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .admin-form {
            margin: 30px;
        }

        input {
            width: 80%;
        }

        input[type="text"],
        input[type="value"] {
            border: 1px solid blue;
            font-size: 25px;
        }

        input[type="submit"] {
            border: 4px #2196f3 solid;
            background-color: #2196f3;
            color: #fff;
            font-size: 20px;
            border-radius: 5px;
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

    <div id="panel">

        <button class="accordion">Dodaj fryzjera</button>
        <div class="form-container">
            <form class="admin-form" action="dodajFryzjera.php">
                <input type="text" name="imie" placeholder="imię" required>
                <input type="text" name="nazwisko" placeholder="nazwisko" required>
                <input type="text" name="telefon" placeholder="telefon" required>
                <input type="submit" value="Dodaj fryzjera">
            </form>
        </div>

        <button class="accordion">Dodaj usługę do fryzjera</button>
        <div class="form-container">
            <form class="admin-form" action="dodajUslugeDoFryzjera.php">
                <select id="fryzjerzySelect" name="fryzjer"></select>
                <select id="uslugi" name="usluga"></select>
                <input type="submit" value="Dodaj">
            </form>
            <script>
                    function pokazFryzjerow() {
                        var select = document.getElementById("fryzjerzySelect");
                        for (var i = 0; i < fryzjerzy.length; i++) {
                            if(fryzjerzy[i].zatrudniony==1)
                            {var option = document.createElement("option");
                            option.value = fryzjerzy[i].id;
                            option.text = fryzjerzy[i].imie+" "+fryzjerzy[i].nazwisko;
                            select.add(option);
                            }
                        }
                    }

                    function pokazUslugi() {
                        var select = document.getElementById("uslugi");
                        for (var i = 0; i < cennik.length; i++) {
                            if(cennik[i].aktywna==1)
                            {var option = document.createElement("option");
                            option.value = cennik[i].id;
                            option.text = cennik[i].nazwa;
                            select.add(option);
                            }
                        }
                    }

                    pokazFryzjerow();
                    pokazUslugi();
                </script>
        </div>

        <button class="accordion">Usuń fryzjera</button>
        <div class="form-container" id="fryzjerzy">
            <script>
                function generujTabeleFryzjerow() {
                    var tabela = '<div class="table admin-form">';
                    tabela += '<div class="td th">Imie</div><div class="td th">Nazwisko</div>'

                    for (var i = 0; i < fryzjerzy.length; i++) {
                        if (fryzjerzy[i].zatrudniony == 1) {
                            tabela += '<form class="tr" action="usunFryzjera.php" method="get">';
                            tabela += '<input type="hidden" id="id_fryzjera" name="id" value="' + fryzjerzy[i].id + '">';
                            tabela += '<div class="td">' + fryzjerzy[i].imie + '</div>';
                            tabela += '<div class="td">' + fryzjerzy[i].nazwisko + '</div>';
                            tabela += '<div class="td"><input type="submit" value="Usuń"></div>';
                            tabela += '</form>';
                        }

                    }

                    tabela += '</div>';
                    document.getElementById("fryzjerzy").innerHTML += tabela;
                }

                generujTabeleFryzjerow();
            </script>
        </div>

        <button class="accordion">Zmiana cennika</button>
        <div class="form-container" id="cennik">
            <script>
                function generujTabeleCennika() {
                    var tabela = '<div class="table admin-form">';
                    tabela += '<div class="td th">Nazwa</div><div class="td th">Cena</div><div class="td th">Czas trwania</div><div class="td th">Aktywna</div>'

                    for (var i = 0; i < cennik.length; i++) {
                        tabela += '<form class="tr" action="zmienCennik.php" method="get">';
                        tabela += '<input type="hidden" id="id_uslugi" name="id" value="' + cennik[i].id + '">';
                        tabela += '<div class="td"><input type="text" id="nazwa" name="nazwa" value="' + cennik[i].nazwa + '" required></div>';
                        tabela += '<div class="td"><input type="value" id="cena" name="cena" value="' + cennik[i].cena + '" required> zł</div>';
                        tabela += '<div class="td"><input type="value" id="czas" name="czas" value="' + cennik[i].czas_trwania + '" required> min</div>';
                        tabela += '<div class="td"><input type="value" id="aktywna" name="aktywna" value="' + cennik[i].aktywna + '" required></div>';
                        tabela += '<div class="td"><input type="submit" value="Zapisz"></div>';
                        tabela += '</form>';
                    }

                    tabela += '</div>';
                    document.getElementById("cennik").innerHTML += tabela;
                }

                generujTabeleCennika();
            </script>
        </div>

        <button class="accordion">Dodaj usługę</button>
        <div class="form-container">
            <form class="admin-form" action="dodajUsluge.php">

                <input type="text" name="nazwa" placeholder="nazwa" required>
                <input type="value" name="cena" placeholder="cena" required>
                <input type="value" name="czas" placeholder="czas trwania" required>
                <input type="submit" value="Dodaj usługę">
            </form>
        </div>

        <button class="accordion">Usuń usługę</button>
        <div class="form-container" id="uslugi">
            <script>
                function generujTabeleUslug() {
                    var tabela = '<div class="table admin-form">';
                    tabela += '<div class="td th">Nazwa</div>'

                    for (var i = 0; i < cennik.length; i++) {
                        if (cennik[i].aktywna == 1) {
                            tabela += '<form class="tr" action="usunUsluge.php" method="get">';
                            tabela += '<input type="hidden" id="id_uslugi" name="id" value="' + cennik[i].id + '">';
                            tabela += '<div class="td">' + cennik[i].nazwa + '</div>';
                            tabela += '<div class="td"><input type="submit" value="Usuń"></div>';
                            tabela += '</form>';
                        }

                    }

                    tabela += '</div>';
                    document.getElementById("uslugi").innerHTML += tabela;
                }

                generujTabeleUslug();
            </script>
        </div>

    </div>



    <div id="footer">
        Strona rezerwacji do fryzjera na Projekt z internetowych baz danych. Autorzy: Szulc, Bagiński, Kipa.
    </div>

    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    </script>
</body>

</html>