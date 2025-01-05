<?php 
    require_once "../classes/FileManager.php";
    require_once "../classes/user.php";
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

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
            echo "<p>Conseguenze: $fields[1]</p>";
            echo "</div>";
        }
    ?>
</body>
</html>