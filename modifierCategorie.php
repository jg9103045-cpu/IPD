<?php
require 'config.php';

// 1. Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id'])) {
    header("Location: categorie.php");
    exit();
}

$id = $_GET['id'];

// 2. Récupérer les données actuelles du produit
$stmt = $pdo->prepare("SELECT * FROM categorie WHERE id = ?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    die("categorie introuvable.");
}

// 3. Récupérer la liste des catégories pour le menu déroulant
//$cats = $pdo->query("SELECT * FROM produit")->fetchAll();

// 4. Traiter la soumission du formulaire (Mise à jour)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];

    $sql = "UPDATE categorie SET nom = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nom,$id])) {
        header("Location: categorie.php?message=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la categorie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">Modifier la categorie : <?= htmlspecialchars($categorie['nom']) ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom de la categorie</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($categorie['nom']) ?>" required>
                            </div>                         
                            <div class="d-flex justify-content-between">
                                <a href="categorie.php" class="btn btn-secondary">Annuler</a>
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