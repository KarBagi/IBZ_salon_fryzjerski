<?php

if ($_POST["nazwa"] == "" || $_POST["cena"] == "" || $_POST["czas"] == "") {
    header("Location: panel.php");
    exit;
}

$nazwa = $_POST["nazwa"];
$cena = $_POST["cena"];
$czas = $_POST["czas"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: panel.php');
    exit;
}

$sql = "INSERT INTO usluga (NAZWA, CENA, CZAS_TRWANIA, AKTYWNA) VALUES ('$nazwa', $cena, $czas,1)";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>
