<hr>
<?php
// Luodaan tietokantayhteys ($conn-muuttuja).
include('tietokantayhteys_mysqli.php');

// Haetaan kaikki myynti-ilmoitukset tietokannasta. Järjestetään id:n mukaan laskevasti, jolloin
// saadaan ilmoitukset uusimmasta vanhimpaan (uusimmissa ilmoituksissa on suurempi id
// kuin vanhemmissa).
$stmt = $conn->prepare("SELECT id, title, description, price, image, username, date_added FROM items ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!-- Listaa ilmoitukset -->
<?php 
    if(mysqli_num_rows($result) == 0) {
        echo("Palvelussa ei ole vielä yhtäkään ilmoitusta :( Toivotaan, että joku pian lisää ilmoituksen.");
    }

    foreach($result as $rivi) { ?>
    <?php
        // Määritä muuttujat tuotteen tiedoille. Muuta samalla tiedoissa mahdollisesti olevat
        // HTML-tagit HTML-entiteeteiksi. Tällä estetään hyökkäys tietoihin upotetun
        // HTML-koodin avulla.
        $otsikko = htmlspecialchars($rivi['title']);
        $kuvaus = htmlspecialchars($rivi['description']);
        // Aseta hinnan numeroformaatiksi kaksi desimaalia ja erottimeksi pilkku.
        $hinta = number_format((float)$rivi['price'], 2, ',', '');
        $kayttaja = $rivi['username'];

        $lisatty = $rivi['date_added'];
        // Näytä päivämäärä suomalaisessa muodossa.
        // Erottele päivämäärän osat ja muuta niiden järjestystä.
        $pvm_osat = explode("-", $lisatty);
        $lisatty = "$pvm_osat[2].$pvm_osat[1].$pvm_osat[0]";

        // Jos tuotteelle ei ole asetettu kuvaa, näytetään "Ei kuvaa" -kuva, joka haetaan
        // placehold.co-sivustolta.
        $kuvatiedosto = "https://placehold.co/400x300?text=Ei+kuvaa";

        // Jos tuotteelle on asetettu kuva, sijoitetaan $kuvatiedosto-muuttujaan kuvan
        // tiedosto.
        if($rivi['image'] != "") $kuvatiedosto = "kuvat/" . $rivi['image'];

        // Hae ilmoituksen lisänneen käyttäjän sähköposti ja puhelinnumero. Nämä näytetään
        // Ota yhteyttä -osiossa.
        include('tietokantayhteys_pdo.php');

        $stmt = $conn->prepare("SELECT email, phone FROM users WHERE username=?");
        $stmt->execute([$kayttaja]);
        $kayttajan_tiedot = $stmt->fetch();

        $sposti = $kayttajan_tiedot['email'];
        $puh = htmlspecialchars($kayttajan_tiedot['phone']);
    ?>
    <div class="card mb-3">
        <div class="row g-0">
            <!-- Tuotteen kuvasarake -->
            <div class="col-md-4 text-center p-2">
                <img src="<?php echo($kuvatiedosto); ?>" class="img-thumbnail rounded-start" alt="Kuva tuotteesta <?php echo($otsikko) ?>">
            </div>
            <!-- Tuotteen tiedot -sarake -->
            <div class="col-md-8">
                <div class="card-body">
                    <!-- Tuotteen otsikko -->
                    <h2 class="card-title"><?php echo($otsikko) ?></h2>
                    <!-- Tuotteen kuvaus -->
                    <p class="card-text"><?php echo($kuvaus) ?></p>
                    <!-- Tuotteen hinta -->
                    <p class="card-text">
                        Hinta:
                        <?php echo($hinta) ?> €
                    </p>
                    <!-- Tuotteen lisännyt käyttäjä -->
                    <p class="card-text">
                        Lisännyt:
                        <?php echo($kayttaja) ?>
                    </p>
                    <!-- Milloin tuote on lisätty -->
                    <p class="card-text">
                        Lisätty:
                        <?php echo($lisatty) ?>
                    </p>
                    <!-- Ota yhteyttä -osio, jossa näytetään ilmoituksen lisänneen käyttäjän
                    sähköposti ja puhelinnumero. -->
                    <p class="card-text">
                        <p><strong>Ota yhteyttä</strong></p>
                        <p>Sähköposti:
                        <?php echo($sposti) ?></p>
                        <p>Puhelinnumero:
                        <?php echo($puh) ?></p>
                    </p>
                </div>
            </div>
        </div>
        </div>
<?php } ?>