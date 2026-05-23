<?php
require 'config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM produit WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: produit.php");
?>