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
    timeline
    <a href="../login/logout.php">logout</a>

    <!-- navbar di bootstrap a scomparsa con link per logout e chronology -->
</body>
</html>