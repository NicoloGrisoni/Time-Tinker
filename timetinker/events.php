<?php 
    //Inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    //controllo per evitare che un utente non autenticato possa accedere a questa pagina privata
    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    //controlli sul parametro year per verificarne la correttezza
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
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Time-Tinker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item dropdown">
                    <li><a class="dropdown-item" href="../login/logout.php">Logout</a></li>
                    <li><a class="dropdown-item" href="chronology.php">Chronology</a></li>
                    <li><a class="dropdown-item" href="timeline.php">Timeline</a></li>
                </li>
                </ul>
                <form class="d-flex mt-3" role="search">
                </form>
            </div>
            </div>
        </div>
    </nav>

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