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


    $idpl = $_POST['idpl']; // login z formularza
    $idpl = htmlentities($idpl, ENT_QUOTES, "UTF-8");
    $ids = $_POST['ids']; // login z formularza
    $ids = htmlentities($ids, ENT_QUOTES, "UTF-8");
    $userid = $_SESSION['userid'];

    $pyt1 = mysqli_query($link, "SELECT * FROM playlistdatabase WHERE idpl='$idpl' AND idf='$ids'");
    $rekord = mysqli_fetch_array($pyt1);
    if (!$rekord) {
        $pyt2 = mysqli_query($link, "INSERT INTO playlistdatabase (idpl,idf) VALUES ($idpl, $ids)");
        $_SESSION['error'] = "Dodano utwór";
        mysqli_close($link);
        header("Location: playlists.php");
        exit();
    } else {
        mysqli_close($link);
        $_SESSION['error'] = "Ten utwór jest już w playliście.";
        header("Location: playlists.php");
        exit();
    }
    ?>
</BODY>

</HTML>