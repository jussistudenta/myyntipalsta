<?php
// Estä pääsy sivulle, jos käyttäjä ei ole kirjautunut sisään. Tämä estää tilanteen, jossa
// käyttäjä pääsee tälle sivulle kirjoittamalla sivun nimen suoraan selaimen osoitekenttään.
// route_protect.php sisältää myös session_start()-käskyn, jolla päästään käsiksi istunto-
// muuttujaan. Tästä syystä tämän tiedoston alussa ei ole session_start()-käskyä.
include('route_protect.php');
?>

<?php
include("tietokantayhteys_pdo.php");

// Hae tuotteen kuva kuvatiedoston poistamista varten.
try {
    $sql = $conn->prepare("SELECT image FROM items WHERE id=:id AND username=:username LIMIT 1");
    $sql->bindParam(':id', $_GET['id']);
    $sql->bindParam(':username', $_SESSION['user']);
    $sql->execute();
    $result = $sql->fetch()[0];
    if($result) {
        $kuvatiedosto = $result;
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}

try {
    $sql = $conn->prepare("DELETE FROM items WHERE id=:id AND username=:username");
    $sql->bindParam(':id', $_GET['id']);
    $sql->bindParam(':username', $_SESSION['user']);
    $sql->execute();

    // Poista kuvatiedosto, jos tuotteelle oli asetettu sellainen.
    if(isset($kuvatiedosto)) {
        unlink("kuvat/$kuvatiedosto");
    }
    $_SESSION['viesti'] = 'Ilmoitus poistettu onnistuneesti!';
    header("Location: omat_ilmoitukset.php");
} catch (Exception $e) {
    echo ('Virhe poistettaessa ilmoitusta: ' . $e->getMessage());
}
?>