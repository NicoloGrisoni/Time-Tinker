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

    <title>Register</title>
</head>
<body>
    <?php 
        if (isset($_GET["messaggio"])) {
            echo $_GET["messaggio"];
        }
    ?>
    
    <form action="register_manager.php" method="get" onsubmit="return validateForm()">
        <label for="usernameTxt">Username: </label>
        <input type="text" id="usernameTxt" name="username"><br>

        <label for="passwordTxt">Password: </label>
        <input type="password" id="passwordTxt" name="password"><br>

        <label for="confirmPasswordTxt">Conferma Password: </label>
        <input type="password" id="confirmPasswordTxt"><br>

        <button>Registrati</button>
        <input type="reset" value="Reset">
    </form>
</body>

<script>
    function validateForm() {
        var password = document.getElementById("passwordTxt").value;
        var confirmPassword = document.getElementById("confirmPasswordTxt").value;

        if (password !== confirmPassword) {
            alert("Le password non corrispondono");
            return false;
        }

        return true;
    }
</script>
</html>