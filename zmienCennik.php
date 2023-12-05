<?php

if ($_POST["id"] == "" || $_POST["nazwa"] == "" || $_POST["cena"] == "" || $_POST["czas"] == "") {
    header("Location: panel.php");
    exit;
}

$id_uslugi = $_POST["id"];
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

$sql = "UPDATE usluga SET nazwa = '$nazwa', cena = $cena, czas_trwania = $czas WHERE USLUGA_ID = $id_uslugi";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>
