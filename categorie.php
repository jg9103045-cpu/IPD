<?php
session_start();
require_once 'config.php';
include 'Acceuil.php';

// Gestion de la recherche
$search = $_GET['search'] ?? '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = "WHERE nom LIKE :search OR description LIKE :search OR categorie LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Récupération des produits
try {
    $sql = "SELECT * FROM categorie $whereClause ORDER BY nom DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $categorie = $stmt->fetchAll();
} catch(PDOException $e) {
    $error_message = "Erreur lors de la récupération des categorie : " . $e->getMessage();
    $categorie = [];
    // Afficher l'erreur pour le débogage (à retirer en production)
    error_log("Erreur PDO dans categorie.php: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <!-- Messages de succès/erreur -->
    <?php 
    if (isset($_GET['success']) && $_GET['success'] == 1): 
        $success_msg = $_SESSION['success_message'] ?? 'Categorie ajouté avec succès !';
        unset($_SESSION['success_message']);
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): 
        $error_msg = $_SESSION['error_message'] ?? '';
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['error_message']);
        unset($_SESSION['errors']);
    ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Liste des Categories</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="addCategorie.php" class="btn btn-success">
                + Ajouter un Categorie
            </a>
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="categorie.php" class="d-flex">
                <input type="text" 
                       class="form-control me-2" 
                       name="search" 
                       placeholder="Rechercher un Categorie (nom,id)..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-primary" type="submit">Rechercher</button>
                <?php if (!empty($search)): ?>
                    <a href="categorie.php" class="btn btn-outline-secondary ms-2">Effacer</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Liste des produits -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        Liste des Categories 
                        <?php if (!empty($search)): ?>
                            <span class="badge bg-light text-dark">Résultats de recherche</span>
                        <?php endif; ?>
                        <span class="badge bg-info"><?php echo count($categorie); ?> categorie(s)</span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <strong>Erreur :</strong> <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($categorie)): ?>
                        <div class="alert alert-info">
                            <?php if (!empty($search)): ?>
                                Aucune Categorie trouvé pour "<?php echo htmlspecialchars($search); ?>".
                            <?php else: ?>
                                Aucune Categorie enregistré. Ajoutez votre premiere Categorie ci-dessus.
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categorie as $categorie): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($categorie['id']); ?></td>
                                            <td><strong><?php echo htmlspecialchars($categorie['nom']); ?></strong></td>                                          
                                            <td>
                                                <a href="modifierCategorie.php?id=<?php echo (int)$categorie['id']; ?>" class="btn btn-sm btn-primary">
                                                    Modifier
                                                </a>
                                                <a href="supprimerCategorie.php?id=<?php echo (int)$categorie['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette categorie ?');">
                                                    Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
