<?php

require_once '../vendor/autoload.php';
require_once '../src/Classes/Database.php';

use App\Classes\Database;

// Récupérer les dates du filtre
$dateFrom = $_GET['date_from'] ?? null;
$dateTo = $_GET['date_to'] ?? null;


$dateFromWithTime = $dateFrom ? $dateFrom . ' 00:00:00' : null;
$dateToWithTime = $dateTo ? $dateTo . ' 23:59:59' : null;

try {
    
    $pdo = Database::getConnection();

    // requête -> aliments et leur quantité utilisée
    $query = "
        SELECT 
            a.id AS aliment_id,
            a.name AS aliment_name,
            a.price_per_unit_ht,
            a.price_per_kilo_ht,
            a.tva,
            a.supplier,
            a.quantity,
            IFNULL(SUM(ci.quantity), 0) AS total_used
        FROM aliments a
        LEFT JOIN meal_aliment ma ON a.id = ma.aliment_id
        LEFT JOIN meals m ON ma.meal_id = m.id
        LEFT JOIN command_items ci ON ci.meal_id = m.id
        LEFT JOIN commands c ON ci.command_id = c.id
    ";

    $params = [];
    if ($dateFromWithTime && $dateToWithTime) {
        $query .= " WHERE c.datetime_command BETWEEN :date_from AND :date_to";
        $params = [
            ':date_from' => $dateFromWithTime,
            ':date_to' => $dateToWithTime
        ];
    } elseif ($dateFromWithTime) {
        $query .= " WHERE c.datetime_command >= :date_from";
        $params = [':date_from' => $dateFromWithTime];
    } elseif ($dateToWithTime) {
        $query .= " WHERE c.datetime_command <= :date_to";
        $params = [':date_to' => $dateToWithTime];
    }

    $query .= " GROUP BY a.id ORDER BY total_used DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $aliments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $aliments = [];
    error_log("Erreur lors de la récupération des aliments : " . $e->getMessage());
}

// Fichier csv au clic
if (isset($_GET['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="gestion_stocks.csv"');
    
    $output = fopen('php://output', 'w');
    
    // fichier csv (début) : dates 
    if ($dateFrom && $dateTo) {
        fputcsv($output, ["Période : Du $dateFrom au $dateTo"]);
    } elseif ($dateFrom) {
        fputcsv($output, ["Période : À partir du $dateFrom"]);
    } elseif ($dateTo) {
        fputcsv($output, ["Période : Jusqu'au $dateTo"]);
    } else {
        fputcsv($output, ["Période : Toutes les dates"]);
    }

    // fichier csv : séparation
    fputcsv($output, []);

    // fichier csv : entête tableau
    fputcsv($output, ['Nom de l\'Aliment', 'Quantité Utilisée', 'Quantité Restante', 'Prix Unitaire HT', 'Prix par Kilo HT', 'TVA', 'Fournisseur']);

    // fichier csv : tableau
    foreach ($aliments as $aliment) {
        fputcsv($output, [
            $aliment['aliment_name'],
            $aliment['total_used'],
            $aliment['quantity'],
            number_format($aliment['price_per_unit_ht'], 2),
            number_format($aliment['price_per_kilo_ht'], 2),
            number_format($aliment['tva'] * 100, 2) . '%',
            $aliment['supplier']
        ]);
    }

    fclose($output);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Stocks</title>
    <link rel="stylesheet" href="../assets/css/formule.css"> 
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script> 
</head>
<body>

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
        <h1>Gestion des Stocks</h1>
        <!-- Filtre dates -->
        <form method="get" action="gestionStocks.php">
            <label for="date_from">De :</label>
            <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">
            <label for="date_to">À :</label>
            <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">
            <button type="submit">Filtrer</button>
        </form>
        <br>
        <!-- export CSV -->
        <form method="get" action="gestionStocks.php">
            <input type="hidden" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">
            <input type="hidden" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">
            <button type="submit" name="export_csv">Exporter en CSV</button>
        </form>
        <br>
        <div class="info">
            <?php if (!empty($aliments)) : ?>
                <table border="1" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Nom de l'Aliment</th>
                            <th>Quantité Utilisée</th>
                            <th>Quantité Restante</th>
                            <th>Prix Unitaire HT</th>
                            <th>Prix par Kilo HT</th>
                            <th>TVA</th>
                            <th>Fournisseur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aliments as $aliment): ?>
                            <tr>
                                <td><?= htmlspecialchars($aliment['aliment_name']) ?></td>
                                <td><?= $aliment['total_used'] ?></td>
                                <td><?= $aliment['quantity'] ?></td>
                                <td><?= number_format($aliment['price_per_unit_ht'], 2) ?> €</td>
                                <td><?= number_format($aliment['price_per_kilo_ht'], 2) ?> €</td>
                                <td><?= number_format($aliment['tva'] * 100, 2) ?>%</td>
                                <td><?= htmlspecialchars($aliment['supplier']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Aucun aliment trouvé pour la période sélectionnée.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
