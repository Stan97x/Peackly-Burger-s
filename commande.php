<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Factories\ItemFactory;

session_start();


if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $idToRemove = (int)$_GET['id'];

    $_SESSION['commande'] = array_filter($_SESSION['commande'], function ($meal) use ($idToRemove) {
        return $meal['id'] !== $idToRemove;
    });


    header('Location: commande.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $mealId = (int)$_POST['id'];


    $meal = ItemFactory::getMealById($mealId);

    if ($meal) {

        if (!isset($_SESSION['commande'])) {
            $_SESSION['commande'] = [];
        }


        $found = false;
        foreach ($_SESSION['commande'] as &$existingMeal) {
            if ($existingMeal['id'] === $mealId) {
                $existingMeal['quantity'] += 1; 
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['commande'][] = [
                'id' => $meal->getId(),
                'name' => $meal->getName(),
                'price' => $meal->getPriceHT(),
                'tva' => $meal->getTva(),
                'quantity' => 1 
            ];
        }

     
        echo 'success';
        exit;
    } else {
   
        echo 'Plat introuvable.';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    http_response_code(400);
    echo 'Requête invalide.';
    exit;
}


$totalTTC = 0;
if (!empty($_SESSION['commande'])) {
    foreach ($_SESSION['commande'] as $meal) {
        $priceHT = $meal['price'];
        $tva = $meal['tva'];
        $quantity = $meal['quantity'];
        $totalMealTTC = ($priceHT + ($priceHT * $tva / 100)) * $quantity;

      
        $totalTTC += $totalMealTTC;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Votre Commande</title>
    <link rel="stylesheet" href="assets/css/commande.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background-color: #0F0E0E; color: white;">

<div class="wrapper">
    <div class="sidebar">
        <img class="logo" src='assets/images/PeacklyBurger.png'>
        <ul>
        <li><a href="accueil.html"><i class="fas fa-home"></i>Accueil</a></li>
                <li><a href="formule.php"><i class="fa fa-cutlery"></i>Formule</a></li>
                <li><a href="entree.php"><i class="fas fa-user"></i>Entrée</a></li>
                <li><a href="plat.php"><i class="fas fa-blog"></i>Burgers</a></li>
                <li><a href="Boisson.php"><i class="fa fa-glass"></i>Boisson</a></li>
                <li><a href="dessert.php"><i class="fas fa-cake"></i>Dessert</a></li>
        </ul>
        <ul class="Commande">
            <li><a href="commande.php"><i class="fas fa-shopping-cart"></i>Commande</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="info"></div>
    </div>
</div>
    <div class="main_content">
        <div class="info">
            <h1>Votre Commande</h1>
            <?php if (!empty($_SESSION['commande'])): ?>
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prix HT</th>
                            <th>TVA</th>
                            <th>Quantité</th>
                            <th>Total TTC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['commande'] as $meal): ?>
                            <?php
                            $priceHT = $meal['price'];
                            $tva = $meal['tva'];
                            $quantity = $meal['quantity'];
                            $totalMealTTC = ($priceHT + ($priceHT * $tva / 100)) * $quantity;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($meal['name']) ?></td>
                                <td><?= number_format($priceHT, 2) ?> €</td>
                                <td><?= number_format($tva, 2) ?>%</td>
                                <td><?= $quantity ?></td>
                                <td><?= number_format($totalMealTTC, 2) ?> €</td>
                                <td>
                                    <a href="commande.php?action=remove&id=<?= $meal['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h3>Total TTC: <?= number_format($totalTTC, 2) ?> €</h3>
            <?php else: ?>
                <p>Aucun plat dans votre commande pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
