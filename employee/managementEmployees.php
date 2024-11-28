<?php

require_once '../vendor/autoload.php';
require_once '../src/Classes/Database.php';

use App\Classes\Database;

try {
    $pdo = Database::getConnection();

    // Requête -> utilisateurs triés par rôle
    $query = "
        SELECT 
            u.id,
            u.matricule,
            u.name,
            u.surname,
            u.role,
            IF(u.role = 'cook', (
                SELECT AVG(TIMESTAMPDIFF(SECOND, c.datetime_begin, c.datetime_end))
                FROM commands c
                WHERE c.user_id = u.id
            ), NULL) AS avg_prep_time,
            IF(u.role = 'cook', (
                SELECT AVG(TIMESTAMPDIFF(SECOND, c.datetime_command, c.datetime_begin))
                FROM commands c
                WHERE c.user_id = u.id
            ), NULL) AS avg_handle_time,
            IF(u.role = 'cook', (
                SELECT COUNT(*)
                FROM commands c
                WHERE c.user_id = u.id
            ), NULL) AS command_count
        FROM users u
        ORDER BY FIELD(u.role, 'cook', 'management', 'stock'), avg_prep_time ASC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $users = [];
    error_log("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Management - Statistiques des Employés</title>
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
        <h1>Statistiques des Employés</h1>
        <div class="info">
            <?php if (!empty($users)) : ?>
                <table border="1" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Rôle</th>
                            <th>Temps de Préparation Moyenne (min)</th>
                            <th>Temps de Prise en Charge Moyenne (min)</th>
                            <th>Nombre de Commandes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['matricule']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['surname']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <?php if ($user['role'] === 'cook'): ?>
                                    <td><?= $user['avg_prep_time'] !== null ? round($user['avg_prep_time'] / 60, 2) . ' min' : 'N/A' ?></td>
                                    <td><?= $user['avg_handle_time'] !== null ? round($user['avg_handle_time'] / 60, 2) . ' min' : 'N/A' ?></td>
                                    <td><?= $user['command_count'] ?></td>
                                <?php else: ?>
                                    <td colspan="3" style="text-align: center;">N/A</td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Aucun utilisateur trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
