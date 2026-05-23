<?php
require 'config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: categorie.php");
?>