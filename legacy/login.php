<?php 
session_start();

// Määritä käyttäjä sisäänkirjautuneeksi. Tämä on vain testausta varten. Myöhemmin on tietenkin
// tarkoitus lisätä oikea sisäänkirjautuminen (lomake, käyttäjän tunnistaminen yms.)
// $_SESSION['user'] = 'test_user';
// header("location:index.php");

try {
    $conn = new PDO("mysql:host=localhost;dbname=myyntipalsta","asiakas", ")JiX]G[@I0S_2444");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo '<br>Yhteys epäonnistui: ' . $e->getMessage();
}

echo("<p>Username: " . $_POST['username'] . "</p>");
echo("<p>Password: " . $_POST['password'] . "</p>");

echo "<br>" . $password . "<br>";

$sql = $conn->prepare("select password from users where username = :username limit 1");
$sql->bindParam(':username', $_POST['username']);
$sql->execute();
if($sql->rowCount() == 1) {
    echo('Käyttäjä löytyi!');
    $password_hash = $sql->fetch()['password'];
    if(password_verify($_POST['password'], $password_hash)) {
        echo("Sisäänkirjautuminen onnistui!");
        $_SESSION['user'] = $_POST['username'];
        header('Location: index.php');
    } else {
        $_SESSION['kirjautumisvirhe'] = "Virheellinen salasana!";
        header('Location: kirjautumislomake.php');
    }
} else {
    $_SESSION['kirjautumisvirhe'] = "Käyttäjää ei löydy!";
    header('Location: kirjautumislomake.php');
}
?>