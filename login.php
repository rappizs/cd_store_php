<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
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

<body>
    <h2>Bejelentkezés</h2>
    <h3 id="error" style="color: red;">
        <?php
        if (isset($_GET['nomatch'])) {
            echo "Hibás felhasználónév vagy jelszó! ";
        }
        if (isset($_GET['connectfail'])) {
            echo "Nem sikerült csatlakozni az adatbázishoz!";
        }
        ?>
    </h3>
    <h3 style="color: green;">
        <?php
        if (isset($_GET['registered'])) {
            echo "Sikeres regisztráció! :)";
        }
        ?>
    </h3>
    <form action="check.php" method="post" onsubmit="return blankcheck()">
        Felhasználónév: <input id="name" name="name" type="text"> <br>
        Jelszó: <input id="password" name="password" type="password"> <br>
        <input type="submit" value="Bejelentkezés" name="login">
    </form>
    <a href="register.php">Regisztráció</a>
</body>

</html>