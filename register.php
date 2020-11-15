<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <script>
        function blankcheck() {
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
            return true;
        }
    </script>
</head>

<body>
    <h3 id="error" style="color: red;">
        <?php 
        if (isset($_GET['failed'])) {
            echo "Nem sikerült a regisztráció, lehet van már ilyen nevű felhasználó!";
        }
        ?>
    </h3>
    <form action="check.php" method="post" onsubmit="return blankcheck()">
        Felhasználónév: <input id="name" name="name" type="text"> <br>
        Jelszó: <input id="password" name="password" type="password"> <br>
        Jelszó mégegyszer: <input id="password_again" type="password"> <br>
        <input type="submit" value="Regisztráció" name="register">
    </form>
    <a href="login.php">Bejelentkezés</a>
</body>

</html>