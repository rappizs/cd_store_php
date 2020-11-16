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

<body>
    <nav>
        <?php if ($_SESSION['role'] === "admin") : ?>
            <a href="discs.php">Lemezeim</a>
            <a href="users.php">Felhasználók</a>
        <?php endif; ?>
        <a href="logout.php">Kijelentkezés</a>
    </nav>
    <h3 style="color: red;">
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
    </h3>
    <h3>Lemezek</h3>
    <table>
        <thead>
            <tr>
                <td>Album</td>
                <td>Előadó</td>
                <td>Stílus</td>
                <td>Évszám</td>
                <td>Zeneszámok</td>
                <td></td>
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
                    <td><button onclick='handleChange(<?php echo json_encode($row); ?>)'>Módosítás</button></td>
                    <td>
                        <form action="discs.php" method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="submit" value="Törlés" name="delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="button" onclick="handleNewDisc()" value="&#10010 Új lemez">
    <div id="edit_form" style="display: none;">
        <h3>Módosítás</h3>
        <form action="discs.php" method="post">
            <input type="hidden" name="id" id="id">
            Album: <input type="text" id="album" name="album" required> <br>
            Előadó: <input type="text" id="artist" name="artist" required> <br>
            Stílus: <input type="text" id="style" name="style" required> <br>
            Év: <input type="number" id="year" name="year" required> <br>
            Zeneszám: <input type="number" id="song_count" name="song_count" required> <br>
            <input type="submit" value="Mentés" name="save">
        </form>
        <button onclick="hideForm()">Mégse</button>
    </div>
    <div id="create_form" style="display: none;">
        <h3>Új lemez létrehozása</h3>
        <form action="discs.php" method="post">
            Album: <input type="text" name="album" required> <br>
            Előadó: <input type="text" name="artist" required> <br>
            Stílus: <input type="text" name="style" required> <br>
            Év: <input type="number" name="year" required> <br>
            Zeneszám: <input type="number" name="song_count" required> <br>
            <input type="submit" value="Létrehozás" name="create">
        </form>
        <button onclick="hideForm()">Mégse</button>
    </div>
</body>

</html>