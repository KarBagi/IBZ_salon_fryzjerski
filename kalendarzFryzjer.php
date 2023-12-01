<?php
session_start();
?>

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
    <script src="scripts/scriptWorker.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid&family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="icon" href="ikona.jpg" type="image/jpg">

    <?php
    require_once("wizyty.php");
    require_once("fryzjerzyIuslugi.php");
    require_once("specjalizacje.php");
    ?>
</head>

<body>

    <div id="errorModalBackground" class="modal-background">
        <div class="modal" id="errorModal">
            <?php
            if ((isset($_SESSION['error']))) {
                echo $_SESSION['error'];
            }
            ?>
            <div onclick="closeErrorModal()" id="cancel" class="buttonModal">OK</div>
        </div>
    </div>

    <div class="modal-background" id="modalBackground">


        <div class="modal">
            <h2>Rezerwacja usługi</h2>

            <table>

                <tr>
                    <td>
                        <div class="modalLi">Data: </div>
                    </td>
                    <td><input type="date" name="termin" id="termin" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Godzina: </div>
                    </td>
                    <td><input type="time" name="czas" id="czas" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi"><span id="fryzjer_text">Fryzjer:</span></div>
                    </td>
                    <td>
                        <input type="text" id="opcja_fryzjer" readonly>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi"><span id="usluga_text">Usługa:</span></div>
                    </td>
                    <td>
                        <input type="text" id="opcja" readonly>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Imię: </div>
                    </td>
                    <td><input type="text" name="imie" id="imie" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Nazwisko: </div>
                    </td>
                    <td><input type="text" name="nazwisko" id="nazwisko" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Nr telefonu: </div>
                    </td>
                    <td><input type="text" name="phone" id="phone" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Email: </div>
                    </td>
                    <td><input type="text" name="email" id="email" readonly></td>
                </tr>
                <tr>
                    <td>
                        <div class="modalLi">Zatwierdzono: </div>
                    </td>
                    <td><input type="text" name="zatwierdzono" id="zatwierdzono" readonly></td>
                </tr>
            </table>
            <form action="" id="modalForm" method="get">
                <input type="hidden" name="id_wizyty" id="id_wizyty">
                <div style="display: flex; justify-content: space-between;">
                    <div onclick="closeModal()" id="cancel" class="buttonModal">Anuluj</div>
                    <button type="submit" formaction="usun.php" class="buttonModal">Usuń</button>
                    <button type="submit" formaction="zatwierdz.php" class="buttonModal">Zatwierdź</button>

                </div>
            </form>
        </div>
    </div>


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

        <div id="select-week">
            <span id="week">Wybrany tydzień</span><br>
            <div id="slider">
                <div id="filter">Fryzjer:
                    <select name="calendar_option" id="calendar_filter" onchange="changeWorker()">

                    </select>
                </div>

                <button onclick="weekBack()" id="left"
                    class="material-symbols-outlined change-week">arrow_back_ios_new</button>

                <div id="date"></div>

                <button onclick="weekForward()" id="right"
                    class="material-symbols-outlined change-week">arrow_forward_ios</button>

                <div style="float: left; width: 30%;"></div>
                <div style="clear: both;"></div>
            </div>
            <span id="reset-week" onclick="getCurrentDate()">aktualny tydzień</span>
        </div>

        <div id="cal-nav">
            <span class="cal-nav-text hour-text">Godzina</span>
            <span class="cal-nav-text">Poniedziałek</span>
            <span class="cal-nav-text">Wtorek</span>
            <span class="cal-nav-text">Środa</span>
            <span class="cal-nav-text">Czwartek</span>
            <span class="cal-nav-text">Piątek</span>
            <div style="clear: both;"></div>
        </div>
        <div id="columns">

            <div class="column hours">
                <div class="hour" style="margin-top: 20px;"><span>08:00</span></div>
                <div class="hour"><span>09:00</span></div>
                <div class="hour"><span>10:00</span></div>
                <div class="hour"><span>11:00</span></div>
                <div class="hour"><span>12:00</span></div>
                <div class="hour"><span>13:00</span></div>
                <div class="hour"><span>14:00</span></div>
                <div class="hour"><span>15:00</span></div>
                <div class="hour"><span>16:00</span></div>
                <div class="hour"><span>17:00</span></div>
                <div class="hour"><span>18:00</span></div>
            </div>

            <div class="column pon">
                <div class="day" id="monday-date"></div>
                <div class="hour" day="0" hour="8" onclick="openModal(0,8)">Wolne</div>
                <div class="hour" day="0" hour="9" onclick="openModal(0,9)">Wolne</div>
                <div class="hour" day="0" hour="8" onclick="openModal(0,10)">Wolne</div>
                <div class="hour" day="0" hour="11" onclick="openModal(0,11)">Wolne</div>
                <div class="hour" day="0" hour="12" onclick="openModal(0,12)">Wolne</div>
                <div class="hour" day="0" hour="13" onclick="openModal(0,13)">Wolne</div>
                <div class="hour" day="0" hour="14" onclick="openModal(0,14)">Wolne</div>
                <div class="hour" day="0" hour="15" onclick="openModal(0,15)">Wolne</div>
                <div class="hour" day="0" hour="16" onclick="openModal(0,16)">Wolne</div>
                <div class="hour" day="0" hour="17" onclick="openModal(0,17)">Wolne</div>
            </div>

            <div class="column wt">
                <div class="day" id="tuesday-date"></div>
                <div class="hour" day="1" hour="8" onclick="openModal(1,8)">Wolne</div>
                <div class="hour" day="1" hour="9" onclick="openModal(1,9)">Wolne</div>
                <div class="hour" day="1" hour="10" onclick="openModal(1,10)">Wolne</div>
                <div class="hour" day="1" hour="11" onclick="openModal(1,11)">Wolne</div>
                <div class="hour" day="1" hour="12" onclick="openModal(1,12)">Wolne</div>
                <div class="hour" day="1" hour="13" onclick="openModal(1,13)">Wolne</div>
                <div class="hour" day="1" hour="14" onclick="openModal(1,14)">Wolne</div>
                <div class="hour" day="1" hour="15" onclick="openModal(1,15)">Wolne</div>
                <div class="hour" day="1" hour="16" onclick="openModal(1,16)">Wolne</div>
                <div class="hour" day="1" hour="17" onclick="openModal(1,17)">Wolne</div>
            </div>

            <div class="column sr">
                <div class="day" id="wednesday-date"></div>
                <div class="hour" day="2" hour="8" onclick="openModal(2,8)">Wolne</div>
                <div class="hour" day="2" hour="9" onclick="openModal(2,9)">Wolne</div>
                <div class="hour" day="2" hour="10" onclick="openModal(2,10)">Wolne</div>
                <div class="hour" day="2" hour="11" onclick="openModal(2,11)">Wolne</div>
                <div class="hour" day="2" hour="12" onclick="openModal(2,12)">Wolne</div>
                <div class="hour" day="2" hour="13" onclick="openModal(2,13)">Wolne</div>
                <div class="hour" day="2" hour="14" onclick="openModal(2,14)">Wolne</div>
                <div class="hour" day="2" hour="15" onclick="openModal(2,15)">Wolne</div>
                <div class="hour" day="2" hour="16" onclick="openModal(2,16)">Wolne</div>
                <div class="hour" day="2" hour="17" onclick="openModal(2,17)">Wolne</div>
            </div>

            <div class="column czw">
                <div class="day" id="thursday-date"></div>
                <div class="hour" day="3" hour="8" onclick="openModal(3,8)">Wolne</div>
                <div class="hour" day="3" hour="9" onclick="openModal(3,9)">Wolne</div>
                <div class="hour" day="3" hour="10" onclick="openModal(3,10)">Wolne</div>
                <div class="hour" day="3" hour="11" onclick="openModal(3,11)">Wolne</div>
                <div class="hour" day="3" hour="12" onclick="openModal(3,12)">Wolne</div>
                <div class="hour" day="3" hour="13" onclick="openModal(3,13)">Wolne</div>
                <div class="hour" day="3" hour="14" onclick="openModal(3,14)">Wolne</div>
                <div class="hour" day="3" hour="15" onclick="openModal(3,15)">Wolne</div>
                <div class="hour" day="3" hour="16" onclick="openModal(3,16)">Wolne</div>
                <div class="hour" day="3" hour="17" onclick="openModal(3,17)">Wolne</div>
            </div>

            <div class="column pt">
                <div class="day" id="friday-date"></div>
                <div class="hour" day="4" hour="8" onclick="openModal(4,8)">Wolne</div>
                <div class="hour" day="4" hour="9" onclick="openModal(4,9)">Wolne</div>
                <div class="hour" day="4" hour="10" onclick="openModal(4,10)">Wolne</div>
                <div class="hour" day="4" hour="11" onclick="openModal(4,11)">Wolne</div>
                <div class="hour" day="4" hour="12" onclick="openModal(4,12)">Wolne</div>
                <div class="hour" day="4" hour="13" onclick="openModal(4,13)">Wolne</div>
                <div class="hour" day="4" hour="14" onclick="openModal(4,14)">Wolne</div>
                <div class="hour" day="4" hour="15" onclick="openModal(4,15)">Wolne</div>
                <div class="hour" day="4" hour="16" onclick="openModal(4,16)">Wolne</div>
                <div class="hour" day="4" hour="17" onclick="openModal(4,17)">Wolne</div>
            </div>
            <div style="clear: both;"></div>

        </div>




    </div>

    <div id="footer">
    Strona rezerwacji do fryzjera na Projekt z internetowych baz danych. Autorzy: Szulc, Bagiński, Kipa.
    </div>
    
    <?php

    if ((isset($_SESSION['error']))) {

        echo "<script>";
        echo "openErrorModal();";
        echo "</script>";
        unset($_SESSION['error']);
    }
    ?>
</body>

</html>