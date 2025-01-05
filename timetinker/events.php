<?php 
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if(!isset($_GET["year"]) || empty($_GET["year"])) {
        header("location: timeline.php");
        exit;
    }
    if(!is_numeric($_GET["year"]) || $_GET["year"] > date("Y") || $_GET["year"] < -1000) {
        if($_GET["year"] != 0) {
            header("location: timeline.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/events.css">
    <script src="../js/events.js"></script>
</head>
<body>

    <div class="banner">
            <?php
                $events = EventList::GetEventsByYear($_GET["year"]);
                $length = sizeof($events);
                echo "<div class='slider' style='--quantity: $length'>";
                foreach ($events as $i => $e) {
                    $pathImg = $e->getImage();
                    $name = $e->getName();
                    echo "<div class='item' style='--position: $i'>";
                    echo "<div class='card' style='width: 18rem;'>";
                    echo "<img src='$pathImg' class='card-img-top'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $name . "</h5>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>