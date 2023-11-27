<?php 
    require_once "dbconnect.php";
    $connection = @new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) 
    {
        die("Database connection error: " . $connection->connect_error);
        
    }
    else 
    {
        if (isset($_GET["termin"]) && isset($_GET["czas"]) && isset($_GET["imie"]) && isset($_GET["nazwisko"])) {
            // Odczytaj dane z metody GET
            $termin = $_GET["termin"];
            $czas = $_GET["czas"];
            list($godzina, $minuta) = explode(":",$czas);
            $imie = $_GET["imie"];
            $nazwisko = $_GET["nazwisko"];
            $fryzjer = $_GET["fryzjer"];
            $fryzjer_id="";
            $usluga = $_GET["usluga"];
            $usluga_id = "";
            $klient_id="";
        
            // Podziel datę na dzień, miesiąc i rok
            list($rok, $miesiac, $dzien) = explode("-", $termin);
        
            // Wyświetl odczytane dane
            echo "Termin: $dzien-$miesiac-$rok<br>";
            echo "Czas: $czas<br>";
            echo "Imię: $imie<br>";
            echo "Nazwisko: $nazwisko<br>";
            echo "Usługa: $usluga<br>";
            echo "Fryzjer: $fryzjer<br>";
            $phone = "";
            $email = "";
            // Sprawdź, czy parametr phone został przesłany
            if (isset($_GET["phone"])) {
                $phone = $_GET["phone"];
                echo "Numer telefonu: $phone<br>";
            }
        
            // Sprawdź, czy parametr email został przesłany
            if (isset($_GET["email"])) {
                $email = $_GET["email"];
                echo "Email: $email";
            }

            $sql = "INSERT INTO KLIENT (IMIE, NAZWISKO, NUMER_TELEFONU, EMAIL) VALUES ('$imie', '$nazwisko', '$phone', '$email')";
        
            if ($connection->query($sql) === TRUE) {
                echo "Nowy rekord został dodany poprawnie.";
            } else {
                echo "Błąd: " . $sql . "<br>" . $connection->error;
            }

            $sql = "Select KLIENT_ID from klient where nazwisko = '$nazwisko'";
            if ($result = @$connection->query($sql)) {
                
                    $row = $result->fetch_assoc();
                        
                    $klient_id = $row['KLIENT_ID'];

                    $result->free_result();
                }

            $sql = "Select FRYZJER_ID from fryzjer where nazwisko = '$fryzjer'";
            if ($result = @$connection->query($sql)) {
                    $row = $result->fetch_assoc();
                        
                    $fryzjer_id = $row['FRYZJER_ID'];
                    echo "Fryzjer_id: $fryzjer_id";
                    $result->free_result();
                }

            $sql = "Select USLUGA_ID from usluga where nazwa = '$usluga'";
            if ($result = @$connection->query($sql)) {
                
                    $row = $result->fetch_assoc();
                        
                    $usluga_id = $row['USLUGA_ID'];

                    $result->free_result();
                }

            if($fryzjer_id!="" && $usluga_id!="")
            {
            $sql = "INSERT INTO WIZYTA (DZIEN, MIESIAC, ROK, GODZINA, MINUTA, ZATWIERDZONY, KLIENT_ID, FRYZJER_ID, USLUGA_ID) VALUES ($dzien, $miesiac, $rok, $godzina, $minuta, false, $klient_id, $fryzjer_id, $usluga_id)";
        
        if ($connection->query($sql) === TRUE) {
            echo "Nowy rekord został dodany poprawnie.";
        } else {
            echo "Błąd: " . $sql . "<br>" . $connection->error;
        }
            }

        } else {
            echo "Brak wymaganych danych w adresie URL.";
        }



        //     $sql = "SELECT * FROM FRYZJER";

        // if ($result = @$connection->query($sql)) {
        //     $counter = 0;
        
        //     while ($row = $result->fetch_assoc()) {
        //         if ($counter <= 1) {
        //             $name = $row['NAZWISKO'];
        //             echo $name;
                    
        //         }
        
        //         $counter++;
        //     }
        
        //     $result->free_result();
        
        

        // $name = "Jakub";
        // $surname = "Jabłczyński";
        // $phone = "444555666";
        
        // $sql = "INSERT INTO FRYZJER (IMIE, NAZWISKO, NUMER_KONTAKTOWY) VALUES ('$name', '$surname', '$phone')";
        
        // if ($connection->query($sql) === TRUE) {
        //     echo "Nowy rekord został dodany poprawnie.";
        // } else {
        //     echo "Błąd: " . $sql . "<br>" . $connection->error;
        // }

    }

    $connection->close();
?>