<?php 
session_start();
$_SESSION['ladattu_kuva'] = '';
?>

<?php include('html_alku.php') ?>

<h2>Myynti-ilmoitukset</h2>

<main>
<?php 
// Luodaan tietokantayhteys, jonka jälkeen sitä voidaan käyttää $conn-muuttujan kautta.
include('tietokantayhteys_mysqli.php');

// Haetaan myynti-ilmoitukset tietokannasta.
$sql = "SELECT title, description, username, image, price, date_added FROM items ORDER BY date_added DESC";
$result = $conn->query($sql);

// Listataan ilmoitukset.
foreach($result as $rivi) {
    echo("<hr>");
    echo "<h3>" . $rivi['title'] . "</h3>";
    echo "<p>" . $rivi['description'] . "</p>";
    if(isset($rivi['price'])) {
        echo "<p>Hinta: " . $rivi['price'] . " €</p>";
    } else {
        echo "<p>Hinta: Ei määritelty</p>";
    }
    echo "<p>Lisännyt: " . $rivi['username'] . "</p>";
    echo "<p>Lisätty: " . $rivi['date_added'] . "</p>";

    if(isset($rivi['image']) && $rivi['image'] != '') {
        $kuvatiedosto = $rivi['image'];
        echo('<img src="');
        echo("kuvat/" . $kuvatiedosto . '"');
        echo('width="200" height="200">');
    }
}
?>
</main>

<?php include('html_loppu.php') ?>