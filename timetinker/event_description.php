<?php 
    //inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    //controllo per evitare che un utente non autenticato possa accedere a questa pagina privata
    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    //controllo presenza e validità parametro src
    if(!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php?messaggio=nessun evento selezionato");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Description</title>
    <link rel="stylesheet" href="../css/event_description.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

    <?php
        $srcImg = $_GET["src"];
        $srcImg = explode("Time-Tinker", $srcImg)[1];
        $srcImg = ".." . $srcImg;
        $event = EventList::GetEventByImagePath($srcImg);

        $name = $event->getName();
        $description = $event->getDescription();
        $date = $event->getYears();
    ?>

    <!-- Card Container -->
    <div class="card-container">
        <div class="card">
            <img src="<?php echo $srcImg; ?>" alt="Event Image" class="card-img">
            <div class="card-content">
                <h1 class="event-name"><?php echo $name; ?></h1>
                <p class="event-date"><?php echo $date; ?></p>
                <p class="event-description"><?php echo $description; ?></p>
                
                <a href="event_modifier.php?src=<?php echo urlencode($srcImg); ?>" class="event-action-button">Manipola la storia</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>