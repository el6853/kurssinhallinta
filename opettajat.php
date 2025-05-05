<?php
include 'db.php';

// Lisää uusi opettaja
if (isset($_POST['lisaa'])) {
    $stmt = $yhteys->prepare("INSERT INTO opettajat (etunimi, sukunimi, aine) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['etunimi'], $_POST['sukunimi'], $_POST['aine']);
    $stmt->execute();
}

// Päivitä opettaja
if (isset($_POST['paivita'])) {
    $stmt = $yhteys->prepare("UPDATE opettajat SET etunimi=?, sukunimi=?, aine=? WHERE tunnus=?");
    $stmt->bind_param("sssi", $_POST['etunimi'], $_POST['sukunimi'], $_POST['aine'], $_POST['tunnus']);
    $stmt->execute();
}

// Poista opettaja
if (isset($_GET['poista'])) {
    $stmt = $yhteys->prepare("DELETE FROM opettajat WHERE tunnus=?");
    $stmt->bind_param("i", $_GET['poista']);
    $stmt->execute();
}

$opettajat = $yhteys->query("SELECT * FROM opettajat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Opettajat</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Opettajat</h2>

<form method="post">
    <input type="text" name="etunimi" placeholder="Etunimi" required>
    <input type="text" name="sukunimi" placeholder="Sukunimi" required>
    <input type="text" name="aine" placeholder="Aine" required>
    <button type="submit" name="lisaa">Lisää opettaja</button>
</form>

<table>
<tr>
    <th>Nimi</th><th>Aine</th><th>Kurssit</th><th>Muokkaa</th><th>Poista</th>
</tr>
<?php while($o = $opettajat->fetch_assoc()): ?>
<tr>
<td><?= $o['etunimi'] ?> <?= $o['sukunimi'] ?></td>
<td><?= $o['aine'] ?></td>
<td>
    <ul>
    <?php
    $stmt = $yhteys->prepare("SELECT k.nimi, k.alkupaiva, k.loppupaiva, t.nimi AS tila_nimi 
                              FROM kurssit k 
                              LEFT JOIN tilat t ON k.tila_id = t.tunnus 
                              WHERE k.opettaja_id = ?");
    $stmt->bind_param("i", $o['tunnus']);
    $stmt->execute();
    $kurssit = $stmt->get_result();
    while ($k = $kurssit->fetch_assoc()):
    ?>
        <li><?= $k['nimi'] ?> (<?= $k['alkupaiva'] ?> – <?= $k['loppupaiva'] ?>), tila: <?= $k['tila_nimi'] ?></li>
    <?php endwhile; ?>
    </ul>
</td>
<td>
    <form method="post">
        <input type="hidden" name="tunnus" value="<?= $o['tunnus'] ?>">
        <input type="text" name="etunimi" value="<?= $o['etunimi'] ?>" required>
        <input type="text" name="sukunimi" value="<?= $o['sukunimi'] ?>" required>
        <input type="text" name="aine" value="<?= $o['aine'] ?>" required>
        <button type="submit" name="paivita">Tallenna</button>
    </form>
</td>
<td><a href="?poista=<?= $o['tunnus'] ?>">Poista</a></td>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Takaisin</a>
</body>
</html>
