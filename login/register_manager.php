<?php 
    require_once("../classes/UserList.php");

    if (!isset($_GET["username"]) || !isset($_GET["password"])) {
        header("location: register.php?messaggio=devi impostare i campi correttamente");
        exit;
    }

    if (empty($_GET["username"]) || empty($_GET["password"])) {
        header("location: register.php?messaggio=campi vuoti");
        exit;
    }

    $utenti = new UserList("users.csv");
    $user = $utenti->doLogin($_GET["username"], $_GET["password"]);
    if (!is_null($user)) {
        header("location: register.php?messaggio=credenziali già utilizzate... inseriscine delle altre");
        exit;
    }


    FileManager::InsertContent("users.csv", $_GET["username"] . ";" . $_GET["password"], true);

    if (!isset($_SESSION)) {
        session_start();
    }

    header("location: login.php");
    exit;
?>