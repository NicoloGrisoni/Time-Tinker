<?php 
    if (!isset($_SESSION)) {
        session_start();
    }

    //controllo per reindirizzare direttamente alla pagina privata un utente già autenticato al sito
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="wrapper">
        <form action="register_manager.php" method="get" onsubmit="return validateForm()">
            <h1>Register</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="passwordTxt" placeholder="Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="confirmPasswordTxt" placeholder="Confirm Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <button type="submit" class="btn">Register</button>

            <div class="register-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>

    <!-- <form action="register_manager.php" method="get" onsubmit="return validateForm()">
        <label for="usernameTxt">Username: </label>
        <input type="text" id="usernameTxt" name="username"><br>

        <label for="passwordTxt">Password: </label>
        <input type="password" id="passwordTxt" name="password"><br>

        <label for="confirmPasswordTxt">Conferma Password: </label>
        <input type="password" id="confirmPasswordTxt"><br>

        <button>Registrati</button>
        <input type="reset" value="Reset">
    </form> -->
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
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

    function showAlert(messaggio) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: messaggio
            });
        }

    <?php if (isset($_GET["messaggio"])): ?>
        showAlert("<?php echo $_GET["messaggio"]; ?>");
    <?php endif; ?>
</script>
</html>