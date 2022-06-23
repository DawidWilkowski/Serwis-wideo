<?php

declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wilkowski</title>
    </script>
</HEAD>

<BODY>
    <?php
    session_start();
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$connection) {
        echo " MySQL Connection error." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
    if (!$_SESSION['loggedin'] == "true") {
        $_SESSION['error'] = "Nie zalogowano";
        header('Location: index.php');
        exit();
    }
    echo "<a href=\"logout.php\">Wyloguj</a>";
    print "<br>Witaj: " . $_SESSION['username'];
    print "<hr>";
    ?>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <?php echo "<p style=\"color:red;font-size:15px;\">" . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = ""; ?>
        Dodaj film<br><input type="file" name="fileToUpload" id="fileToUpload"><br><br>
        <label for="title">Tytuł:</label><br>
        <input type="text" name="title" required><br>
        <label for="director">Reżyser:</label><br>
        <input type="text" name="director" required><br>
        <label for="subtitles">Napisy:</label><br>
        <input type="text" name="subtitles"><br>
        <label for="idft">Rodzaj filmu:</label><br>
        <select name="idft">
            <option value="1">document</option>
            <option value="2">reportaż</option>
            <option value="3">publicystyka</option>
            <option value="4">film akcji</option>
            <option value="5">sci-fi</option>
            <option value="6">horror</option>
            <option value="7">familijny</option>
            <option value="8">przyrodniczy</option>
            <option value="9">koncert</option>
            <option value="10">animowany</option>
            <option value="11" selected>Other</option>
        </select>

        <input type="submit" value="Upload" name="submit">
    </form>
    <BR>
    <hr>







    <a href="playlists.php">Utwórz playliste / Dodaj film do playlisty</a><br>

    <?php
    print "<br><br>Wybierz playliste";
    $userid = $_SESSION['userid'];
    print "<br><form method=\"post\" action=\"playpl.php\">
    <select name=\"idpl\">";
    print "$userid";
    $playlists = mysqli_query($connection, "Select * from playlistname where idu=$userid ORDER BY name ASC;") or die("DB error: $dbname 1");
    $playlists2 = mysqli_query($connection, "Select * from playlistname where idu!=$userid AND public=1 ORDER BY name ASC;") or die("DB error: $dbname 2");
    while ($row = mysqli_fetch_array($playlists)) {
        $idpl = $row[0];
        $plname = $row[2];
        print "<option value=\"$idpl\">$plname</option>";
    }
    while ($row = mysqli_fetch_array($playlists2)) {
        $idpl = $row[0];
        $plname = $row[2];
        print "<option value=\"$idpl\">$plname</option>";
    }

    print "</select>

    <input type=\"submit\" value=\"Odtwórz\"/>
    </form>";
    ?>
    <?php echo "<p style=\"color:red;font-size:15px;\">" . $_SESSION['error'] . "</p>";
    $_SESSION['error'] = ""; ?>
    <?php
    print "<hr>";
    $result = mysqli_query($connection, "Select * from film ORDER BY datetime DESC") or die("DB error: $dbname");
    print "Wszystkie filmy<br>";
    print "<TABLE CELLPADDING=5 BORDER=1>";

    print "<TR><TD>Tytuł</TD><TD>Autor</TD><TD>Utwór</TD></TR>\n";
    while ($row = mysqli_fetch_array($result)) {
        $idf = $row[0];
        $title = $row[1];
        $director = $row[2];
        $datetime = $row[3];
        $idu = $row[4];
        $filename = $row[5];
        $subtitles = $row[6];
        $idft = $row[7];
        print "<TR>";
        print "<TD>$title</TD>";
        print "<TD>$director</TD>";
        print "<TD>";
        print "<video id=\"video\" controls width='400'>
                    <source src=\"movies/$filename\">
                        Your browser does not support the video element.
                </video>";
        print "</TD>";
        print "</TR>\n";
    }
    print "</TABLE>";
    mysqli_close($connection);
    ?>
    <script>
        var vid = document.getElementById("video");
        vid.volume = 0.2;
    </script>
</BODY>

</HTML>