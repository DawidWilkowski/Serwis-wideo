<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <title>Wilkowski</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>

<BODY>
    <?php
    session_start();
    echo "<a href=\"logout.php\">Wyloguj</a>";
    echo "<br><a href=\"index2.php\">Powrót</a>";
    echo "<br><p style=\"color:red;font-size:15px;\">" . $_SESSION['error'] . "</p>";
    $_SESSION['error'] = "";
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
    $userid = $_SESSION['userid'];
    $songs = mysqli_query($link, "Select * from film ORDER BY title DESC;") or die("DB error: $dbname");
    $playlists = mysqli_query($link, "Select * from playlistname where idu=$userid ORDER BY name ASC;") or die("DB error: $dbname");;
    print "";
    print "<form method=\"post\" action=\"addplaylist.php\">";
    print "Dodaj nową playliste.<br>";
    print "<label for=\"plname\">Nazwa playlisty:</label>";
    print "<input type=\"text\" name=\"plname\"><br>";
    print "<label>Typ playlisty</label>";
    print "<select name=\"pltype\">
    <option value=\"0\">Prywatna</option>
    <option value=\"1\">Publiczna</option>
    </select>";
    print "<br><input type=\"submit\" value=\"Dodaj\"/>";
    print "</form>";


    print "<br>";
    print "<form method=\"post\" action=\"addmusic.php\">";
    print "Dodaj film do swojej playlisty.<br>";
    print "<label>Wybierz playliste:</label>";
    print "<select name=\"idpl\" style=\"min-width:100px;\">";
    while ($playlistsrow = mysqli_fetch_array($playlists)) {
        $idpl = $playlistsrow[0];
        $plname = $playlistsrow[2];
        print "<option value=\"$idpl\">$plname</option>";
    }
    print "</select>";
    print "<br><label>Wybierz film:</label>";
    print "<select name=\"ids\" style=\"min-width:100px;\">";
    while ($songsrow = mysqli_fetch_array($songs)) {
        $ids = $songsrow[0];
        $title = $songsrow[1];
        $musician = $songsrow[2];
        $datetime = $songsrow[3];
        $idu = $songsrow[4];
        $filename = $songsrow[5];
        $lyrics = $songsrow[6];
        $idmt = $songsrow[7];
        print "<option value=\"$ids\">$title by $musician</option>";
    }
    print "</select><br>";
    print "<input type=\"submit\" value=\"Dodaj\"/>";
    print "</form>";
    mysqli_close($link);
    ?>
</BODY>

</HTML>