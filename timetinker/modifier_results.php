<?php
    //inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once "../classes/EventList.php";
    require_once "../classes/FileManager.php";
    require_once "../classes/user.php";

    session_start();

    //controllo per evitare che un utente non autenticato possa accedere a questa pagina privata
    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    //controllo della presenza e validità del parametro src
    if (!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php?messaggio=nessun evento selezionato");
        exit;
    }

    // Variabili per l'evento
    $srcImg = $_GET["src"];
    $event = EventList::GetEventByImagePath($srcImg);
    $name = $event->getName();
    $date = $event->getYears();

    if(isset($_GET['prompt']) && isset($_GET['results']) && isset($_GET['src'])) {
        $result = $_GET['results'];
        $userRequest = $_GET['prompt'];
    }
    // Gestione della richiesta al modello Llama
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
        $ollama_url = "http://localhost:11434/api/generate";
        $prompt = "Quali sarebbero state le conseguenze dell'evento $name se " . $_POST['prompt'] . "? Rispondi in italiano";
        $model = "llama3";

        $userRequest = $_POST['prompt'];

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
        $row = $eventIndex."§".$result."§".$_POST['prompt']."\r\n";

        $filename = $username."chronology.csv";

        FileManager::createFile($filename);
        FileManager::InsertContent($filename, $row, true);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            <h2>Dettagli Evento Selezionato<button class="export-button" onclick="exportPDF()"><i class="fa-solid fa-file-pdf"></i></button></h2>
            <div class="event-info">
                <img src="<?php echo $srcImg; ?>" alt="Immagine evento" class="event-img">
                <div class="event-description">
                    <p><strong>Nome Evento:</strong> <?php echo $name; ?></p>
                    <p><strong>Data:</strong> <?php echo $date; ?></p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h2>La tua modifica</h2>
            <div class="user-choice">
                <p><?php echo $userRequest; ?></p>
            </div>
        </div>

        <div class="result-container">
            <h3>Risultato della tua modifica:</h3>
            <!-- nl2br server per migliorare l'indentazione del testo -->
            <p><?php echo nl2br($result); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        // Funzione per esportare la pagina in PDF
        function exportPDF() {
            const element = document.getElementsByTagName('body')[0]; // Seleziona il contenuto da esportare

            // Usa html2pdf per convertire il contenuto selezionato in PDF
            html2pdf()
                .from(element) // Seleziona il contenuto da includere nel PDF
                .save('cronologia_eventi.pdf'); // Imposta il nome del file PDF
        }
    </script>
</body>
</html>