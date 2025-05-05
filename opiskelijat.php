<?php
include 'db.php';

// Lisää opiskelija
if (isset($_POST['lisaa'])) {
    $stmt = $yhteys->prepare("INSERT INTO opiskelijat (opiskelijanumero, etunimi, sukunimi, syntymapaiva, vuosikurssi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $_POST['opiskelijanumero'], $_POST['etunimi'], $_POST['sukunimi'], $_POST['syntymapaiva'], $_POST['vuosikurssi']);
    $stmt->execute();
}

// Päivitä opiskelija
if (isset($_POST['paivita'])) {
    $stmt = $yhteys->prepare("UPDATE opiskelijat SET opiskelijanumero=?, etunimi=?, sukunimi=?, syntymapaiva=?, vuosikurssi=? WHERE opiskelijanumero=?");
    $stmt->bind_param("ssssis", $_POST['opiskelijanumero'], $_POST['etunimi'], $_POST['sukunimi'], $_POST['syntymapaiva'], $_POST['vuosikurssi'], $_POST['vanha_opnro']);
    $stmt->execute();
}

// Poista opiskelija
if (isset($_GET['poista'])) {
    $stmt = $yhteys->prepare("DELETE FROM opiskelijat WHERE opiskelijanumero=?");
    $stmt->bind_param("s", $_GET['poista']);
    $stmt->execute();
}

$opiskelijat = $yhteys->query("SELECT * FROM opiskelijat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Opiskelijat</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Opiskelijat</h2>

<form method="post">
    <input type="text" name="opiskelijanumero" placeholder="Opiskelijanumero" required>
    <input type="text" name="etunimi" placeholder="Etunimi" required>
    <input type="text" name="sukunimi" placeholder="Sukunimi" required>
    <input type="date" name="syntymapaiva" required>
    <input type="number" name="vuosikurssi" min="1" max="3" placeholder="Vuosikurssi" required>
    <button type="submit" name="lisaa">Lisää opiskelija</button>
</form>

<table>
<tr>
    <th>Nimi</th><th>Opiskelijanumero</th><th>Syntymäpäivä</th><th>Vuosikurssi</th><th>Ilmoittautuneet kurssit</th><th>Muokkaa</th><th>Poista</th>
</tr>
<?php while($o = $opiskelijat->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($o['etunimi'] . ' ' . $o['sukunimi']) ?></td>
<td><?= htmlspecialchars($o['opiskelijanumero']) ?></td>
<td><?= $o['syntymapaiva'] ?></td>
<td><?= $o['vuosikurssi'] ?></td>
<td>
    <ul>
    <?php
    $stmt = $yhteys->prepare("SELECT k.nimi, k.alkupaiva 
                              FROM kurssikirjautumiset kk 
                              JOIN kurssit k ON kk.kurssi_id = k.tunnus 
                              WHERE kk.opiskelija_id = ?");
    $stmt->bind_param("s", $o['opiskelijanumero']);
    $stmt->execute();
    $kurssit = $stmt->get_result();
    while ($k = $kurssit->fetch_assoc()):
    ?>
        <li><?= htmlspecialchars($k['nimi']) ?> (<?= $k['alkupaiva'] ?>)</li>
    <?php endwhile; ?>
    </ul>
</td>
<td>
    <form method="post">
        <input type="hidden" name="vanha_opnro" value="<?= $o['opiskelijanumero'] ?>">
        <input type="text" name="opiskelijanumero" value="<?= $o['opiskelijanumero'] ?>" readonly>
        <input type="text" name="etunimi" value="<?= $o['etunimi'] ?>" required>
        <input type="text" name="sukunimi" value="<?= $o['sukunimi'] ?>" required>
        <input type="date" name="syntymapaiva" value="<?= $o['syntymapaiva'] ?>" required>
        <input type="number" name="vuosikurssi" value="<?= $o['vuosikurssi'] ?>" min="1" max="3" required>
        <button type="submit" name="paivita">Tallenna</button>
    </form>
</td>
<td><a href="?poista=<?= $o['opiskelijanumero'] ?>">Poista</a></td>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Takaisin</a>
</body>
</html>
