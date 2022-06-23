<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <title>Wilkowski</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>

<BODY>
    <?php
    session_start();
    $user = $_POST['user']; // login z formularza
    $user = htmlentities($user, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
    $pass = $_POST['pass']; // hasło z formularza
    $pass = htmlentities($pass, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
    $pass2 = $_POST['pass2']; // hasło z formularza
    $pass2 = htmlentities($pass2, ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass2

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
    if (!$rekord) {
        if ($pass == $pass2) {
            $sql = "INSERT INTO UsersLab5 (`username`,`password`) VALUES ('$user','$pass')";
            if (mysqli_query($link, $sql)) {
                $result2 = mysqli_query($link, "SELECT * FROM UsersLab5 WHERE username='$user'");
                $rekord2 = mysqli_fetch_array($result2);
                echo "Poprawnie Dodano";
                $_SESSION['userid'] = $rekord2['id'];;
                mkdir("../z6/users/" . $user, 0777);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = "" . $user;
                header("Location: index2.php");
                exit();
            } else {
                echo "Błąd rejestracji";
                $_SESSION['error'] = "Błąd rejestracji";
                header("Location: rejestruj.php");
                exit();
            }
        } else { //hasła się nie zgadzają
            echo "Hasła się nie zgadzają.";
            $_SESSION['error'] = "Hasła się nie zgadzają.";
            header("Location: rejestruj.php");
            exit();
        }
    } else { //istnieje już taki użytkownik
        echo "Użytkownik już istnieje";
        $_SESSION['error'] = "Użytkownik już istnieje";
        header("Location: rejestruj.php");
        exit();
    }
    mysqli_close($link);


    ?>
</BODY>

</HTML>