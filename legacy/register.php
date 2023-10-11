<?php 
session_start();
?>

<?php include('html_alku.php') ?>

<h2>Rekisteröidy</h2>

<?php 

if(isset($_SESSION['virhe']) && $_SESSION['virhe'] != '') {
    if(str_starts_with($_SESSION['virhe'], 'Duplicate')) {
        echo('<p>Käyttäjätunnus on jo olemassa!</p>');
    } else {
        echo("<p>" . $_SESSION['virhe'] . "</p>");
    }
    $_SESSION['virhe'] = '';
}

?>

<form action="luo_kayttaja.php" method="POST">
    <label for="username">Käyttäjätunnus: </label>
    <input type="text" class="form-control mb-2 shadow-none" name="username" id="username" required>
    <label for="password">Salasana: </label>
    <input type="password" class="form-control mb-2 shadow-none" name="password" id="password" required>
    <label for="confirm_password">Salasana uudestaan: </label>
    <input type="password" class="form-control mb-2 shadow-none" name="confirm_password" id="confirm_password" required>
    <button class="btn btn-secondary">Rekisteröidy</button>
</form>

<?php include('html_loppu.php') ?>