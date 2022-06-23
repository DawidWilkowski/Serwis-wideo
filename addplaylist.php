<?php

declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wilkowski</title>
</HEAD>

<BODY>
    <?php
    session_start();
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

    $plname = $_POST['plname']; // login z formularza
    $plname = htmlentities($plname, ENT_QUOTES, "UTF-8");
    $pltype = $_POST['pltype']; // login z formularza
    // $pltype = htmlentities ($pltype, ENT_QUOTES, "UTF-8");
    $userid = $_SESSION['userid'];

    $pyt1 = mysqli_query($link, "SELECT * FROM playlistname WHERE name='$plname'");
    $rekord = mysqli_fetch_array($pyt1);
    if (!$rekord) {
        $pyt2 = mysqli_query($link, "INSERT INTO playlistname (idu, name, public) VALUES ($userid,'$plname',$pltype)");
        $_SESSION['error'] = "Dodano playliste";
        mysqli_close($link);
        header("Location: playlists.php");
        exit();
    } else {
        mysqli_close($link);
        $_SESSION['error'] = "Istnieje playlista o takiej nazwie.";
        header("Location: playlists.php");
        exit();
    }
    ?>
</BODY>

</HTML>