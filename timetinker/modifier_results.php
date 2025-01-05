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
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La tua modifica</title>
    <link rel="stylesheet" href="../css/modifier_results.css">
</head>
<body>
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
</body>
</html>