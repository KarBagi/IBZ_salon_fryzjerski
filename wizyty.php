<?php

require_once "dbconnect.php";

$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
}

$sql = "SELECT WIZYTA_ID, DZIEN, MIESIAC, ROK, GODZINA,ZATWIERDZONA, fryzjer.NAZWISKO, usluga.NAZWA,klient.IMIE, klient.NAZWISKO AS KLIENT_NAZWISKO, KLIENT.NUMER_TELEFONU, KLIENT.EMAIL from wizyta,fryzjer,usluga,klient where usluga.USLUGA_ID=wizyta.USLUGA_ID AND fryzjer.FRYZJER_ID=wizyta.FRYZJER_ID AND klient.KLIENT_ID=wizyta.KLIENT_ID";
$result = @$connection->query($sql);
if (!$result) {
    $result->free_result();
    $connection->close();
    echo "Nie udało się pobrać wizyt";
    $_SESSION['error'] = 'Nie udało się pobrać wizyt';
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
        $id_wizyty = $row["WIZYTA_ID"];
        $zatwierdzona = $row["ZATWIERDZONA"];
        $klient_imie = $row["IMIE"];
        $klient_nazwisko = $row["KLIENT_NAZWISKO"];
        $klient_telefon = $row["NUMER_TELEFONU"];
        $klient_email = $row["EMAIL"];
        $wizyty[] = array("id_wizyty"=>$id_wizyty ,"data" => $data, "godzina" => $godzina, "fryzjer"=> $fryzjer_nazwisko, "usluga"=>$usluga, "zatwierdzona"=>$zatwierdzona, "klient_imie"=>$klient_imie, "klient_nazwisko"=>$klient_nazwisko, "numer_telefonu"=>$klient_telefon, "email"=>$klient_email);

    }
}

$connection->close();

echo '<script>';
echo 'var wizyty = ' . json_encode($wizyty) . ';';
echo '</script>';

?>