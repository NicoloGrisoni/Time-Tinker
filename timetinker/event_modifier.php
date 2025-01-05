<?php 
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }
?>

<?php
// Se il form è stato inviato, esegui il codice PHP per inviare la richiesta all'API
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // La tua chiave API di Anthropic (inseriscila qui)
    $apiKey = 'YOUR_API_KEY';  // Sostituisci con la tua chiave API

    // Imposta l'endpoint dell'API di Anthropic
    $apiUrl = 'https://api.anthropic.com/v1/claude/completions';  // Assicurati che l'endpoint sia corretto

    // Ottieni l'evento e la modifica dall'input del form
    $evento = $_POST['evento'];
    $modifica = $_POST['modifica'];

    // Creiamo il prompt che invieremo all'API
    $prompt = "Immagina che l\'evento storico \"{$evento}\" non avvenga come descritto. Invece, {$modifica}. Quali sarebbero le possibili conseguenze di questo cambiamento nel corso della storia?";

    // Crea il corpo della richiesta
    $data = array(
        'model' => 'claude-2', // Modello che vuoi utilizzare, verifica quale modello è attivo
        'prompt' => $prompt,
        'max_tokens' => 500,  // Limite per la risposta in termini di numero di token
        'temperature' => 0.7   // Imposta la temperatura per la creatività delle risposte
    );

    // Impostazioni per la richiesta cURL
    $options = array(
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($data)
    );

    // Esegui la richiesta cURL
    $ch = curl_init();
    curl_setopt_array($ch, $options);

    // Ottieni la risposta dell'API
    $response = curl_exec($ch);

    // Verifica se ci sono errori nella richiesta
    if(curl_errno($ch)) {
        $result = 'Error: ' . curl_error($ch);
    } else {
        // Decodifica la risposta JSON
        $responseData = json_decode($response, true);

        // Visualizza la risposta generata dal modello
        if(isset($responseData['completion'])) {
            $result = $responseData['completion'];
        } else {
            $result = "Errore nella risposta: " . json_encode($responseData);
        }
    }

    // Chiudi la connessione cURL
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <div class="container">
            <form action="modifier_result.php" method="post">
                <div>
                    <label for="prompt">prompt</label>
                    <input type="text" name="prompt" id="prompt">
                </div>

                <div>
                    <button type="submit">Invia</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>