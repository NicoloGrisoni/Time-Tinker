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
    <title>Cronologia Eventi</title>
    <link rel="stylesheet" href="../css/chronology.css">
</head>
<body>
    <header>
        <h1>Cronologia degli Eventi Modificati</h1>
    </header>
    <main>
        <?php
            $user = $_SESSION["user"];
            $username = $user->getUsername();
            $filename = $username . "chronology.csv";
            $fileRows = FileManager::GetRowFromFile($filename);

            if ($fileRows) {
                foreach ($fileRows as $row) {
                    $fields = FileManager::GetFieldsFromRow("§", $row);
                    if ($fields == null) {
                        continue;
                    }
                    $evento = EventList::GetEventByIndex($fields[0]);
                    $name = $evento->getName();
                    $src = $evento->getImage();
                    $userRequest = $fields[2]; // Supponiamo che la richiesta utente sia nel terzo campo
                    $results = $fields[1]; // Conseguenze

                    echo "<div class='event-container'>";
                    echo "<h2 class='event-title'>Evento: $name</h2>";
                    echo "<p class='event-details'><strong>Richiesta dell'utente:</strong> $userRequest</p>";
                    echo "<p class='event-details'><strong>Conseguenze:</strong> $results</p>";
                    echo "<form action='modifier_results.php' method='get'>";
                    echo "<input type='hidden' name='src' value='" . $src . "'>";
                    echo "<input type='hidden' name='prompt' value='" . htmlspecialchars($userRequest) . "'>";
                    echo "<input type='hidden' name='results' value='" . htmlspecialchars($results) . "'>";
                    echo "<button type='submit' class='event-button'>Vedi Dettagli</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-events'>Non ci sono eventi registrati.</p>";
            }
        ?>
    </main>
</body>
</html>