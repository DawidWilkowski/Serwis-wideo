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
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

    session_start();
    $title = $_POST['title']; // login z formularza
    $title = htmlentities($title, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $director = $_POST['director']; // login z formularza
    $director = htmlentities($director, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $subtitles = $_POST['subtitles']; // login z formularza
    $subtitles = htmlentities($subtitles, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej
    $idft = $_POST['idft']; // login z formularza
    $idft = htmlentities($idft, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej

    if ($subtitles == "") $subtitles = "NONE";

    $_SESSION['z6title'] = $title;
    $_SESSION['z6director'] = $director;
    $_SESSION['z6subtitles'] = $subtitles;
    $_SESSION['z6idft'] = $idft;


    $usernameid = mysqli_query($link, "SELECT id FROM UsersLab5 where username=\"" . $_SESSION['username'] . "\";") or die("DB error: $dbname");
    $row = mysqli_fetch_array($usernameid);
    $idu = $row[0];

    $filename = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));

    $target_dir = "movies";
    $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists 
    if (file_exists($target_file)) {
        echo "Plik już istnieje.";
        $_SESSION['error'] = "Plik juz istnieje";
        $uploadOk = 0;
    }

    // Allow certain file formats 
    if ($imageFileType != "mp4") {
        echo "Tylko pliki mp4 ";
        $_SESSION['error'] = "Tylko pliki mp4";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error 
    if ($uploadOk == 0) {
        echo "Nie przesłano";
        header('Location: select.php');
        exit();
    } else // if everything is ok, try to upload file 
    {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            print "Plik " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " przesłano.";
            $_SESSION['error'] = "Plik " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " przesłano. "/*.$target_file*/;
            $result = mysqli_query($link, "INSERT INTO film (`title`, `director` , `idu`, `filename`, `subtitles`, `idft`) 
                VALUES ('$title', '$director', $idu, '$filename' , '$subtitles', $idft);") or die("DB error: $dbname");
            mysqli_close($link);
            $_SESSION['z6title'] = "";
            $_SESSION['z6director'] = "";
            $_SESSION['z6subtitles'] = "";
            $_SESSION['z6idft'] = "";
            header('Location: index2.php');
            exit();
        } else {
            echo "Doszło do błędu przy przesyłaniu.";
            $_SESSION['error'] = "Doszło do błędu przy przesyłaniu.";

            header('Location: select.php');
            exit();
        }
    }
    ?>
</BODY>

</HTML>