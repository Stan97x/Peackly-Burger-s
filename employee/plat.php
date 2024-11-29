<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\Factories\ItemFactory;

// Gestion de la mise à jour AJAX pour le `sortOrder`
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!is_array($data)) {
            throw new Exception('Données invalides.');
        }

        // Appeler ItemFactory pour mettre à jour le tri
        $response = ItemFactory::updateSortOrder($data);

        echo json_encode(['success' => true, 'data' => $response]);
        exit;
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        error_log('Erreur dans la mise à jour du sortOrder : ' . $e->getMessage());
        exit;
    }
}

// Récupération des Meals filtrés par type
$type = $_GET['type'] ?? null;

try {
    $meals = ItemFactory::getMeals($type); 
} catch (Exception $e) {
    $meals = [];
    error_log("Erreur lors de la récupération des plats : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Meals - Peackly Burger's</title>
    <link rel="stylesheet" href="../assets/css/formule.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body style="background-color: #0F0E0E;">

<div class="wrapper">
    <div class="sidebar">
        <img class="logo" src='../assets/images/PeacklyBurger.png'>
        <ul>
            <li><a href="management.php"><i class="fas fa-home"></i>Stats des commandes</a></li>
            <li><a href="managementEmployees.php"><i class="fas fa-user"></i>Stats des employés</a></li>
            <li><a href="commandStocks.php"><i class="fas fa-address-card"></i>Commande de stocks</a></li>
            <li><a href="plat.php"><i class="fas fa-cogs"></i>Gestion des plats</a></li>
        </ul>
    </div>
    <div class="main_content">
        <h1>Gestion des Meals</h1>
        <!-- Filtre par type -->
        <form method="get" action="plat.php" style="margin-bottom: 20px;">
            <label for="type">Filtrer par type :</label>
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="">Tous</option>
                <option value="burger" <?= $type === 'burger' ? 'selected' : '' ?>>Burger</option>
                <option value="drink" <?= $type === 'drink' ? 'selected' : '' ?>>Boisson</option>
                <option value="entree" <?= $type === 'entree' ? 'selected' : '' ?>>Entrée</option>
                <option value="dessert" <?= $type === 'dessert' ? 'selected' : '' ?>>Dessert</option>
            </select>
        </form>

        <div style="text-align: right; margin-bottom: 10px;">
            <a href="addMeal.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un Meal</a>
        </div>
        
        <!-- Liste des Meals triable -->
        <div id="meals-container">
            <?php if (!empty($meals)) : ?>
                <?php foreach ($meals as $meal) : ?>
                    <div class="meal-card" data-id="<?= $meal->getId() ?>">
                        <h3><?= htmlspecialchars($meal->getName()) ?></h3>
                        <p><strong>Prix TTC :</strong> <?= number_format($meal->getPriceHT() + $meal->getPriceHT() * $meal->getTva(), 2) ?> €</p>
                        <p><strong><?= $meal->getType() ?></p> :</strong> <?= htmlspecialchars(implode(', ', $meal->getAliments())) ?></p>
                        <div class="actions">
                            <a href="editMeal.php?id=<?= $meal->getId() ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="deleteMeal.php?id=<?= $meal->getId() ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Meal ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun Meal disponible pour ce type.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Initialiser Sortable.js
    const mealsContainer = document.getElementById('meals-container');
    Sortable.create(mealsContainer, {
        animation: 150,
        onEnd: function () {
            // Récupérer l'ordre des IDs
            let order = [];
            document.querySelectorAll('.meal-card').forEach((card, index) => {
                order.push({id: card.dataset.id, sortOrder: index + 1});
            });

            // Envoyer les données au serveur via AJAX
            $.ajax({
                url: 'plat.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(order),
                success: function () {
                    // Recharger la page pour refléter le tri
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error('Erreur lors de la mise à jour de l\'ordre:', error);
                    alert('Une erreur est survenue lors de la mise à jour du tri.');
                }
            });
        }
    });
</script>

<style>
    #meals-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .meal-card {
        background: #fff;
        color: #000;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: calc(33.33% - 10px);
    }
    .meal-card h3 {
        margin: 0 0 10px;
    }
    .meal-card .actions {
        margin-top: 10px;
    }
    .meal-card .btn {
        margin-right: 5px;
        padding: 5px 10px;
        text-decoration: none;
        color: #fff;
        border-radius: 3px;
        display: inline-block;
    }
    .meal-card .btn-warning {
        background: #ffc107;
    }
    .meal-card .btn-danger {
        background: #dc3545;
    }
    .meal-card .btn-primary {
        background: #007bff;
    }
</style>

</body>
</html>
