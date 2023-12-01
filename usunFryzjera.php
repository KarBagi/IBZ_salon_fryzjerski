<?php

if ($_GET["id"] == "") {
    header("Location: panel.php");
    exit;
}

$id_fryzjera = $_GET["id"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: panel.php');
    exit;
}

$sql = "UPDATE fryzjer SET zatrudniony=0 WHERE FRYZJER_ID = $id_fryzjera";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>