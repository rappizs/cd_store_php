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
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        function blankcheck() {
            const name = document.getElementById("name").value;
            const password = document.getElementById("password").value;

            if (name === "" || password === "") {
                document.getElementById("error").innerHTML = "Töltsd ki mindkét mezőt a belépéshez!";
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="bg-light">
    <div class="login">
        <h1>Bejelentkezés</h1>
        <h5 id="error" style="color: red;">
            <?php
            if (isset($_GET['nomatch'])) {
                echo "Hibás felhasználónév vagy jelszó! ";
            }
            if (isset($_GET['connectfail'])) {
                echo "Nem sikerült csatlakozni az adatbázishoz!";
            }
            ?>
        </h5>
        <h3 style="color: green;">
            <?php
            if (isset($_GET['registered'])) {
                echo "Sikeres regisztráció! :)";
            }
            ?>
        </h3>
        <form action="check.php" method="post" onsubmit="return blankcheck()">
            <div class="form-group">
                <input id="name" name="name" type="text" class="form-control" placeholder="Felhasználónév">
            </div>
            <div class="form-group">
                <input id="password" name="password" type="password" class="form-control" placeholder="Jelszó">
            </div>
            <button type="submit" value="Bejelentkezés" name="login" class="btn btn-primary">Bejelentkezés</button>
            <a href="register.php" class="btn btn-primary">Regisztráció</a>
        </form>
    </div>
</body>

</html>