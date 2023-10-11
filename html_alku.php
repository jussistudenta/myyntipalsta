<?php
session_start();

// Virheilmoitukset päälle. Nämä ovat vain kehitystä varten.
// Kommentoi pois päältä tai poista myöhemmin, kun et enää tarvitse näitä (= tuotantokäyttö)!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myyntipalsta</title>

    <!-- Tarvitaan Bootstrap-kirjastoa varten. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <!-- Omat globaalit tyylimääritykset, joilla kustomoidaan Bootstrap-tyylejä -->
    <link rel="stylesheet" href="tyylit/globaalit.css">
</head>

<body class="bg-body-tertiary">

    <?php include('navigation.php') ?>

    <main class="container mx-auto" style="max-width: 1024px; min-height: 100vh">
        <br>

        <?php
        // Tämän avulla näytetään flash-viestit. Flash-viestit ovat viestejä, jotka näytetään
        // yhden kerran sivun latautuessa. Niiden avulla voidaan välittää viestejä sivulta toiselle.
        // Niitä voidaan käyttää ilmoitettaessa käyttäjälle erilaisia asioita siirryttäessä sivulta
        // toiselle.
        if (isset($_SESSION['flash']) && $_SESSION['flash'] != "") {
            echo ("<p>" . $_SESSION['flash'] . "</p>");

            $_SESSION['flash'] = "";
        }
        ?>
