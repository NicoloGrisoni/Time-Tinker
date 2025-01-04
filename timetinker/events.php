<?php
require_once "../classes/EventList.php";

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
    exit;
}

if (!isset($_GET["year"]) || empty($_GET["year"]) || !is_numeric($_GET["year"]) || $_GET["year"] > date("Y") || $_GET["year"] < -1000) {
    if($_GET["year"] != 0)
    {
        header("location: timeline.php?messaggio=Anno non valido");
        exit;
    }
}

$year = (int) $_GET["year"];
$events = EventList::GetEventsByYear($year);
$apiEvent = EventList::getHistoricalEventFromAPI($year);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventi Storici</title>
</head>
<body>
    <h1>Eventi Storici</h1>
    <h2>Anno: <?php echo $year; ?></h2>

    <h3>Eventi da Sistema Locale:</h3>
    <table>
        <tr>
            <th>Titolo</th>
            <th>Descrizione</th>
            <th>Data</th>
        </tr>
        <?php foreach ($events as $e): ?>
            <tr>
                <td><?php echo htmlspecialchars($e->getName()); ?></td>
                <td><?php echo htmlspecialchars($e->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($e->getDate()); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Evento Storico dalla Wikipedia (API):</h3>
    <?php if ($apiEvent['success']): ?>
        <pre>
            Anno: <?php echo $apiEvent['event']->getDate(); ?>
            Nome: <?php echo $apiEvent['event']->getName(); ?>
            Descrizione: <?php echo $apiEvent['event']->getDescription(); ?>
            Importanza: <?php echo $apiEvent['event']->isImportant(); ?>
            Immagine: <?php echo $apiEvent['event']->getImage(); ?>
        </pre>
    <?php else: ?>
        <p><?php echo $apiEvent['message']; ?></p>
    <?php endif; ?>
</body>
</html>