<?php

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
}

$sql = "SELECT IMIE, NAZWISKO FROM FRYZJER";
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

$sql = "SELECT NAZWA FROM USLUGA";
$result = @$connection->query($sql);

if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać usług";

    header('Location: index.php');
    exit;
}

$uslugi = [];

if ($result->num_rows > 0) {
    $uslugi = array();

    while ($row = $result->fetch_assoc()) {
        $usluga = $row["NAZWA"];

        $uslugi[] = array($usluga);
    }
}

$connection->close();

echo '<script>';
echo 'var fryzjerzy = ' . json_encode($fryzjerzy) . ';';
echo 'var uslugi = ' . json_encode($uslugi) . ';';
echo "var selectedService = uslugi[0];";
echo "var selectedWorker = fryzjerzy[0];";
echo '</script>';

?>