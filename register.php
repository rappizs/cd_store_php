<?php
session_start();
if ($_SESSION['logged_in'] === true)
    header("location: discs.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Regisztráció</title>
    <script>
        function formCheck() {
            const name = document.getElementById("name").value;
            const password = document.getElementById("password").value;
            const password_again = document.getElementById("password_again").value;

            if (name === "" || password === "" || password_again === "") {
                document.getElementById("error").innerHTML = "Töltsd ki mindhárom mezőt a regisztrációhoz!";
                return false;
            }
            if (password_again !== password) {
                document.getElementById("error").innerHTML = "Nem egyezik a két jelszó!";
                return false;
            }
            if (password.length < 5) {
                document.getElementById("error").innerHTML = "A jelszónak minimum 5 karakteresnek kell lennie!";
                return false;
            }
            if (name.length < 5 && name.length > 0) {
                document.getElementById("error").innerHTML = "A névnek minimum 5 karakteresnek kell lennie!";
                return false;
            }
            return true;
        }

        function pwdCheck() {
            const password = document.getElementById("password").value;
            const password_again = document.getElementById("password_again").value;

            if (password.length < 5) {
                document.getElementById("error").innerHTML = "A jelszónak minimum 5 karakteresnek kell lennie!";
                return;
            }
            document.getElementById("error").innerHTML = "";
        }

        function pwdCheckAgain() {
            const password = document.getElementById("password").value;
            const password_again = document.getElementById("password_again").value;

            if (password_again !== password) {
                document.getElementById("error").innerHTML = "Nem egyezik a két jelszó!";
                return;
            }
            document.getElementById("error").innerHTML = "";
        }

        function nameCheck() {
            const name = document.getElementById("name").value;

            if (name.length < 5 && name.length > 0) {
                document.getElementById("error").innerHTML = "A névnek minimum 5 karakteresnek kell lennie!";
                return;
            }
            document.getElementById("error").innerHTML = "";
        }
    </script>
</head>

<body class="bg-light">
    <div class="login">
        <h1>Regisztráció</h1>
        <h5 id="error" style="color: red;">
            <?php
            if (isset($_GET['failed'])) {
                echo "Nem sikerült a regisztráció, lehet van már ilyen nevű felhasználó!";
            }
            ?>
        </h5>
        <form action="check.php" method="post" onsubmit="return formCheck()">
            <div class="form-group">
                <input id="name" name="name" type="text" class="form-control" placeholder="Felhasználónév" onchange="nameCheck()" onkeyup="nameCheck()">
            </div>
            <div class="form-group">
                <input id="password" name="password" type="password" class="form-control" placeholder="Jelszó" onchange="pwdCheck()" onkeyup="pwdCheck()">
            </div>
            <div class="form-group">
                <input id="password_again" type="password" class="form-control" placeholder="Jelszó mégegyszer" onchange="pwdCheckAgain()" onkeyup="pwdCheckAgain()">
            </div>
            <button type="submit" class="btn btn-primary" value="Regisztráció" name="register">Regisztráció</button>
            <a href="login.php" class="btn btn-primary">Bejelentkezés</a>
        </form>
    </div>
</body>

</html>