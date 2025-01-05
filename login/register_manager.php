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
    $isUsernameValid = $utenti->checkUsername($_GET["username"]);
    if (!$isUsernameValid) {
        header("location: register.php?messaggio=username già in uso... inseriscine un altro");
        exit;
    }


    FileManager::InsertContent("users.csv", $_GET["username"] . ";" . $_GET["password"], true);

    if (!isset($_SESSION)) {
        session_start();
    }

    header("location: login.php");
    exit;
?>