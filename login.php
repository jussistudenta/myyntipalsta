<?php 
session_start();

// Tämä funktio tallentaa kirjautumisvirheen istuntomuuttujaan, jota kirjautumislomake.php
// käyttää virheviestin näyttämiseen sekä uudelleenohjaa takaisin kirjautumissivulle
// (kirjautumislomake.php)
function kirjautumisvirhe($virheviesti) {
    $_SESSION['kirjautumisvirhe'] = $virheviesti;
    header('Location: kirjautumislomake.php');
}

// Estä CSRF-hyökkäys
include("tarkista_csrf_token.php");

// Yritä ottaa yhteyttä tietokantaan.
try {
    $conn = new PDO("mysql:host=localhost;dbname=myyntipalsta","asiakas", ")JiX]G[@I0S_2444");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    kirjautumisvirhe('Tietokantaan ei saada yhteyttä: ' . $e->getMessage());
}

// Hae tietokannasta käyttäjän salakirjoitettu salasana.
$sql = $conn->prepare("select password from users where username = :username limit 1");
$sql->bindParam(':username', $_POST['username']);
$sql->execute();
// Tarkista, löytyikö käyttäjän salasanaa. Jos ei löytynyt, tiedämme, ettei käyttäjää ole vielä
// rekisteröity tietokataan.
if($sql->rowCount() == 1) {
    // Tarkista salasanan oikeellisuus.
    $password_hash = $sql->fetch()['password'];
    if(password_verify($_POST['password'], $password_hash)) {
        // Salasana on oikein. Tallenna käyttäjänimi sessiomuuttujaan sisäänkirjautumisen
        // merkiksi ja uudelleenohjaa Omat ilmoitukset -sivulle.
        $_SESSION['user'] = $_POST['username'];
        header('Location: omat_ilmoitukset.php');
    } else {
        kirjautumisvirhe('Virheellinen salasana');
    }
} else {
    kirjautumisvirhe('Käyttäjää ei löydy!');
}
?>