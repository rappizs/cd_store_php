<?php
$connection = mysqli_connect('localhost', 'root', 'root', 'cd_store');
if (!$connection)
    header("location: login.php?connectfail=true");