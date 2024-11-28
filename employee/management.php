<?php

require_once '../vendor/autoload.php';
require_once '../src/Classes/Database.php';

use App\Classes\Database;

$dateFrom = $_GET['date_from'] ?? null; 
$dateTo = $_GET['date_to'] ?? null;

try {

    $pdo = Database::getConnection();

    // requête -> commandes selon dates filtre
    $query = "
        SELECT 
            c.datetime_command,
            c.datetime_begin,
            c.datetime_end,
            c.price_ttc,
            u.name AS user_name,
            u.surname AS user_surname
        FROM commands c
        LEFT JOIN users u ON c.user_id = u.id
    ";

    $params = [];
    if ($dateFrom && $dateTo) {
        $query .= " WHERE c.datetime_command BETWEEN :date_from AND :date_to";
        $params = [
            ':date_from' => $dateFrom . ' 00:00:00',
            ':date_to' => $dateTo . ' 23:59:59'
        ];
    } elseif ($dateFrom) {
        $query .= " WHERE c.datetime_command >= :date_from";
        $params = [':date_from' => $dateFrom . ' 00:00:00'];
    } elseif ($dateTo) {
        $query .= " WHERE c.datetime_command <= :date_to";
        $params = [':date_to' => $dateTo . ' 23:59:59'];
    }

    $query .= " ORDER BY c.datetime_command DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $commands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // variables à zero pour les calculs
    $totalPriceTTC = 0;
    $totalPrepTime = 0;
    $totalHandleTime = 0;
    $validPrepCount = 0;
    $validHandleCount = 0;
    $commandCount = count($commands);

    foreach ($commands as $command) {
        $totalPriceTTC += $command['price_ttc'];

        $prepTime = strtotime($command['datetime_end']) - strtotime($command['datetime_begin']);
        if ($prepTime > 0) {
            $totalPrepTime += $prepTime;
            $validPrepCount++;
        }

        $handleTime = strtotime($command['datetime_begin']) - strtotime($command['datetime_command']);
        if ($handleTime > 0) {
            $totalHandleTime += $handleTime;
            $validHandleCount++;
        }
    }

    $averagePriceTTC = $commandCount > 0 ? $totalPriceTTC / $commandCount : 0;
    $averagePrepTime = $validPrepCount > 0 ? $totalPrepTime / $validPrepCount : 0;
    $averageHandleTime = $validHandleCount > 0 ? $totalHandleTime / $validHandleCount : 0;
} catch (Exception $e) {
    $commands = [];
    $commandCount = 0;
    $averagePriceTTC = $averagePrepTime = $averageHandleTime = 0;
    error_log("Erreur lors de la récupération des commandes : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Management - Liste des Commandes</title>
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
        <h1><?= $commandCount ?> Commande(s)</h1>
        <!-- Form pour Filtre des dates -->
        <form method="get" action="management.php">
            <label for="date_from">De :</label>
            <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">
            <label for="date_to">À :</label>
            <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">
            <button type="submit">Filtrer</button>
        </form>
        <div class="info">
            <?php if (!empty($commands)) : ?>
                <table border="1" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Date de Commande</th>
                            <th>Nom et Prénom</th>
                            <th>Prix TTC</th>
                            <th>Temps de Prise en Charge (min)</th>
                            <th>Temps de Préparation (min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h1><?php echo count($commands) ?> commandes</h1>
                        <?php foreach ($commands as $command): ?>
                            <tr>
                                <td><?= htmlspecialchars($command['datetime_command']) ?></td>
                                <td><?= htmlspecialchars($command['user_name'] . ' ' . $command['user_surname']) ?></td>
                                <td><?= number_format($command['price_ttc'], 2) ?> €</td>
                                <td>
                                    <?php
                                    $handleTime = strtotime($command['datetime_begin']) - strtotime($command['datetime_command']);
                                    echo $handleTime > 0 ? round($handleTime / 60) . ' min' : 'N/A';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $prepTime = strtotime($command['datetime_end']) - strtotime($command['datetime_begin']);
                                    echo $prepTime > 0 ? round($prepTime / 60) . ' min' : 'N/A';
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <div class="averages">
                    <h2>Moyennes :</h2>
                    <ul>
                        <li>Prix TTC moyen : <?= number_format($averagePriceTTC, 2) ?> €</li>
                        <li>Temps de préparation moyen : <?= round($averagePrepTime / 60) ?> min</li>
                        <li>Temps de prise en charge moyen : <?= round($averageHandleTime / 60) ?> min</li>
                    </ul>
                </div>
            <?php else : ?>
                <p>Aucune commande trouvée.</p>
            <?php endif; ?>
        </div>
        <div class="stats-image" style="margin-top: 20px; text-align: center;">
            <img src="../assets/images/stats.png" alt="Statistiques" style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>

</body>
</html>
