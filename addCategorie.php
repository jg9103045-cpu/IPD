<?php
session_start();
require_once 'config.php';
include 'navbar.php';

// Valeurs par défaut pour le formulaire
$nom = $_POST['nom'] ?? '';
$errors = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $nom = trim($nom);
    
    // Validation des données
    if (empty($nom)) {
        $errors[] = "Le nom du categorie est requis.";
    }
    
    // Si pas d'erreurs, insérer dans la base de données
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categorie (nom) VALUES (:nom)");
            
            $stmt->execute([
                ':nom' => $nom,
            ]);
            
            // Redirection avec message de succès vers la liste
            $_SESSION['success_message'] = "categorie ajouté avec succès !";
            header('Location: categorie.php?success=1');
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Erreur lors de l'ajout de la categorie : " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Ajouter une Categorie</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="categorie.php" class="btn btn-secondary">← Retour à la liste</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulaire d'ajout de Categorie</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="addCategorie.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom de la categorie *</label>
                                <input type="text" class="form-control" id="nom" name="nom" required
                                       value="<?php echo htmlspecialchars($nom); ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Enregistrer la categorie
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

