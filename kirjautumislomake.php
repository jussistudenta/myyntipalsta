<?php
/*
Tämä tiedosto sisältää kirjautumislomakkeen, jolla käyttäjä voi kirjautua sisään.
*/
?>

<?php 
include('html_alku.php');
include("luo_csrf_token.php");
?>

<h2>Kirjaudu</h2>

<?php
// login.php-tiedostossa, jossa suoritetaan sisäänkirjautuminen, asetetaan virhetilanteissa
// sessiomuuttuja "kirjautumisvirhe". Tämän avulla voimme tulostaa kirjautumisvirheen
// sellaisen sattuessa.
if(isset($_SESSION['kirjautumisvirhe']) && $_SESSION['kirjautumisvirhe'] != '') {
    echo("<p>Virhe! " . $_SESSION['kirjautumisvirhe'] . "</p>");
    $_SESSION['kirjautumisvirhe'] = '';
}
?>

<!-- Kirjautumislomake. Sisäänkirjautuminen suoritetaan login.php-tiedostossa. 
Jotta sisäänkirjautuminen onnistuu, pitää lomakkeiden kenttien nimet olla username ja password, koska login.php käyttää näitä nimiä. -->
<form action="login.php" method="POST">
    <?php include("csrf_token_input.php") ?>
    <label for="username">Käyttäjätunnus: </label>
    <input type="text" class="form-control mb-2" name="username" id="username" required>

    <label for="password">Salasana: </label>
    <input type="password" class="form-control mb-4" name="password" id="password" required>

    <button class="btn btn-secondary">Kirjaudu sisään</button>
</form>

<?php include('html_loppu.php') ?>