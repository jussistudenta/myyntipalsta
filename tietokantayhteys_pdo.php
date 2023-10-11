<?php 
// Yritä ottaa yhteyttä tietokantaan.
try {
    $conn = new PDO("mysql:host=localhost;dbname=myyntipalsta","asiakas", ")JiX]G[@I0S_2444");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Tietokantaan ei saada yhteyttä: ' . $e->getMessage());
} 
?>