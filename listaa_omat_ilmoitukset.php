<hr>
<?php
// Luodaan tietokantayhteys ($conn-muuttuja).
include('tietokantayhteys_mysqli.php');

// Haetaan käyttäjän myynti-ilmoitukset tietokannasta. Sisäänkirjautuneen käyttäjän käyttäjänimi
// löytyy "user"-sessiomuuttujasta.
$stmt = $conn->prepare("SELECT id, title, description, price, image, date_added FROM items WHERE username=? ORDER BY id DESC");
$stmt->bind_param("s", $username);
$username = $_SESSION['user'];
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();

$kuvatiedosto = "https://placehold.co/400x300?text=Ei+kuvaa";
?>

<?php if(mysqli_num_rows($result) == 0) { ?>
    <p>Et ole vielä lisännyt palveluun yhtään ilmoitusta.</p>
    <a href="uusi_ilmoitus.php" class="btn btn-dark">Lisää ilmoitus</a>
<?php } ?>

<!-- Listaa ilmoitukset -->
<?php foreach($result as $rivi) { ?>
    <?php
        $id = $rivi['id'];
        $otsikko = htmlspecialchars($rivi['title']);
        $kuvaus = htmlspecialchars($rivi['description']);
        // Jos tuotteelle on asetettu kuva, aseta sen sijainti $kuvatiedosto-muuttujaan
        // kuvan näyttämistä varten (kaikki käyttäjän lataamat kuvat löytyvät 
        // "kuvat"-alikansiosta)
        if($rivi['image'] != "") $kuvatiedosto = "kuvat/" . $rivi['image'];
    ?>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4 text-center p-2">
                <img src="<?php echo($kuvatiedosto); ?>" class="img-thumbnail rounded-start" alt="Kuva tuotteesta <?php echo($otsikko) ?>">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title"><?php echo($otsikko) ?></h2>
                    <p class="card-text"><?php echo($kuvaus) ?></p>
                    <a class="btn btn-secondary me-4" href="<?php echo("muokkaa_ilmoitusta.php?id=" . $id) ?>">Muokkaa</a>
                    <a class="btn btn-secondary me-4" href="javascript:poistaIlmoitus(<?php echo($id) ?>)">Poista ilmoitus</a>
                </div>
            </div>
        </div>
        </div>
<?php } ?>

<script>
function poistaIlmoitus(id) {
    if(confirm("Haluatko varmasti poistaa ilmoituksen?")) {
        window.location.href = "poista_ilmoitus.php?id=" + id;
    }
}
</script>