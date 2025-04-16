<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $conn->query("DELETE FROM moviegenre WHERE id_film = $id");
    $conn->query("DELETE FROM film WHERE id_film = $id");
}


$conn->close();
header("Location: read.php");
exit;
?>