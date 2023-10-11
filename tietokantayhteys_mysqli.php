<?php
// Virheilmoitukset päälle.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

$user = "asiakas";
$server = "localhost";
$salasana = ")JiX]G[@I0S_2444";
$db = "myyntipalsta";
$conn = new mysqli($server, $user, $salasana, $db);

if($conn->connect_error) {
    die("Tietokantayhteys epäonnistui" . $conn->connect_error);
}
?>