<?php 
    require_once "../classes/EventList.php";

    session_start();

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if (!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php");
        exit;
    }

    $srcImg = $_GET["src"];
    $event = EventList::GetEventByImagePath($srcImg);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Evento Storico</title>
    <link rel="stylesheet" href="../css/event_modifier.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Modifica il Corso della Storia</h1>
            <p>Scopri come piccole modifiche possano cambiare il destino di un evento storico!</p>
        </div>

        <div class="event-details">
            <h2>Evento Selezionato</h2>
            <div class="event-info">
                <img src="<?php echo htmlspecialchars($srcImg); ?>" alt="Immagine evento" class="event-img">
                <div class="event-description">
                    <p><strong>Nome Evento:</strong> <?php echo htmlspecialchars($event->getName()); ?></p>
                    <p><strong>Data:</strong> <?php echo htmlspecialchars($event->getDate()); ?></p>
                    <p><strong>Descrizione:</strong> <?php echo nl2br(htmlspecialchars($event->getDescription())); ?></p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h2>Modifica la Storia</h2>
            <form action="modifier_results.php?src=<?php echo urlencode($srcImg); ?>" method="post">
                <textarea name="prompt" placeholder="Descrivi qui la modifica che vuoi fare..."></textarea>
                <button type="submit">Invia Modifica</button>
            </form>
        </div>
    </div>
</body>
</html>