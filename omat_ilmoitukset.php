<?php 
// Estä pääsy sivulle, jos käyttäjä ei ole kirjautunut sisään. Tämä estää tilanteen, jossa
// käyttäjä pääsee tälle sivulle kirjoittamalla sivun nimen suoraan selaimen osoitekenttään.
include('route_protect.php');
$_SESSION['ladattu_kuva'] = '';
?>

<?php include('html_alku.php') ?>
<?php 
if(isset($_SESSION['viesti']) && $_SESSION['viesti'] != '') {
    echo("<p>" . $_SESSION['viesti'] . "</p>");
    $_SESSION['viesti'] = '';
}
?>

<p>Hei, <?php echo($_SESSION['user']); ?>! Alla näet omat ilmoituksesi.</p>

<h2>Omat ilmoitukset</h2>

<?php include("listaa_omat_ilmoitukset.php"); ?>

<?php include('html_loppu.php') ?>