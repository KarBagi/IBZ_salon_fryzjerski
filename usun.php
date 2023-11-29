<?php

if ($_GET["id_wizyty"] == "") {
    header("Location: kalendarzFryzjer.php");
    exit;
}

$id_wizyty = $_GET["id_wizyty"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: kalendarzFryzjer.php');
    exit;
}

$sql = "DELETE FROM wizyta WHERE WIZYTA_ID = $id_wizyty";
@$connection->query($sql);

$connection->close();
header('Location: kalendarzFryzjer.php');


?>