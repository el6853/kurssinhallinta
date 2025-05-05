<?php
$yhteys = new mysqli("localhost", "root", "", "kurssinhallinta");
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}
?>