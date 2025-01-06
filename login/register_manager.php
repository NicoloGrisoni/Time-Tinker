<?php 
    //Inclusione dei file contenenti classi necessarie per il funzionamento corretto di questa pagina dei controlli login
    require_once("../classes/UserList.php");

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
    //utilizzo del metodo checkUsername dell'istanza della classe UserList per fare questa verifica
    $utenti = new UserList();
    $isUsernameValid = $utenti->checkUsername($_GET["username"]);
    if (!$isUsernameValid) {
        //reindirizzamento nel caso in cui lo username sia già presente e quindi non valido
        header("location: register.php?messaggio=username già in uso... inseriscine un altro");
        exit;
    }

    //scrittura del nuovo utente appena registrato all'interno del file 'users.csv'
    FileManager::InsertContent("users.csv", $_GET["username"] . ";" . $_GET["password"], true);

    if (!isset($_SESSION)) {
        session_start();
    }

    //reindirizzamento alla login per fare l'accesso effettivo
    header("location: login.php");
    exit;
?>