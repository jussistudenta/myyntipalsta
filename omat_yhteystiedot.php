<?php 
session_start();

include("route_protect.php");
include("puhdista_input.php");
?>

<?php include('html_alku.php') ?>

<h2>Omat yhteystiedot</h2>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Estä CSRF-hyökkäys
    include("tarkista_csrf_token.php");

    // Pyynnön metodi on POST, joten tiedämme, että käyttäjä on lähettänyt Päivitä tiedot 
    // -lomakkeen.
    // Validoi syötteet ja ilmoita mahdollisesta virheestä tallentamalla virhe 
    // $virhe-muuttujaan.

    $email = puhdista_input($_POST['email']);
    $phone = puhdista_input($_POST['phone']);

    // Tarkista, onko käyttäjä täyttänyt kaikki lomakkeen kentät.
    if(empty($email) || empty($phone)) {
        $virhe = 'Ole hyvä ja täytä kaikki alla olevat kentät!';
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $virhe = 'Sähköpostiosoite on virheellinen!';
    }

    // Yritä päivittää käyttäjän tiedot tietokantaan, jos syötteiden validointi onnistui (=
    // $virhe-muuttujaa ei ole asetettu).
    if(!isset($virhe)) {
        include('tietokantayhteys_mysqli.php');

        try {
            $stmt = $conn->prepare("UPDATE users SET email=?, phone=? WHERE username=?");
            $stmt->bind_param("sss", $email, $phone, $_SESSION['user']);
    
            $stmt->execute();
        } catch(Exception $e) {
            $virhe = $e->getMessage();
        }
    
        // Sulje kysely- ja tietokantayhteys.
        $stmt->close();
        $conn->close();
    
        if(!isset($virhe)) {
            echo("<strong class=\mt-4\">Käyttäjän yhteystiedot päivitetty onnistuneesti.</strong>");
            // Jos virhettä ei tapahtunut, keskeytä skriptin suoritus, koska 
            // päivityslomaketta ei näytetä jos käyttäjän tietojen päivittäminen onnistui.
            die();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    include("luo_csrf_token.php");
    
    // Käyttäjä on saapunut sivulle napsauttamalla Omat tiedot -linkkiä.

    // Hae käyttäjän tiedot tietokannasta.
    include('tietokantayhteys_pdo.php');

    $stmt = $conn->prepare("SELECT email, phone FROM users WHERE username=?");
    $stmt->execute([$_SESSION['user']]);
    $row = $stmt->fetch();
    $email = $row['email'];
    $phone = $row['phone'];
}
?>

<?php if(isset($virhe)) echo("<p>" . $virhe . "</p>") ?>

<form action="omat_yhteystiedot.php" method="POST">
    <!-- CSRF-token -->
    <?php include("csrf_token_input.php") ?>
    <!-- Sähköposti -->
    <label for="email">Sähköposti: </label>
    <input type="email" class="form-control mb-2 shadow-none" name="email" id="email" 
        value="<?php echo($email) ?>">

    <!-- Puhelinnumero -->
    <label for="phone">Puhelinnumero: </label>
    <input type="tel" class="form-control mb-2 shadow-none" name="phone" id="phone" 
        value="<?php echo($phone) ?>">

    <button class="btn btn-secondary">
        Päivitä tiedot
    </button>
</form>

<?php include('html_loppu.php') ?>