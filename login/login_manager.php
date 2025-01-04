<?php 
    //inclusione delle classi necessarie
    require_once("../classes/user_list.php");
    require_once("../classes/file_manager.php");

    //CONTROLLI DI PRESENZA CORRETTA DEI DATI
    //controllo se le variabili di username e password sono impostate
    if (!isset($_GET["username"]) || !isset($_GET["password"])) {
        //reindirizzamento alla pagina login, errore di mancanza dati
        header("location: login.php?messaggio=devi impostare le credenziali");
        exit;
    }

    //controllo se le variabili di username e password corrispondono ad un valore, quindi non sono vuote
    if (empty($_GET["username"]) || empty($_GET["password"])) {
        //reindirizzamento alla pagina login, errore di dati vuoti
        header("location: login.php?messaggio=credenziali vuote");
        exit;
    }


    //CONTROLLO VALIDITA DEI DATI --> USERNAME E PASSWORD CORRISPONDONO AD UN UTENTE PRESENTE
    //definizione del file degli utenti
    $file_utenti = "users.csv";
    //definizione del vettore contenente tutti gli utenti presenti
    $utenti = new UserList($file_utenti);
    //chiamata del metodo tryDoLogin per verificare che username e password inserite dall'utente siano valide
    $user = $utenti->doLogin($_GET["username"], $_GET["password"]);

    //usertype === null --> username e password non valide
    //usertype !== null --> username e password valide, salvataggio il tipo di utente
    if (is_null($user)) {
        header("location: login.php?messaggio=credenziali errate");
        exit;
    }

    //dati di sessione
    if (!isset($_SESSION)) {
        session_start();
    }

    //salvataggio del tipo di utente
    $_SESSION["user"] = $user;
    header("location: ../timetinker/timeline.php");
    exit;
?>