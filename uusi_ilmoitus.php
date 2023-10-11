<?php
// Estä pääsy sivulle, jos käyttäjä ei ole kirjautunut sisään. Tämä estää tilanteen, jossa
// käyttäjä pääsee tälle sivulle kirjoittamalla sivun nimen suoraan selaimen osoitekenttään.
// route_protect.php sisältää myös session_start()-käskyn, jolla päästään käsiksi istunto-
// muuttujaan. Tästä syystä tämän tiedoston alussa ei ole session_start()-käskyä.
include('route_protect.php');

include("puhdista_input.php");
?>

<?php include('html_alku.php') ?>
<h2>Lisää myynti-ilmoitus</h2>

<?php
function virhe($virheviesti)
{
    echo ('<div class="text-danger">');
    echo ($virheviesti);
    echo ('</div>');
}

$hinta = "0,00";

// Onko käyttäjä lisäämässä ilmoitusta? Jos palvelupyyntö on POST, tiedämme, että käyttäjä
// on lähettänyt lomakkeen, jolla voi lisätä ilmoituksen (määritelty tämän tiedoston
// myöhemmässä vaiheessa)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("tarkista_csrf_token.php");
    
    $otsikko = puhdista_input($_POST['otsikko']);
    $kuvaus = puhdista_input($_POST['kuvaus']);
    $hinta = puhdista_input($_POST['hinta']);

    // Tarkista, että otsikko ja kuvaus (pakolliset kentät) on määritelty ja niiden arvo ei ole 
    // tyhjä merkkijono.
    if (empty($otsikko) || empty($kuvaus)) {
        virhe("Otsikko ja kuvaus ovat pakollisia kenttiä.");
    } else {
        // Tarkista, että hinta on desimaaliluku.
        if (!filter_var($hinta, FILTER_VALIDATE_FLOAT) && filter_var($hinta, FILTER_VALIDATE_FLOAT) != 0) {
            $virhe = "Virhe: Hinta on virheellinen!";
        } else {
            // Uploadaa kuva. upload_image.php-tiedostossa määritellään muuttujat $kuvatiedosto ja 
            // $kuvan_lataus_virhe, jota käytetään tässä tiedostossa.
            include('upload_image.php');

            // Yritä lisätä ilmoitusta tietokantaan.
            include('lisaa_ilmoitus_tietokantaan.php');
        }
    }
} else {
    include("luo_csrf_token.php");
}
?>
<hr>

<?php
// Tämä muuttuja pitää asettaa, jotta ilmoituslomake-sisällytys toimii oikein.
$lomake_action = "uusi_ilmoitus.php";

include("ilmoituslomake.php");
?>

<?php include('html_loppu.php'); ?>