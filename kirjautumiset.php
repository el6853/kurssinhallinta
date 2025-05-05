<?php
include 'db.php';

if (isset($_POST['lisaa'])) {
    $stmt = $yhteys->prepare("INSERT INTO kurssikirjautumiset (opiskelija_id, kurssi_id, kirjautumisaika) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $_POST['opiskelija_id'], $_POST['kurssi_id']);
    $stmt->execute();
}

if (isset($_GET['poista'])) {
    $stmt = $yhteys->prepare("DELETE FROM kurssikirjautumiset WHERE tunnus = ?");
    $stmt->bind_param("i", $_GET['poista']);
    $stmt->execute();
}

$tulos = $yhteys->query("SELECT k.tunnus, o.etunimi AS opiskelija_etunimi, o.sukunimi AS opiskelija_sukunimi, c.nimi AS kurssi_nimi, k.kirjautumisaika FROM kurssikirjautumiset k JOIN opiskelijat o ON k.opiskelija_id = o.opiskelijanumero JOIN kurssit c ON k.kurssi_id = c.tunnus");
?>
<!DOCTYPE html>
<html><head><title>Kurssikirjautumiset</title><link rel="stylesheet" href="styles.css"></head><body>
<h2>Kurssikirjautumiset</h2>
<form method="post">
<input type="number" name="opiskelija_id" placeholder="Opiskelijanumero" required>
<input type="number" name="kurssi_id" placeholder="Kurssi ID" required>
<button type="submit" name="lisaa">Lisää kirjautuminen</button>
</form>
<table><tr><th>ID</th><th>Opiskelija</th><th>Kurssi</th><th>Kirjautumisaika</th><th>Toiminnot</th></tr>
<?php while($r = $tulos->fetch_assoc()): ?>
<tr><td><?= $r['tunnus'] ?></td><td><?= $r['opiskelija_etunimi'] ?> <?= $r['opiskelija_sukunimi'] ?></td><td><?= $r['kurssi_nimi'] ?></td><td><?= $r['kirjautumisaika'] ?></td>
<td><a href="?poista=<?= $r['tunnus'] ?>">Poista</a></td></tr>
<?php endwhile; ?></table>
<a href="index.php">Takaisin</a></body></html>
