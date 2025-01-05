<?php 
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if(!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Description</title>
    <link rel="stylesheet" href="../css/event_description.css"> <!-- Collegamento al CSS -->
</head>
<body>
    <?php
        $srcImg = $_GET["src"];
        $srcImg = explode("Time-Tinker", $srcImg)[1];
        $srcImg = ".." . $srcImg;
        $event = EventList::GetEventByImagePath($srcImg);

        $name = $event->getName();
        $description = $event->getDescription();
        $date = $event->getDate();
    ?>

    <!-- Card Container -->
    <div class="card-container">
        <div class="card">
            <img src="<?php echo $srcImg; ?>" alt="Event Image" class="card-img">
            <div class="card-content">
                <h1 class="event-name"><?php echo htmlspecialchars($name); ?></h1>
                <p class="event-date"><?php echo htmlspecialchars($date); ?></p>
                <p class="event-description"><?php echo nl2br(htmlspecialchars($description)); ?></p>
                
                <a href="event_modifier.php?src=<?php echo urlencode($srcImg); ?>" class="event-action-button">Manipola la storia</a>
            </div>
        </div>
    </div>
</body>
</html>