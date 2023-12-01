<?php

if ($_GET["imie"] == "" || $_GET["nazwisko"] == "" || $_GET["telefon"] == "") {
    header("Location: panel.php");
    exit;
}

$imie = $_GET["imie"];
$nazwisko = $_GET["nazwisko"];
$telefon = $_GET["telefon"];

require_once "dbconnect.php";
$connection = @new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    echo "Błąd" . $connection->connect_error;
    $_SESSION['error'] = 'Błąd połączenia z serwerem';
    header('Location: panel.php');
    exit;
}

$sql = "INSERT INTO fryzjer (IMIE, NAZWISKO, NUMER_KONTAKTOWY, ZATRUDNIONY) VALUES ('$imie', '$nazwisko', '$telefon', 1)";
@$connection->query($sql);

$connection->close();
header('Location: panel.php');


?>