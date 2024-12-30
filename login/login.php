<?php 
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION["user"])) {
        header("location: ../timetinker/timeline.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
</head>
<body>
    <?php 
        if (isset($_GET["messaggio"])) {
            echo $_GET["messaggio"];
        }
    ?>
    
    <form action="login_manager.php" method="get">
        <label for="usernameTxt">Username: </label>
        <input type="text" id="usernameTxt" name="username"><br>

        <label for="passwordTxt">Password: </label>
        <input type="password" id="passwordTxt" name="password"><br>

        <button>Login</button>
        <input type="reset" value="Reset">
    </form>

    <a href="register.php">Registrati</a>
</body>
</html>