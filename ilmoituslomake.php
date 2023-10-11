<?php
/* 
Tämä tiedosto sisältää ilmoituslomakkeen määrittelyn ja sitä käytetään sekä lisättäessä uutta
ilmoitusta (uusi_ilmoitus.php) että muokattaessa ilmoitusta (muokkaa_ilmoitusta.php). Tiedostossa käytetyn $lomake_action-muuttujan arvo määrää sen, kumpi toiminto suoritetaan ja sen arvo tulee asettaa ennen tiedoston sisällyttämistä.
*/
?>
<form action="<?php echo($lomake_action) ?>" method="POST" enctype="multipart/form-data">
        <?php 
            // CSRF-token
            include("csrf_token_input.php") 
        ?>
        
        <?php // Jos tuotteella on id (= ollaan muokkaamassa tuotetta), luo piilotettu kenttä, jonka arvo on id.
        // Tätä arvoa käytetään, kun tuotteen tiedot päivitetään tietokantaan.
            if(isset($id)) { ?>
                <input type="hidden" name="id" value="<?php echo($id) ?>">
        <?php } ?>
        <!-- Otsikko -->
        <div class="mb-4">
            <label for="otsikko">Otsikko:</label>
            <input type="text" class="form-control" id="otsikko" name="otsikko" placeholder="Kirjoita myytävän tavaran/palvelun nimi tähän" 
            value="<?php if(isset($otsikko)) echo($otsikko); ?>">
        </div>
        <!-- Kuvaus -->
        <div class="mb-4">
            <label for="kuvaus">Kuvaus:</label>
            <textarea class="form-control" id="kuvaus" name="kuvaus" placeholder="Kirjoita myytävän tavaran/palvelun mahdollisimman tarkka ja todenmukainen kuvaus tähän. Huom. kuvaa myös mahdolliset virheet ja puutteet! Muista myös kertoa, miten sinuun saa yhteyttä! Älä mainosta tavaraa äläkä sisällytä kuvaukseen asioita, mitkä eivät ole totta!" rows="5"><?php if(isset($kuvaus)) echo($kuvaus); ?></textarea>
        </div>
        <!-- Hinta -->
        <div class="mb-4">
            <label for="hinta">Hinta</label>
            <input type="number" step=".01" class="form-control hinta" name="hinta" id="hinta" 
            value="<?php echo number_format((float)$hinta, 2, '.', '') ?>">
        </div>
        <!-- Kuvan lataaminen -->
        <div class="mb-4">
            <div><label for="kuva"><strong>Kuva:</strong></label></div>
            <!-- Kenttä kuvanlatausta varten. Huom: Jotta kuvanlataus toimii, pitää name-
            attribuutin olla "fileToUpload", koska sisällytetty tiedosto upload_image.php
            käyttää tätä attribuuttia kuvan latauksessa -->
            <input type="file" accept="image/png, image/jpg, image/jpeg, image/gif" name="fileToUpload" id="kuvaInput">
            <img id="esikatselu" 
                src="<?php if(isset($kuva) && $kuva != "") echo("kuvat/$kuva"); 
                    else echo("https://placehold.co/200x200?text=Kuvan\\nesikatselu"); ?>"
                alt="Kuvan esikatselu" width="200" height="200"/>
        </div>

        <!-- Lisää ilmoitus/Päivitä tiedot -painike -->
        <button class="btn btn-secondary">
            <?php
                // Määritä painikkeen tekstiksi joko Lisää ilmoitus tai Muokkaa ilmoitusta
                // riippuen siitä, kumpaa toimintoa käyttäjän on tekemässä.
                if($lomake_action == "uusi_ilmoitus.php") {
                    echo("Lisää ilmoitus");
                } else {
                    echo("Päivitä tiedot");
                }
            ?>
        </button>
        <!-- Peruuta-painike (linkki) -->
        <a href="omat_ilmoitukset.php" class="btn btn-secondary">Peruuta</a>
    </form>

<script>
// Kuvan esikatselun päivittäminen, kun käyttäjä on valinnut kuvan.
document.getElementById('kuvaInput').onchange = evt => {
  const [file] = kuvaInput.files
  if (file) {
    esikatselu.src = URL.createObjectURL(file)
  }
}
</script>