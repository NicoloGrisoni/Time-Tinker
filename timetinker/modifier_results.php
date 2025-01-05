<?php 
    require_once "../classes/EventList.php";
    require_once "../classes/FileManager.php";
    require_once "../classes/user.php";

    session_start();

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if (!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php");
        exit;
    }

    // Variabili per l'evento
    $srcImg = $_GET["src"];
    $event = EventList::GetEventByImagePath($srcImg);
    $name = $event->getName();
    $date = $event->getDate();

    // Gestione della richiesta al modello Llama
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
        $ollama_url = "http://localhost:11434/api/generate";
        $prompt = "Quali sarebbero state le conseguenze dell'evento $name se " . $_POST['prompt'] . "? Rispondi in italiano";
        $model = "llama3";

        $data = ['prompt' => $prompt, 'model' => $model, 'stream' => false];

        // Esegui la richiesta CURL
        $ch = curl_init($ollama_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        $return = json_decode($response, true);

        // Mostra la risposta
        $result = $return['response'];

        $user = $_SESSION["user"];
        $username = $user->getUsername();
        $eventIndex = EventList::GetEventIndexByName($name);
        $row = $eventIndex.";"."$result\r\n";

        FileManager::InsertContent($username."chronology.csv", $row, true);
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La tua modifica</title>
    <link rel="stylesheet" href="../css/modifier_results.css">

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


    <div class="container">
        <div class="event-details">
            <h2>Dettagli Evento Selezionato</h2>
            <div class="event-info">
                <img src="<?php echo htmlspecialchars($srcImg); ?>" alt="Immagine evento" class="event-img">
                <div class="event-description">
                    <p><strong>Nome Evento:</strong> <?php echo htmlspecialchars($name); ?></p>
                    <p><strong>Data:</strong> <?php echo htmlspecialchars($date); ?></p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h2>La tua modifica</h2>
            <div class="user-choice">
                <p><?php echo nl2br(htmlspecialchars($_POST['prompt'])); ?></p>
            </div>
        </div>

        <div class="result-container">
            <h3>Risultato della tua modifica:</h3>
            <p><?php echo nl2br(htmlspecialchars($result)); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>