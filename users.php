<?php
session_start();
if ($_SESSION['logged_in'] !== true)
    header("location: login.php");

if ($_SESSION['role'] !== "admin")
    header("location: discs.php");

require('connect.php');
$userId = $_SESSION['user_id'];

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $result = null;
    if ($password === "") {
        $result = mysqli_query($connection, "UPDATE users SET username='$username', role='$role' WHERE id=$id");
    } else {
        $password = sha1($password);
        $result = mysqli_query($connection, "UPDATE users SET username='$username', role='$role', password='$password' 
            WHERE id=$id");
    }
    if (!$result)
        header("location: users.php?update_failed=true");
    else
        header("location: users.php");
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $deleteDiscs = mysqli_query($connection, "DELETE FROM discs WHERE user_id=$id");
    if (!$deleteDiscs)
        header("location: users.php?delete_failed=true");

    $result = mysqli_query($connection, "DELETE FROM users WHERE id=$id");
    if (!$result)
        header("location: users.php?delete_failed=true");
    else
        header("location: users.php");
}

if (isset($_POST['create'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $role = $_POST['role'];

    $result = mysqli_query($connection, "INSERT INTO users(username, password, role) 
        values('$username', '$password', '$role')");
    if (!$result)
        header("location: users.php?insert_failed=true");
    else
        header("location: users.php");
}


$result = mysqli_query($connection, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználók</title>
    <script>
        function handleChange(id, username, role) {
            document.getElementById("create_form").style.display = "none";

            document.getElementById("id").value = id;
            document.getElementById("username").value = username;
            document.getElementById("role").value = role;

            document.getElementById("edit_form").style.display = "block";
        }

        function hideForm() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "none";
        }

        function handleNewUser() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "block";
        }

        function passwordCheck() {
            const password = document.getElementById("password").value;
            const password_again = document.getElementById("password_again").value;

            if (password_again !== password) {
                document.getElementById("error").innerHTML = "Nem egyezik a két jelszó!";
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <nav>
        <?php if ($_SESSION['role'] === "admin") : ?>
            <a href="discs.php">Lemezeim</a>
            <a href="users.php">Felhasználók</a>
        <?php endif; ?>
        <a href="logout.php">Kijelentkezés</a>
    </nav>
    <h3 id="error" style="color: red;">
        <?php
        if ($_GET['update_failed'] == true) {
            echo "Nem sikerült a módosítás!";
        }
        if ($_GET['delete_failed'] == true) {
            echo "Nem sikerült a törlés!";
        }
        if ($_GET['insert_failed'] == true) {
            echo "Nem sikerült a létrehozás!";
        }
        ?>
    </h3>
    <h3>Felhasználók</h3>
    <table>
        <thead>
            <tr>
                <td>Felhasználónév</td>
                <td>Jelszó</td>
                <td>Szerepkör</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <!-- '?=' == php echo -->
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['password'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><button onclick="handleChange('<?= $row['id'] ?>','<?= $row['username'] ?>', 
                        '<?= $row['role'] ?>')">Módosítás</button></td>
                    <td>
                        <form action="users.php" method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="submit" value="Törlés" name="delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="button" onclick="handleNewUser()" value="&#10010 Új felhasználó">
    <div id="edit_form" style="display: none;">
        <h3>Módosítás</h3>
        <form action="users.php" method="post" onsubmit="return passwordCheck()">
            <input type="hidden" name="id" id="id">
            Felhasználónév: <input type="text" id="username" name="username" required> <br>
            Szerepkör: <select name="role" id="role">
                <option value="user">Felhasználó</option>
                <option value="admin">Adminisztrátor</option>
            </select> <br>
            Jelszó: <input type="password" id="password" name="password"> <br>
            Jelszó mégegyszer: <input type="password" id="password_again"> <br>
            <input type="submit" value="Mentés" name="save">
        </form>
        <button onclick="hideForm()">Mégse</button>
    </div>
    <div id="create_form" style="display: none;">
        <h3>Új felhasználó létrehozása</h3>
        <form action="users.php" method="post" onsubmit="return passwordCheck()">
            Felhasználónév: <input type="text" name="username" required> <br>
            Szerepkör: <select name="role">
                <option value="user">Felhasználó</option>
                <option value="admin">Adminisztrátor</option>
            </select> <br>
            Jelszó: <input type="password" id="password" name="password" required> <br>
            Jelszó mégegyszer: <input type="password" id="password_again" required> <br>
            <input type="submit" value="Létrehozás" name="create">
        </form>
        <button onclick="hideForm()">Mégse</button>
    </div>
</body>

</html>