<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>

<BODY>
    <?php
    $user = $_POST['user']; // login z formularza
    $user = htmlentities($user, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
    $pass = $_POST['pass']; // hasło z formularza
    $pass = htmlentities($pass, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
    $dbhost = "";
    $dbuser = "";
    $dbpassword = "";
    $dbname = "";
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
    $result = mysqli_query($link, "SELECT * FROM UsersLab5 WHERE username='$user'"); // wiersza, w którym login=login z formularza
    $rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
    session_start();
    if (!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
    {
        mysqli_close($link); // zamknięcie połączenia z BD
        $_SESSION['error'] = "Błąd logowania !";
        echo "Błąd logowania !"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
        header("Location: index.php");
        exit();
    } else { // jeśli $rekord istnieje
        if ($rekord['password'] == $pass) // czy hasło zgadza się z BD
        {
            $_SESSION['userid'] = $rekord['id'];
            echo "Logowanie Poprawne";
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = "" . $user;
            header("Location: index2.php");
            exit();
        } else {
            mysqli_close($link);
            $_SESSION['error'] = "Błąd logowania !";
            echo "Błąd logowania"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
            header("Location: index.php");
            exit();
        }
    }
    ?>
</BODY>

</HTML>