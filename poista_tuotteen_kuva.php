<?php
// Hae tuotteen kuva kuvatiedoston poistamista varten.
try {
    $sql = $conn->prepare("SELECT image FROM items WHERE id=:id AND username=:username LIMIT 1");
    $sql->bindParam(':id', $_GET['id']);
    $sql->bindParam(':username', $_SESSION['user']);
    $sql->execute();
    $kuvatiedosto = $sql->fetch()[0];
    if ($kuvatiedosto) {
        $kuvatiedosto = $result;
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}
?>