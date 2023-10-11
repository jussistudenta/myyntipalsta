<?php

if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    // return 405 http status code
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
    // die("Pääsy evätty: CSRF-tokenia ei ole määritelty tai se on virheellinen!");
}
?>