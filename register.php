<?php
session_start();

include("puhdista_input.php");
?>

<?php include('html_alku.php') ?>

<h2>Rekisteröidy</h2>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("tarkista_csrf_token.php");

    // Validoi syötteet ja ilmoita mahdollisesta virheestä tallentamalla virhe 
    // $virhe-muuttujaan.
    $username = puhdista_input($_POST['username']);
    $password = puhdista_input($_POST['password']);
    $confirm_password = puhdista_input($_POST['confirm_password']);
    $phone = puhdista_input($_POST['phone']);
    $email = puhdista_input($_POST['email']);

    // Tarkista, onko käyttäjä täyttänyt kaikki lomakkeen kentät.
    if
    (
        empty($username) || empty($password) || empty($confirm_password) ||
        empty($phone) || empty($email)
    ) {
        $virhe = 'Ole hyvä ja täytä kaikki alla olevat kentät!';
    }

    if(!isset($virhe)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $virhe = 'Virheellinen sähköpostiosoite!';
        }
    }

    // Tarkista, ovatko Salasana- ja Salasana uudestaan -kentät samoja. Tämä tarkistus
    // tehdään vain, jos käyttäjä on täyttänyt kaikki lomakkeen kentät. Tästä syystä
    // tarkistetaan, onko $virhe-muuttujaa asetettu.
    if (!isset($virhe)) {
        if ($password != $confirm_password) {
            $virhe = 'Salasanat eivät täsmää.';
        }
    }

    // Yritä lisätä käyttäjä tietokantaan, jos syötteiden validointi onnistui (=
    // $virhe-muuttujaa ei ole asetettu).
    if (!isset($virhe)) {
        include('tietokantayhteys_mysqli.php');

        try {
            $stmt = $conn->prepare("INSERT INTO users(username, password, email, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $password_hash, $email, $phone);

            // Salakirjoita salasana.
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt->execute();
        } catch (Exception $e) {
            $virhe = $e->getMessage();

            if (str_starts_with($virhe, 'Duplicate')) {
                $virhe = '<p>Käyttäjätunnus on jo olemassa!</p>';
            }
        }

        // Sulje kysely- ja tietokantayhteys.
        $stmt->close();
        $conn->close();
    }

    if (!isset($virhe)) {
        echo ("<h3 class=\mt-4\">Käyttäjä luotu onnistuneesti. Voit nyt kirjautua sisään luomillasi tunnuksilla.</h3>");
        // Jos virhettä ei tapahtunut, ilmoita käyttäjälle flash-viestillä, että käyttäjän luominen onnistui ja uudelleenohjaa sisäänkirjautumissivulle.
        $_SESSION['flash'] = "Käyttäjän luominen onnistui. Voit nyt kirjautua sisään luomillasi tunnuksilla.";
        header('Location: kirjautumislomake.php');
    }
} else {
    include("luo_csrf_token.php");
}

if(isset($virhe)) echo("<p>" . $virhe . "</p>");
?>

<form action="register.php" method="POST">
    <?php include("csrf_token_input.php") ?>
    <!-- Käyttäjätunnus -->
    <label for="username">Käyttäjätunnus: </label>
    <input type="text" class="form-control mb-2 shadow-none" name="username" id="username"
        value="<?php if (isset($_POST['username']))
            echo ($_POST['username']); ?>">

    <!-- Sähköposti -->
    <label for="email">Sähköposti: </label>
    <input type="email" class="form-control mb-2 shadow-none" name="email" id="email"
        value="<?php if (isset($_POST['email']))
            echo ($_POST['email']); ?>">

    <!-- Puhelinnumero -->
    <label for="phone">Puhelinnumero: </label>
    <input type="tel" class="form-control mb-2 shadow-none" name="phone" id="phone"
        value="<?php if (isset($_POST['phone']))
            echo ($_POST['phone']); ?>">

    <!-- Salasana -->
    <label for="password">Salasana: </label>
    <input type="password" class="form-control mb-2 shadow-none" name="password" id="password"
        value="<?php if (isset($_POST['password']))
            echo ($_POST['password']); ?>">

    <!-- Salasanan vahvistus -->
    <label for="confirm_password">Salasana uudestaan: </label>
    <input type="password" class="form-control mb-2 shadow-none" name="confirm_password" id="confirm_password"
        value="<?php if (isset($_POST['confirm_password']))
            echo ($_POST['confirm_password']); ?>">

    <button class="btn btn-secondary">
        Rekisteröidy
    </button>
</form>

<?php include('html_loppu.php') ?>