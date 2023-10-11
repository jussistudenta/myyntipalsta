<?php include('html_alku.php') ?>

<h2>Etusivu</h2>

<h3>Selaa myynti-ilmoituksia</h3>

<main>
<?php 
// Hae ilmoitukset tietokannasta ja listaa ne.
include("listaa_ilmoitukset.php");
?>
</main>

<?php include('html_loppu.php') ?>