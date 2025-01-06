<?php 
    //inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once "../classes/FileManager.php";
    require_once "../classes/user.php";
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    //controllo per evitare che un utente non autenticato possa accedere a questa pagina privata
    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $user = $_SESSION["user"];
        $username = $user->getUsername();
        $fileRows = FileManager::GetRowFromFile($username."chronology.csv");
        foreach ($fileRows as $row) {
            $fields = FileManager::GetFieldsFromRow(";", $row);
            $evento = EventList::GetEventByIndex($fields[0]);
            $name = $evento->getName();
            echo "<div class='container' style='border: 1px solid black;'>";
            echo "<p>Evento: $name</p><br>";

            $results = $fields[1];
            echo "<p>Conseguenze: $results</p>";
            echo "</div>";
        }
    ?>
</body>
</html>