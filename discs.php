<?php
session_start();
if ($_SESSION['logged_in'] !== true)
    header("location: login.php");

require('connect.php');
$userId = $_SESSION['user_id'];

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $album = $_POST['album'];
    $artist = $_POST['artist'];
    $style = $_POST['style'];
    $year = $_POST['year'];
    $song_count = $_POST['song_count'];

    $result = mysqli_query($connection, "UPDATE discs SET album='$album', artist='$artist', style='$style', year=$year, song_count=$song_count 
        WHERE id=$id and user_id=$userId");
    if (!$result)
        header("location: discs.php?update_failed=true");
    else
        header("location: discs.php");
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $result = mysqli_query($connection, "DELETE FROM discs WHERE id=$id and user_id=$userId");
    if (!$result)
        header("location: discs.php?delete_failed=true");
    else
        header("location: discs.php");
}

if (isset($_POST['create'])) {
    $id = $_POST['id'];
    $album = $_POST['album'];
    $artist = $_POST['artist'];
    $style = $_POST['style'];
    $year = $_POST['year'];
    $song_count = $_POST['song_count'];

    $result = mysqli_query($connection, "INSERT INTO discs(album, artist, style, `year`, song_count, `user_id`) 
        values('$album', '$artist', '$style', $year, $song_count, $userId)");
    if (!$result)
        header("location: discs.php?insert_failed=true");
    else
        header("location: discs.php");
}


$result = mysqli_query($connection, "SELECT * FROM discs WHERE user_id='$userId'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Lemezek</title>
    <script>
        function handleChange(disc) {
            document.getElementById("create_form").style.display = "none";

            document.getElementById("artist").value = disc.artist;
            document.getElementById("album").value = disc.album;
            document.getElementById("artist").value = disc.artist;
            document.getElementById("style").value = disc.style;
            document.getElementById("year").value = disc.year;
            document.getElementById("song_count").value = disc.song_count;
            document.getElementById("id").value = disc.id;

            document.getElementById("edit_form").style.display = "block";
        }

        function hideForm() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "none";
        }

        function handleNewDisc() {
            document.getElementById("edit_form").style.display = "none";
            document.getElementById("create_form").style.display = "block";
        }
    </script>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
            <?php if ($_SESSION['role'] === "admin") : ?>
                <li class="nav-item active">
                    <a href="discs.php" class="nav-link">Lemezeim</a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link">Felhasználók</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Kijelentkezés</a>
            </li>
        </ul>
    </nav>
    <div class="container-fluid">
        <h5 style="color: red;">
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
        <h1>Lemezek</h1>
        <table class="table col-12">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Album</th>
                    <th scope="col">Előadó</th>
                    <th scope="col">Stílus</th>
                    <th scope="col">Évszám</th>
                    <th scope="col">Zeneszámok</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <!-- '?=' == php echo -->
                        <td><?= $row['album'] ?></td>
                        <td><?= $row['artist'] ?></td>
                        <td><?= $row['style'] ?></td>
                        <td><?= $row['year'] ?></td>
                        <td><?= $row['song_count'] ?></td>
                        <td><button class="btn btn-secondary" onclick='handleChange(<?php echo json_encode($row); ?>)'>Módosítás</button></td>
                        <td>
                            <form action="discs.php" method="post">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="submit" value="Törlés" name="delete" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <input class="btn btn-info" type="button" onclick="handleNewDisc()" value="&#10010 Új lemez">
        <div class="pt-3 col-sm-12 col-md-6 col-lg-4" id="edit_form" style="display: none;">
            <h3>Módosítás</h3>
            <form action="discs.php" method="post">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="">Album: </label>
                    <input type="text" class="form-control" id="album" name="album" required>
                </div>
                <div class="form-group">
                    <label for=""> Előadó:</label>
                    <input type="text" class="form-control" id="artist" name="artist" required>
                </div>
                <div class="form-group">
                    <label for=""> Stílus: </label>
                    <input type="text" class="form-control" id="style" name="style" required>
                </div>
                <div class="form-group">
                    <label for="">Év: </label>
                    <input type="number" class="form-control" id="year" name="year" required>
                </div>
                <div class="form-group">
                    <label for="">Zeneszám: </label>
                    <input type="number" class="form-control" id="song_count" name="song_count" required>
                </div>
                <input class="btn btn-info" type="submit" value="Mentés" name="save">
                <button type="button" class="btn btn-secondary" onclick="hideForm()">Mégse</button>
            </form>
        </div>
        <div class="pt-3 col-sm-12 col-md-6 col-lg-4" id="create_form" style="display: none;">
            <h3>Új lemez létrehozása</h3>
            <form action="discs.php" method="post">
                <div class="form-group">
                    <label for="">Album: </label>
                    <input class="form-control" type="text" name="album" required>
                </div>
                <div class="form-group">
                    <label for=""> Előadó:</label>
                    <input class="form-control" type="text" name="artist" required>
                </div>
                <div class="form-group">
                    <label for=""> Stílus: </label>
                    <input class="form-control" type="text" name="style" required>
                </div>
                <div class="form-group">
                    <label for="">Év: </label>
                    <input class="form-control" type="number" name="year" required>
                </div>
                <div class="form-group">
                    <label for="">Zeneszám: </label>
                    <input class="form-control" type="number" name="song_count" required>
                </div>
                <input class="btn btn-info" type="submit" value="Létrehozás" name="create">
                <button class="btn btn-secondary" type="button" onclick="hideForm()">Mégse</button>
            </form>
        </div>
    </div>
</body>

</html>