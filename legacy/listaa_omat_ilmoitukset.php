<hr>
<?php 
// Luodaan tietokantayhteys ($conn-muuttuja).
include('tietokantayhteys_mysqli.php');

function ilmoituksen_tiedot($ilmoitus) {
    echo('<span class="mr-auto">');
    echo("<p>" . $ilmoitus['description'] . "</p>");
    echo("<p>Lisätty: " . $ilmoitus['date_added'] . "</p>");
    if(isset($ilmoitus['price'])) {
        echo "<p>Hinta: " . $ilmoitus['price'] . " €</p>";
    } else {
        echo "<p>Hinta: Ei määritelty</p>";
    }
    echo("</span>");
}

function ilmoituksen_kuva($ilmoitus) {
    if(isset($ilmoitus['image']) && $ilmoitus['image'] != '') {
        $kuvatiedosto = $ilmoitus['image'];
        echo('<span>');
        echo('<img src="');
        echo("kuvat/" . $kuvatiedosto . '"');
        echo('class="fluid">');
        echo("</span>");
    }
}

// Haetaan käyttäjän myynti-ilmoitukset tietokannasta.
$stmt = $conn->prepare("SELECT id, title, description, price, image, date_added FROM items WHERE username=? ORDER BY id DESC");
$stmt->bind_param("s", $username);
$username = $_SESSION['user'];
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();

// Listaa ilmoitukset
foreach($result as $rivi) {
    echo('<div class="d-flex">');
    // Näytä ilmoituksen otsikko
    echo("<h3>" . $rivi['title'] . "</h3>");

    ilmoituksen_tiedot($rivi);
    ilmoituksen_kuva($rivi);

    echo("</div>");

    echo("<br><br>");
    // Muokkaa ilmoitusta ja Poista ilmoitus -linkit
    echo("<a class=\"btn btn-secondary me-4\" href=\"muokkaa_ilmoitusta.php?id=" . $rivi['id'] . "\">Muokkaa</a>");
    echo("<a class=\"btn btn-secondary\" href=\"poista_ilmoitus.php?id=" . $rivi['id'] . "\">Poista ilmoitus</a>");
    echo("<hr>");
}
?>