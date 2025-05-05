<?php
include 'db.php';

// Lisää uusi kurssi
if (isset($_POST['lisaa'])) {
    $stmt = $yhteys->prepare("INSERT INTO kurssit (nimi, kuvaus, alkupaiva, loppupaiva, opettaja_id, tila_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $_POST['nimi'], $_POST['kuvaus'], $_POST['alkupaiva'], $_POST['loppupaiva'], $_POST['opettaja_id'], $_POST['tila_id']);
    $stmt->execute();
}

// Päivitä kurssi
if (isset($_POST['paivita'])) {
    $stmt = $yhteys->prepare("UPDATE kurssit SET nimi=?, kuvaus=?, alkupaiva=?, loppupaiva=?, opettaja_id=?, tila_id=? WHERE tunnus=?");
    $stmt->bind_param("ssssiii", $_POST['nimi'], $_POST['kuvaus'], $_POST['alkupaiva'], $_POST['loppupaiva'], $_POST['opettaja_id'], $_POST['tila_id'], $_POST['tunnus']);
    $stmt->execute();
}

// Poista kurssi
if (isset($_GET['poista'])) {
    $stmt = $yhteys->prepare("DELETE FROM kurssit WHERE tunnus=?");
    $stmt->bind_param("i", $_GET['poista']);
    $stmt->execute();
}

$kurssit = $yhteys->query("SELECT k.*, o.etunimi AS op_etunimi, o.sukunimi AS op_sukunimi, t.nimi AS tila_nimi 
    FROM kurssit k 
    LEFT JOIN opettajat o ON k.opettaja_id = o.tunnus 
    LEFT JOIN tilat t ON k.tila_id = t.tunnus");
$opettajat = $yhteys->query("SELECT * FROM opettajat");
$tilat = $yhteys->query("SELECT * FROM tilat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kurssit</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Kurssit</h2>

<form method="post">
    <input type="text" name="nimi" placeholder="Kurssin nimi" required>
    <input type="text" name="kuvaus" placeholder="Kuvaus" required>
    <input type="date" name="alkupaiva" required>
    <input type="date" name="loppupaiva" required>
    <select name="opettaja_id" required>
        <option value="">Valitse opettaja</option>
        <?php while($o = $opettajat->fetch_assoc()): ?>
            <option value="<?= $o['tunnus'] ?>"><?= $o['etunimi'] ?> <?= $o['sukunimi'] ?></option>
        <?php endwhile; ?>
    </select>
    <select name="tila_id" required>
        <option value="">Valitse tila</option>
        <?php while($t = $tilat->fetch_assoc()): ?>
            <option value="<?= $t['tunnus'] ?>"><?= $t['nimi'] ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit" name="lisaa">Lisää kurssi</button>
</form>

<table>
<tr>
    <th>Nimi</th><th>Kuvaus</th><th>Alku</th><th>Loppu</th><th>Opettaja</th><th>Tila</th><th>Opiskelijat</th><th>Muokkaa</th><th>Poista</th>
</tr>
<?php while($k = $kurssit->fetch_assoc()): ?>
<tr>
<td><?= $k['nimi'] ?></td>
<td><?= $k['kuvaus'] ?></td>
<td><?= $k['alkupaiva'] ?></td>
<td><?= $k['loppupaiva'] ?></td>
<td><?= $k['op_etunimi'] ?> <?= $k['op_sukunimi'] ?></td>
<td><?= $k['tila_nimi'] ?></td>
<td>
    <ul>
    <?php
    $stmt = $yhteys->prepare("SELECT o.etunimi, o.sukunimi, o.vuosikurssi FROM kurssikirjautumiset kk 
        JOIN opiskelijat o ON kk.opiskelija_id = o.opiskelijanumero 
        WHERE kk.kurssi_id = ?");
    $stmt->bind_param("i", $k['tunnus']);
    $stmt->execute();
    $opiskelijat = $stmt->get_result();
    while ($o = $opiskelijat->fetch_assoc()):
    ?>
        <li><?= $o['etunimi'] ?> <?= $o['sukunimi'] ?> (vk <?= $o['vuosikurssi'] ?>)</li>
    <?php endwhile; ?>
    </ul>
</td>
<td>
    <form method="post">
        <input type="hidden" name="tunnus" value="<?= $k['tunnus'] ?>">
        <input type="text" name="nimi" value="<?= $k['nimi'] ?>" required>
        <input type="text" name="kuvaus" value="<?= $k['kuvaus'] ?>" required>
        <input type="date" name="alkupaiva" value="<?= $k['alkupaiva'] ?>" required>
        <input type="date" name="loppupaiva" value="<?= $k['loppupaiva'] ?>" required>
        <input type="number" name="opettaja_id" value="<?= $k['opettaja_id'] ?>" required>
        <input type="number" name="tila_id" value="<?= $k['tila_id'] ?>" required>
        <button type="submit" name="paivita">Tallenna</button>
    </form>
</td>
<td><a href="?poista=<?= $k['tunnus'] ?>">Poista</a></td>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Takaisin</a>
</body>
</html>
