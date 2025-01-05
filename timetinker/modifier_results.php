<?php 
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
    <title>Document</title>
</head>
<body>
    <?php
        $ollama_url = "http://localhost:11434/api/generate";

        $prompt = $_POST['prompt'];
        $model = "llama3";

        $data = [ 'prompt' => $prompt, 'model' => $model, 'stream' => false ];

        $ch = curl_init($ollama_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        $return = json_decode($response, true);

        echo $return['response'];
    ?>
</body>
</html>