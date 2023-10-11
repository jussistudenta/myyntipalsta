<?php 
session_start();
// Määritä käyttäjä uloskirjautuneeksi.
session_destroy();

// Aloita uusi sessio, jotta voidaan määrittää flash-viesti.
session_start();

$_SESSION['flash'] = "Sinut on nyt kirjattu ulos.";
// Uudelleenohjaa etusivulle.
header("Location:index.php");
?>