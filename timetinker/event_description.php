<?php 
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if(!isset($_GET["src"]) || empty($_GET["src"])) {
        header("location: timeline.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Description</title>
</head>
<body>
    <?php
        $srcImg = $_GET["src"];
        $srcImg = explode("Time-Tinker", $srcImg)[1];
        $srcImg = ".." . $srcImg;
        $event = EventList::GetEventByImagePath($srcImg);

        echo $event->toCSV();
    ?>
</body>
</html>