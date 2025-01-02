<?php 
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }
?>