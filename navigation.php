<?php
if(!isset($_SESSION)) 
{ 
    session_start();
}

function valikkovalinta($teksti, $href) {
    echo('<li class="nav-item">');
    echo("<a class=\"nav-link text-light mx-4\" aria-current=\"page\" href=\"$href\">$teksti</a>");
    echo("</li>");
}
?>

<nav class="navbar navbar-expand-lg bg-dark shadow-lg">
    <div class="container">
        <a href="index.php"><img src="personal.png" width="50" height="50" class="m-2"></a>
        <!-- Sivuston nimi (navbar-brand) -->
        <a class="navbar-brand text-white" href="index.php">Myyntipalsta <?php 
if(isset($_SESSION['user'])) echo("(" . $_SESSION['user'] . ")");
?></a>

        <!-- Näytä valikko -painike responsiivisuutta varten -->
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <!-- Valikkovalinnat -->
                    <?php
                        valikkovalinta("Etusivu", "index.php");
                        // Kirjaudu sisään/Rekisteröidy on näkyvissä vain silloin, kun käyttäjä *ei ole*
                        // kirjautuneena sisään.
                        if(isset($_SESSION['user'])) {
                            // Lisää myynti-ilmoitus, Omat myynti-ilmoitukset ja Kirjaudu ulos -valinnat
                            // ovat näkyvissä vain silloin, kun käyttäjä on kirjautuneena sisään.
                            valikkovalinta("Lisää myynti-ilmoitus", "uusi_ilmoitus.php");
                            valikkovalinta("Omat myynti-ilmoitukset", "omat_ilmoitukset.php");
                            valikkovalinta("Omat yhteystiedot", "omat_yhteystiedot.php");
                            valikkovalinta("Kirjaudu ulos", "logout.php");
                            // echo('<a href="uusi_ilmoitus.php">Lisää myynti-ilmoitus</a>');
                            // echo('<a href="omat_ilmoitukset.php">Omat myynti-ilmoitukset</a>');
                            // echo('<a href="logout.php">Kirjaudu ulos</a>');
                        }
                        else {
                            // Jos käyttäjä ei ole kirjautuneena sisään, näytetään hänelle valinnat 
                            // Kirjaudu sisään ja Rekisteröidy.
                            valikkovalinta("Kirjaudu sisään", "kirjautumislomake.php");
                            valikkovalinta("Rekisteröidy", "register.php");
                            // echo('<a href="kirjautumislomake.php">Kirjaudu sisään</a>');
                            // echo('<a href="register.php">Rekisteröidy</a>');
                        }
                    ?>
                </ul>
            </div>
    </div>
</nav>