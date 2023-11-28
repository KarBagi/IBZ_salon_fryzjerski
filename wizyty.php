<?php

require_once "dbconnect.php";


$godzina = 8;
$dzien_tygodnia = 0;

$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    $_SESSION['godzina'] = $godzina;
    $_SESSION['dzien_tygodnia'] = $dzien_tygodnia;
}

$sql = "SELECT DZIEN, MIESIAC, ROK, GODZINA, fryzjer.NAZWISKO, usluga.NAZWA from wizyta,fryzjer,usluga where usluga.USLUGA_ID=wizyta.USLUGA_ID AND fryzjer.FRYZJER_ID=wizyta.FRYZJER_ID";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać wizyt";
    $_SESSION['error'] = 'Nie udało się pobrać wizyt';
    $_SESSION['godzina'] = $godzina;
    $_SESSION['dzien_tygodnia'] = $dzien_tygodnia;
    header('Location: index.php');
    exit;
}

$wizyty=[];

if ($result->num_rows > 0) {
    $wizyty = array();
    while ($row = $result->fetch_assoc()) {
        $data = $row["ROK"] . "-" . $row["MIESIAC"] . "-" . $row["DZIEN"];
        $godzina = $row["GODZINA"];
        $fryzjer_nazwisko = $row["NAZWISKO"];
        $usluga = $row["NAZWA"];
        $wizyty[] = array("data" => $data, "godzina" => $godzina, "fryzjer"=> $fryzjer_nazwisko, "usluga"=>$usluga);

    }
}

$connection->close();

echo '<script>';
echo 'var wizyty = ' . json_encode($wizyty) . ';';
echo '</script>';

?>