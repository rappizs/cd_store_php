<?php
//Ha nem POST a metodus akkor átirányítás
if ($_SERVER['REQUEST_METHOD'] !== "POST")
    header("location: login.php");

//Eldöntjük, hogy belépés vagy regisztráció
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    register($name, $password);
} elseif (isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    login($name, $password);
}

//Beléptető function
function login($username, $password)
{
    require('connect.php');
    $username = mysqli_real_escape_string($connection, $username);
    $password = sha1(mysqli_real_escape_string($connection, $password));
    $result = mysqli_query($connection, "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1");

    if (mysqli_num_rows($result) === 1) {
        session_start();
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        header("location: discs.php");
    } else {
        header("location: login.php?nomatch=true");
    }
}
//Regisztráló function
function register($username, $password)
{
    require('connect.php');
    //kiszedjük az esetleges ártalmas sql utasításokat
    $username = mysqli_real_escape_string($connection, $username);
    $password = sha1(mysqli_real_escape_string($connection, $password));

    $result = mysqli_query($connection, "INSERT INTO users(username, password) values('$username', '$password')");
    if (!$result) {
        header("location: register.php?failed=true");
    }else {
        header("location: login.php?registered=true");
    }
    
}
