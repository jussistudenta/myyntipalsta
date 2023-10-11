<?php
// Estä pääsy sivulle, jos käyttäjä ei ole kirjautunut sisään. Tämä estää tilanteen, jossa
// käyttäjä pääsee tälle sivulle kirjoittamalla sivun nimen suoraan selaimen osoitekenttään.
// route_protect.php sisältää myös session_start()-käskyn, jolla päästään käsiksi istunto-
// muuttujaan. Tästä syystä tämän tiedoston alussa ei ole session_start()-käskyä.
include('route_protect.php');
include("puhdista_input.php");
?>

<?php include('html_alku.php') ?>

<h2>Muokkaa myynti-ilmoitusta</h2>

<?php
// Onko käyttäjä lähettänyt pyynnön muokata ilmoitusta?
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("tarkista_csrf_token.php");

    $id = $_POST['id'];

    // Nämä pitää asettaa, koska ilmoituslomake.php-tiedosto käyttää näitä lomakkeen 
    // täyttämisessä.
    $otsikko = puhdista_input($_POST['otsikko']);
    $kuvaus = puhdista_input($_POST['kuvaus']);
    $hinta = puhdista_input($_POST['hinta']);
    $kuva = "";

    if (!isset($_POST['otsikko']) && !isset($_POST['kuvaus'])) {
        $virhe = "Virhe: Otsikko ja kuvaus-kenttä ovat pakollisia ja niitä ei ole määritelty.";
    } else {
        if ($_POST['otsikko'] == '' || $_POST['kuvaus'] == '') {
            $virhe = "Virhe: Otsikko ja kuvaus-kenttä ovat pakollisia eikä kumpikaan niistä voi olla tyhjä.";
        }
    }

    // Tarkista, että hinta on desimaaliluku.
    if (!filter_var($hinta, FILTER_VALIDATE_FLOAT) && filter_var($hinta, FILTER_VALIDATE_FLOAT) != 0) {
        $virhe = "Virhe: Hinta on virheellinen! Hinnan on oltava desimaaliluku";
    }

    if (!isset($virhe)) {
        // Lataa kuva palvelimelle vain siinä tapauksessa, että käyttäjä on valinnut uuden kuvan.
        if (isset($_FILES["fileToUpload"]["name"]) && $_FILES["fileToUpload"]["name"] != "") {
            include('upload_image.php');
        }

        $id = $_POST['id'];

        include('tietokantayhteys_pdo.php');

        // Yritä päivittää ilmoitus. WHERE-lauseessa määritellään, että id:n täytyy olla muokattavan
        // tuotteen id ja käyttäjänimen sen käyttäjän nimi, joka omistaa ilmoituksen. Tällä pyritään
        // estämään se, ettei ilkeämielinen käyttäjä pääsisi muokkaamaan toisen käyttäjän
        // ilmoitusta.
        try {
            $sql = $conn->prepare("UPDATE items SET title = :title, description = :description, price = :price WHERE id=:id AND username=:username");
            $sql->bindParam(':title', $otsikko);
            $sql->bindParam(':description', $kuvaus);
            $sql->bindParam(':price', $hinta);
            $sql->bindParam(':id', $id);
            $sql->bindParam(':username', $_SESSION['user']);
            $sql->execute();

            // Päivitä kuvatiedosto, jos se on vaihdettu ja ladattu palvelimelle onnistuneesti.
            if (isset($kuvatiedosto) && $kuvatiedosto != "") {
                // Poista edellisen kuvan kuvatiedosto palvelimelta.
                try {
                    $sql = $conn->prepare("SELECT image FROM items WHERE id=:id AND username=:username LIMIT 1");
                    $sql->bindParam(':id', $id);
                    $sql->bindParam(':username', $_SESSION['user']);
                    $sql->execute();
                    $edellinen_kuvatiedosto = $sql->fetch()[0];
                    if ($kuvatiedosto) {
                        unlink("kuvat/$edellinen_kuvatiedosto");
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }

                $sql = $conn->prepare("UPDATE items SET image=:image WHERE id=:id AND username=:username");
                $sql->bindParam(':image', $kuvatiedosto);
                $sql->bindParam(':id', $id);
                $sql->bindParam(':username', $_SESSION['user']);
                $sql->execute();
            }

            // echo("Tiedot päivitetty! ");
            $_SESSION['flash'] = "Ilmoituksen tiedot päivitetty onnistuneesti";
            if (isset($kuvan_lataus_virhe) && $kuvan_lataus_virhe != 0) {
                $_SESSION['flash'] = $_SESSION['flash'] . "Kuvan lataamisessa tapahtui virhe: $kuvan_lataus_virhe";
            }
            header("Location: omat_ilmoitukset.php");
        } catch (Exception $e) {
            die('Virhe muokattaessa ilmoitusta: ' . $e->getMessage());
        }
    }
} else {
    include("luo_csrf_token.php");

    // Käyttäjä ei ole lähettänyt pyyntöä päivittää ilmoitusta (= lähettänyt päivityslomaketta)
    // eli käyttäjä on napsauttanut "Muokkaa ilmoitusta"-linkkiä. Tällöin id on osa
    // URL-osoitetta, joten se löytyy $_GET-sanakirjasta.
    $id = $_GET['id'];

    // Yritä hakea ilmoituksen tiedot tietokannasta. Jos tietojen haku onnistuu, täytä lomakkeen
    // tiedot.
    include('tietokantayhteys_pdo.php');

    $stmt = $conn->prepare("SELECT id, title, description, image, price FROM items WHERE id=? AND username=?");
    $stmt->execute([$id, $_SESSION['user']]);
    $row = $stmt->fetch();

    // Jos ilmoitusta ei löydy, ohjaa etusivulle. Näin käy, jos käyttäjä syöttää
    // osoitekenttään URL:n, jolla hän pyrkii muokkaamaan ilmoitusta, joka ei kuulu hänelle.
    // Tällä siis pyritään estämään hakkerointiyritykset ja se, että käyttäjä tahallaan/vahingossa 
    // menisi muokkaamaan ilmoitusta, jota hän ei omista.
    if (!$row) {
        header('Location: index.php');
    }

    // Nämä pitää asettaa, koska ilmoituslomake.php-tiedosto käyttää näitä lomakkeen täyttämisessä.
    // Asetetaan muuttujien arvoiksi tietokannasta saadut arvot.
    $otsikko = $row['title'];
    $kuvaus = $row['description'];
    $hinta = $row['price'];
    $kuva = "";

    // Jos tuotteelle on asetettu kuva, tallenna se $kuva-muuttujaan.
    if (isset($row['image']) && $row['image'] != "") {
        $kuva = $row['image'];
    }
}
?>

<?php
if (isset($virhe)) {
    echo ("<p>$virhe</p>");
    $virhe = "";
}

// Tämä muuttuja pitää asettaa, jotta ilmoituslomake.php-sisällytys toimii oikein.
$lomake_action = "muokkaa_ilmoitusta.php";

include("ilmoituslomake.php");

include('html_loppu.php')
    ?>