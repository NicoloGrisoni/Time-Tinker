<?php 
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
    <title>Timeline</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/timeline.css">
    <script src="../js/timeline.js"></script>
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
                <li><a class="dropdown-item" href="#">Chronology</a></li>
            </li>
            </ul>
            <form class="d-flex mt-3" role="search">
            </form>
        </div>
        </div>
    </div>
    </nav>

    <div class="cornice">
        <img src="" id="image">
    </div>

    <div class="flex-parent">
        <div class="input-flex-container">
            <?php
                $importants = EventList::GetImportants();
                foreach ($importants as $i) {
                    $year = $i->getDate();
                    $name = $i->getName();
                    $img = $i->getImage();
                    echo "<div class='input'>
                            <label hidden>$img</label>
                            <span data-year='$year' data-info='$name'></span>
                        </div>";
                }   
            ?>
        </div>
    </div>

    <div class="containerSearch">
        <form action="events.php" method="get">
            <input type="number" id="search" name="year" min="-1000" max="<?php echo date("Y") ?>">
            <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>