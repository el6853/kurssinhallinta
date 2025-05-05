<?php
include 'db.php';

// Lisää tila
if (isset($_POST['lisaa'])) {
    $stmt = $yhteys->prepare("INSERT INTO tilat (nimi, kapasiteetti) VALUES (?, ?)");
    $stmt->bind_param("si", $_POST['nimi'], $_POST['kapasiteetti']);
    $stmt->execute();
}

// Päivitä tila
if (isset($_POST['paivita'])) {
    $stmt = $yhteys->prepare("UPDATE tilat SET nimi=?, kapasiteetti=? WHERE tunnus=?");
    $stmt->bind_param("sii", $_POST['nimi'], $_POST['kapasiteetti'], $_POST['tunnus']);
    $stmt->execute();
}

// Poista tila
if (isset($_GET['poista'])) {
    $stmt = $yhteys->prepare("DELETE FROM tilat WHERE tunnus=?");
    $stmt->bind_param("i", $_GET['poista']);
    $stmt->execute();
}

$tilat = $yhteys->query("SELECT * FROM tilat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tilat</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .warning { color: red; font-weight: bold; }
    </style>
</head>
<body>
<h2>Tilat</h2>

<form method="post">
    <input type="text" name="nimi" placeholder="Tilan nimi" required>
    <input type="number" name="kapasiteetti" placeholder="Kapasiteetti" required>
    <button type="submit" name="lisaa">Lisää tila</button>
</form>

<table>
<tr>
    <th>Nimi</th><th>Kapasiteetti</th><th>Kurssit</th><th>Muokkaa</th><th>Poista</th>
</tr>
<?php while($t = $tilat->fetch_assoc()): ?>
<tr>
<td><?= $t['nimi'] ?></td>
<td><?= $t['kapasiteetti'] ?></td>
<td>
    <ul>
    <?php
    $stmt = $yhteys->prepare("SELECT k.nimi, k.alkupaiva, k.loppupaiva, o.etunimi, o.sukunimi, k.tunnus AS kurssi_id 
        FROM kurssit k 
        LEFT JOIN opettajat o ON k.opettaja_id = o.tunnus 
        WHERE k.tila_id = ?");
    $stmt->bind_param("i", $t['tunnus']);
    $stmt->execute();
    $kurssit = $stmt->get_result();
    while ($k = $kurssit->fetch_assoc()):
        // Laske osallistujat
        $stmt2 = $yhteys->prepare("SELECT COUNT(*) AS maara FROM kurssikirjautumiset WHERE kurssi_id = ?");
        $stmt2->bind_param("i", $k['kurssi_id']);
        $stmt2->execute();
        $res = $stmt2->get_result()->fetch_assoc();
        $osallistujat = $res['maara'];

        $varoitus = ($osallistujat > $t['kapasiteetti']) ? "<span class='warning'>⚠️</span>" : "";
    ?>
        <li><?= $k['nimi'] ?> (<?= $k['alkupaiva'] ?>–<?= $k['loppupaiva'] ?>), Opettaja: <?= $k['etunimi'] ?> <?= $k['sukunimi'] ?>, Osallistujat: <?= $osallistujat ?> <?= $varoitus ?></li>
    <?php endwhile; ?>
    </ul>
</td>
<td>
    <form method="post">
        <input type="hidden" name="tunnus" value="<?= $t['tunnus'] ?>">
        <input type="text" name="nimi" value="<?= $t['nimi'] ?>" required>
        <input type="number" name="kapasiteetti" value="<?= $t['kapasiteetti'] ?>" required>
        <button type="submit" name="paivita">Tallenna</button>
    </form>
</td>
<td><a href="?poista=<?= $t['tunnus'] ?>">Poista</a></td>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Takaisin</a>
</body>
</html>
