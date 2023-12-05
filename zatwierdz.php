<?php

if ($_POST["id_wizyty"] == "") {
    header("Location: kalendarzFryzjer.php");
    exit;
}

$id_wizyty = $_POST["id_wizyty"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: kalendarzFryzjer.php');
    exit;
}

$sql = "UPDATE wizyta SET ZATWIERDZONA = 1 WHERE WIZYTA_ID = $id_wizyty";
@$connection->query($sql);

$connection->close();
header('Location: kalendarzFryzjer.php');


?>
