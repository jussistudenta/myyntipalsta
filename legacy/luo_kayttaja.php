<?php
// Tämä tarvitaan, jotta sessiomuuttujia voidaan käyttää.
session_start();

include("html_alku.php");

// Tarkista, onko käyttäjä antanut käyttäjänimen ja salasanan. Jos ei ole
// tallenna virhe sessiomuuttujaan ja uudelleenohjaa rekisteröitymissivulle
// (joka näyttää sessiomuuttujassa olevan virheen).
if(!isset($_POST['username']) || !isset($_POST['password']) ||
$_POST['username'] == '' || $_POST['password'] == '') {
    $_SESSION['virhe'] = 'Ole hyvä ja täytä kaikki alla olevat kentät!';
    header('Location: register.php');
}

// Tarkista, ovatko Salasana- ja Salasana uudestaan -kentät samoja.
if($_POST['password'] != $_POST['confirm_password']) {
    $_SESSION['virhe'] = 'Salasanat eivät täsmää.';
}

// Yritä lisätä käyttäjä tietokantaan.
include('tietokantayhteys_mysqli.php');

try {
    $stmt = $conn->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    $username = $_POST['username'];
    // Salakirjoita salasana.
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt->execute();
} catch(Exception $e) {
    // Virheen sattuessa tallenna virheviesti sessiomuuttujaan ja uudelleenohjaa
    // rekisteröitymissivulle, joka näyttää sessiomuuttujaan tallennetun virheviestin.
    $_SESSION['virhe'] = $e->getMessage();
    header('Location: register.php');
}

echo("<h3 class=\mt-4\">Käyttäjä luotu onnistuneesti. Voit nyt kirjautua sisään luomillasi tunnuksilla.</h3>");

// Sulje kysely- ja tietokantayhteys.
$stmt->close();
$conn->close();

include("html_loppu.php");
?>