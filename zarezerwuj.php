<?php
session_start();
// error_reporting(0);


if (!isset($_GET["termin"]) || !isset($_GET["czas"]) || !isset($_GET["imie"]) || !isset($_GET["nazwisko"]) || !isset($_GET["fryzjer"]) || !isset($_GET["usluga"])) {
    header("Location: index.php");
    exit;
}

//odczytane dane
$termin = $_GET["termin"];
$czas = $_GET["czas"];
list($godzina, $minuta) = explode(":", $czas);
$imie = $_GET["imie"];
$nazwisko = $_GET["nazwisko"];
$fryzjer = $_GET["fryzjer"];
$fryzjer_id = "";
$usluga = $_GET["usluga"];
$usluga_id = "";
$klient_id = "";
list($rok, $miesiac, $dzien) = explode("-", $termin);
$dzien_tygodnia = $_GET['dzien_tygodnia'];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: index.php');
    exit;
}

$sql = "SELECT WIZYTA_ID, fryzjer.NAZWISKO, usluga.NAZWA from wizyta,fryzjer,usluga where ROK=$rok AND DZIEN=$dzien AND MIESIAC=$miesiac AND GODZINA=$godzina AND MINUTA=$minuta AND fryzjer.NAZWISKO = '$fryzjer' AND fryzjer.FRYZJER_ID=wizyta.FRYZJER_ID AND usluga.USLUGA_ID = wizyta.USLUGA_ID";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać id klienta";
    $_SESSION['error'] = 'Nie udało się pobrać id klienta';
    header('Location: index.php');
    exit;
}

if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Termin już zajęty'.$fryzjer;

    $result->free_result();
    $connection->close();

    header('Location: index.php');
    exit;
}

echo "Termin: $dzien-$miesiac-$rok<br>";
echo "Czas: $czas<br>";
echo "Imię: $imie<br>";
echo "Nazwisko: $nazwisko<br>";
echo "Usługa: $usluga<br>";
echo "Fryzjer: $fryzjer<br>";
$phone = "";
$email = "";

// Sprawdź, czy parametr phone został przesłany
if ($_GET["phone"] != "") {
    $phone = $_GET["phone"];
    echo "Numer telefonu: $phone<br>";
}

// Sprawdź, czy parametr email został przesłany
if ($_GET["email"] != "") {
    $email = $_GET["email"];
    echo "Email: $email<br>";
}

//jeżeli jest sam telefon szukamy po telefonie
if ($phone != "" && $email == "") {
    echo "Szukam po telefonie<br>";
    $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND NUMER_TELEFONU='$phone'";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać id klienta";
        $_SESSION['error'] = 'Nie udało się pobrać id klienta';
        header('Location: index.php');
        exit;
    }

    $ile_klientow = $result->num_rows;
    if ($ile_klientow > 0) {
        $row = $result->fetch_assoc();
        $klient_id = $row['KLIENT_ID'];
    }
}

//jeżeli jest sam email szukamy po emailu
if ($email != "" && $_GET["phone"] != "") {
    echo "Szukam po emailu<br>";
    $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND EMAIL='$email'";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać id klienta";
        $_SESSION['error'] = 'Nie udało się pobrać id klienta';
        header('Location: index.php');
        exit;
    }

    $ile_klientow = $result->num_rows;
    if ($ile_klientow > 0) {
        $row = $result->fetch_assoc();
        $klient_id = $row['KLIENT_ID'];
    }
}

// jeżeli jest i email i telefon
if ($email != '' && $phone != '') {

    echo "Szukam po telefonie i emailu<br>";
    $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND EMAIL='$email' AND NUMER_TELEFONU = '$phone'";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać id klienta";
        $_SESSION['error'] = 'Nie udało się pobrać id klienta';
        header('Location: index.php');
        exit;
    }

    $ile_klientow = $result->num_rows;
    //jeżeli mamy wynik przy szukaniu dwoma kryteriami
    if ($ile_klientow > 0) {
        $row = $result->fetch_assoc();
        $klient_id = $row['KLIENT_ID'];
    } else {
        //jeżeli nie to szukamy jednym kryterium
        echo "Szukam po samym telefonie<br>";
        $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND NUMER_TELEFONU='$phone'";
        $result = @$connection->query($sql);
        if (!$result) {
            $result->free_result();
            $connection->close();
            echo "Nie udało się pobrać id klienta";
            $_SESSION['error'] = 'Nie udało się pobrać id klienta';
            header('Location: index.php');
            exit;
        }
        $ile_klientow = $result->num_rows;
        if ($ile_klientow > 0) {
            //jeżeli dla jednego kryterium jest wynik uzupełniamy drugie
            $row = $result->fetch_assoc();
            $klient_id = $row['KLIENT_ID'];

            $sql = "UPDATE klient SET email = '$email' WHERE numer_telefonu = '$phone'";
            $result = @$connection->query($sql);
            if (!$result) {
                $result->free_result();
                $connection->close();
                echo "Nie udało się pobrać id klienta";
                $_SESSION['error'] = 'Nie udało się pobrać id klienta';
                header('Location: index.php');
                exit;
            }
        } else {
            //jeżeli dla pierwszego kryterium nie ma wyników to szukamy drugim
            echo "Szukam po samym emailu<br>";
            $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND EMAIL='$email'";
            $result = @$connection->query($sql);
            if (!$result) {
                $result->free_result();
                $connection->close();
                echo "Nie udało się pobrać id klienta";
                $_SESSION['error'] = 'Nie udało się pobrać id klienta';
                header('Location: index.php');
                exit;
            }
            $ile_klientow = $result->num_rows;
            if ($ile_klientow > 0) {
                //jeżeli dla drugiego kryterium jest wynik uzupełniamy pierwsze
                $row = $result->fetch_assoc();
                $klient_id = $row['KLIENT_ID'];

                $sql = "UPDATE klient SET NUMER_TELEFONU = '$phone' WHERE EMAIL = '$email'";
                $result = @$connection->query($sql);
                if (!$result) {
                    $result->free_result();
                    $connection->close();
                    echo "Nie udało się pobrać id klienta";
                    $_SESSION['error'] = 'Nie udało się pobrać id klienta';
                    header('Location: index.php');
                    exit;
                }
            }
        }
    }
}

//jeżeli mimo podania danych nie znaleziono klienta musimy go dodać
if ($klient_id == "") {
    $sql = "INSERT INTO KLIENT (IMIE, NAZWISKO, NUMER_TELEFONU, EMAIL) VALUES ('$imie', '$nazwisko', '$phone', '$email')";
    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się dodać klienta";
        $_SESSION['error'] = 'Nie udało się dodać klienta';
        header('Location: index.php');
        exit;
    }

    if ($email == '' && $phone != '')
        $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND NUMER_TELEFONU = '$phone'";

    if ($email != '' && $phone == '')
        $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND EMAIL='$email'";

    if ($email != '' && $phone != '')
        $sql = "SELECT KLIENT_ID from klient where nazwisko = '$nazwisko' AND imie='$imie' AND EMAIL='$email' AND NUMER_TELEFONU = '$phone'";

    $result = @$connection->query($sql);
    if (!$result) {
        $result->free_result();
        $connection->close();
        echo "Nie udało się pobrać id klienta";
        $_SESSION['error'] = 'Nie udało się pobrać id klienta';
        header('Location: index.php');
        exit;
    }

    $row = $result->fetch_assoc();
    $klient_id = $row['KLIENT_ID'];
}

$sql = "Select FRYZJER_ID from fryzjer where nazwisko = '$fryzjer'";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać id fryzjera";
    $_SESSION['error'] = 'Nie udało się pobrać id fryzjera';
    header('Location: index.php');
    exit;
}

$row = $result->fetch_assoc();
$fryzjer_id = $row['FRYZJER_ID'];
echo "Fryzjer_id: $fryzjer_id<br>";

$sql = "Select USLUGA_ID from usluga where nazwa = '$usluga'";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać id uslugi";
    $_SESSION['error'] = 'Nie udało się pobrać id uslugi';
    header('Location: index.php');
}

$row = $result->fetch_assoc();
$usluga_id = $row['USLUGA_ID'];
$result->free_result();

if ($fryzjer_id != "" && $usluga_id != "") {
    $sql = "INSERT INTO WIZYTA (DZIEN, MIESIAC, ROK, GODZINA, MINUTA, ZATWIERDZONA, KLIENT_ID, FRYZJER_ID, USLUGA_ID) VALUES ($dzien, $miesiac, $rok, $godzina, $minuta, 'false', $klient_id, $fryzjer_id, $usluga_id)";

    if (@$connection->query($sql) === TRUE) {
        $connection->close();
        $_SESSION['error'] = 'Rezerwacja udana';
        header('Location: index.php');
    } else {
        $connection->close();
        echo "Nie udało się dodać wizyy";
        $_SESSION['error'] = 'Nie udało się dodać wizyty';
        header('Location: index.php');
    }
}



?>