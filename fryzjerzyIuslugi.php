<?php

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
}

$sql = "SELECT IMIE, NAZWISKO FROM FRYZJER WHERE ZATRUDNIONY=1";
$result = @$connection->query($sql);

if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać wizyt";

    header('Location: index.php');
    exit;
}

$fryzjerzy = [];

if ($result->num_rows > 0) {
    $fryzjerzy = array();

    while ($row = $result->fetch_assoc()) {
        $nazwisko = $row["NAZWISKO"];

        $fryzjerzy[] = array($nazwisko);
    }
}

$sql = "SELECT NAZWA, CZAS_TRWANIA FROM USLUGA WHERE AKTYWNA = 1";
$result = @$connection->query($sql);

if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać usług";

    header('Location: index.php');
    exit;
}

$uslugi = [];
$czas_uslugi = [];

if ($result->num_rows > 0) {
    $uslugi = array();
    $czas_uslugi = array();

    while ($row = $result->fetch_assoc()) {
        $usluga = $row["NAZWA"];
        $czas = $row["CZAS_TRWANIA"];

        $uslugi[] = array($usluga);
        $czas_uslugi[] = array("nazwa"=> $usluga, "czas"=> $czas);
    }
}

$connection->close();

echo '<script>';
echo 'var fryzjerzy = ' . json_encode($fryzjerzy) . ';';
echo 'var uslugi = ' . json_encode($uslugi) . ';';
echo 'var czas_uslugi = ' . json_encode($czas_uslugi) . ';';
echo "var selectedService = uslugi[0];";
echo "var selectedWorker = fryzjerzy[0];";
echo '</script>';

?>