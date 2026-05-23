<?php
require 'config.php';

// 1. Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id'])) {
    header("Location: produit.php");
    exit();
}

$id = $_GET['id'];

// 2. Récupérer les données actuelles du produit
$stmt = $pdo->prepare("SELECT * FROM produit WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    die("Produit introuvable.");
}

// 3. Récupérer la liste des catégories pour le menu déroulant
$cats = $pdo->query("SELECT * FROM categorie")->fetchAll();

// 4. Traiter la soumission du formulaire (Mise à jour)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $id_categorie = $_POST['id_categorie'];

    $sql = "UPDATE produit SET nom = ?, description = ?, prix = ?, quantite = ?, Categorie = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nom, $description, $prix, $quantite, $id_categorie, $id])) {
        header("Location: produit.php?message=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Produit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">Modifier le produit : <?= htmlspecialchars($produit['nom']) ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom du produit</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($produit['nom']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($produit['description']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prix (CFA)</label>
                                    <input type="number" name="prix" class="form-control" value="<?= $produit['prix'] ?>" step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="quantite" class="form-control" value="<?= $produit['quantite'] ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select name="id_categorie" class="form-select" required>
                                    <?php foreach ($cats as $c): ?>
                                        <option value="<?= $c['nom'] ?>" <?= ($c['id'] == $produit['Categorie']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="produit.php" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>