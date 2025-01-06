<?php 
    //Inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once("../classes/UserList.php");
    require_once("../classes/FileManager.php");


    //Controllo per verificare che le variabili di username e password siano effettivamente impostate e presenti
    if (!isset($_GET["username"]) || !isset($_GET["password"])) {
        //reindirizzamento nel caso in cui anche solo una delle due varibili non sia impostata
        header("location: login.php?messaggio=devi impostare le credenziali");
        exit;
    }

    //Controllo per verificare che le variabili di username e password non siano vuote
    if (empty($_GET["username"]) || empty($_GET["password"])) {
        //reindirizzamento nel caso in cui anche solo una delle due varibili sia vuota
        header("location: login.php?messaggio=credenziali vuote");
        exit;
    }

    //controllo per verificare che username e password passate siano valide per effettuare l'accesso
    //utilizzo del metodo doLogin dell'istanza della classe UserList per fare questa verifica
    $utenti = new UserList();
    $user = $utenti->doLogin($_GET["username"], $_GET["password"]);

    if (is_null($user)) {
        //reindirizzamento nel caso in cui le credenziali di accesso non corrispondono con nessun utente presente
        header("location: login.php?messaggio=credenziali errate");
        exit;
    }


    //salvataggio nella sessione dell'utente e reindirizzamento alla pagina privata
    if (!isset($_SESSION)) {
        session_start();
    }

    $_SESSION["user"] = $user;
    header("location: ../timetinker/timeline.php");
    exit;
?>