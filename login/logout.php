<?php 
    if (!isset($_SESSION)) {
        session_start();
    }
    
    //rimozione della variabile di sessione per l'autenticazione dell'utente
    unset($_SESSION["user"]);

    //reindirizzamento alla home del sito
    header("location: ../index.php");
    exit;
?>