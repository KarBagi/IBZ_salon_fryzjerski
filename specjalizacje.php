<?php

require_once "dbconnect.php";

$godzina = 8;
$dzien_tygodnia = 0;

$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
}

$sql = "SELECT fryzjer.NAZWISKO, specjalizacja.NAZWA FROM fryzjer, specjalizacja, specjalizacja_fryzjer WHERE fryzjer.FRYZJER_ID=specjalizacja_fryzjer.FRYZJER_ID AND specjalizacja.SPECJALIZACJA_ID = specjalizacja_fryzjer.SPECJALIZACJA_ID";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać wizyt";
    $_SESSION['error'] = 'Nie udało się pobrać specjalizacji';
    header('Location: index.php');
    exit;
}

$wizyty=[];

if ($result->num_rows > 0) {
    $wizyty = array();
    while ($row = $result->fetch_assoc()) {
        $fryzjer_nazwisko = $row["NAZWISKO"];
        $usluga = $row["NAZWA"];
        $wizyty[] = array("fryzjer"=> $fryzjer_nazwisko, "usluga"=>$usluga);

    }
}

$connection->close();

echo '<script>';
echo 'var specjalizacje = ' . json_encode($wizyty) . ';';
echo '</script>';

?>