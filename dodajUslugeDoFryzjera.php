<?php

if ($_POST["fryzjer"] == "" || $_POST["usluga"] == "") {
    header("Location: panel.php");
    exit;
}

$id_fryzjera = $_POST["fryzjer"];
$id_uslugi = $_POST["usluga"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: panel.php');
    exit;
}

$sql = "INSERT INTO specjalizacja_fryzjer (SPECJALIZACJA_ID, FRYZJER_ID) VALUES ($id_uslugi, $id_fryzjera)";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>
