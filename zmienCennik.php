<?php

if ($_GET["id"] == "" || $_GET["nazwa"] == "" || $_GET["cena"] == "" || $_GET["czas"] == "") {
    header("Location: panel.php");
    exit;
}

$id_uslugi = $_GET["id"];
$nazwa = $_GET["nazwa"];
$cena = $_GET["cena"];
$czas = $_GET["czas"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: panel.php');
    exit;
}

$sql = "UPDATE usluga SET nazwa = '$nazwa', cena = $cena, czas_trwania = $czas WHERE USLUGA_ID = $id_uslugi";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>