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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Felhasználók</title>
    <script>
        function handleChange(user) {
            document.getElementById("create_form").style.display = "none";

            document.getElementById("id").value = user.id;
            document.getElementById("username").value = user.username;
            document.getElementById("role").value = user.role;

            document.getElementById("edit_form").style.display = "block";
            window.scrollTo(0, document.body.scrollHeight);
        }

        function hideForm() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "none";
        }

        function handleNewUser() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "block";
            window.scrollTo(0, document.body.scrollHeight);
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

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
            <?php if ($_SESSION['role'] === "admin") : ?>
                <li class="nav-item">
                    <a href="discs.php" class="nav-link">Lemezeim</a>
                </li>
                <li class="nav-item active">
                    <a href="users.php" class="nav-link">Felhasználók</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Kijelentkezés</a>
            </li>
        </ul>
    </nav>
    <div class="container-fluid">
        <h5 id="error" style="color: red;">
            <?php
            if (isset($_GET['update_failed'])) {
                echo "Nem sikerült a módosítás!";
            }
            if (isset($_GET['delete_failed'])) {
                echo "Nem sikerült a törlés!";
            }
            if (isset($_GET['insert_failed'])) {
                echo "Nem sikerült a létrehozás!";
            }
            ?>
        </h5>
        <h1>Felhasználók</h1>
        <table class="table col-12">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Felhasználónév</th>
                    <th scope="col">Jelszó</th>
                    <th scope="col">Szerepkör</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
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
                        <td><button class="btn btn-secondary" onclick='handleChange(<?php echo json_encode($row); ?>)'>Módosítás</button></td>
                        <td>
                            <form action="users.php" method="post">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="submit" value="Törlés" name="delete" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <input class="btn btn-info" type="button" onclick="handleNewUser()" value="&#10010 Új felhasználó">
        <div class="pt-3 pb-3 col-sm-12 col-md-6 col-lg-4" id="edit_form" style="display: none;">
            <h3>Módosítás</h3>
            <form action="users.php" method="post" onsubmit="return passwordCheck()">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="">Felhasználónév:</label>
                    <input class="form-control" type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="">Szerepkör:</label>
                    <select class="form-control" name="role" id="role">
                        <option value="user">Felhasználó</option>
                        <option value="admin">Adminisztrátor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Jelszó: </label>
                    <input class="form-control" type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="">Jelszó mégegyszer: </label>
                    <input class="form-control" type="password" id="password_again">
                </div>
                <input class="btn btn-info" type="submit" value="Mentés" name="save">
                <button type="button" class="btn btn-secondary" onclick="hideForm()">Mégse</button>
            </form>
        </div>
        <div class="pt-3 pb-3 col-sm-12 col-md-6 col-lg-4" id="create_form" style="display: none;">
            <h3>Új felhasználó létrehozása</h3>
            <form action="users.php" method="post" onsubmit="return passwordCheck()">
                <div class="form-group">
                    <label for="">Felhasználónév:</label>
                    <input class="form-control" type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="">Szerepkör:</label>
                    <select class="form-control" name="role">
                        <option value="user">Felhasználó</option>
                        <option value="admin">Adminisztrátor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Jelszó: </label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="">Jelszó mégegyszer: </label>
                    <input class="form-control" type="password" id="password_again" required>
                </div>
                <input class="btn btn-info" type="submit" value="Létrehozás" name="create">
                <button type="button" class="btn btn-secondary" onclick="hideForm()">Mégse</button>
            </form>
        </div>
    </div>
</body>

</html>