<?php
session_start();

// Yritä ottaa yhteyttä tietokantaan.
include("tietokantayhteys_pdo.php");

try {
    $sql = $conn->prepare("INSERT INTO items(title, description, image, price, username) VALUES 
                (:title, :description, :image, :price, :username)");
    $sql->bindParam(':title', $otsikko);
    $sql->bindParam(':description', $kuvaus);
    $sql->bindParam(':image', $kuvatiedosto);
    $sql->bindParam(':price', $hinta);
    $sql->bindParam(':username', $_SESSION['user']);
    $sql->execute();
    header("Location: omat_ilmoitukset.php");
} catch (Exception $e) {
    echo ('Virhe lisättäessä uutta ilmoitusta: ' . $e->getMessage());
} ?>