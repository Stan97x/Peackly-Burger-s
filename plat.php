<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Factories\ItemFactory;

try {
    // Récupérer tous les meals de type 'burger'
    $meals = ItemFactory::getMeals('burger');
} catch (Exception $e) {
    // Gérer l'erreur
    $meals = [];
    error_log("Erreur lors de la récupération des plats : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Peackly Burger's - Nos Plats</title>
    <link rel="stylesheet" href="assets/css/formule.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background-color: #0F0E0E;">

<div class="wrapper">
    <div class="sidebar">
        <img class="logo" src='assets/images/PeacklyBurger.png'>
        <ul>
            <li><a href="#"><i class="fas fa-home"></i>Accueil</a></li>
            <li><a href="#"><i class="fas fa-user"></i>Entrée</a></li>
            <li><a href="#"><i class="fa fa-cutlery"></i>Formule</a></li>
            <li><a href="#"><i class="fas fa-blog"></i>Plat</a></li>
            <li><a href="#"><i class="fa fa-glass"></i>Boisson</a></li>
            <li><a href="#"><i class="fas fa-cake"></i>Dessert</a></li>
        </ul>
        <ul class="Commande">
            <li><a href="#"><i class="fas fa-shopping-cart"></i>Commande</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="info"></div>
    </div>
</div>

<!-----------Special Formule---------->
<section class="products">
    <h1 class="title">Nos Plats</h1>
    <div class="box-containeur">
        <?php if (!empty($meals)) : ?>
            <?php foreach ($meals as $meal) : ?>
                <?php $price_ttc = $meal->getPriceHT() + ($meal->getPriceHT() * $meal->getTva() / 100); ?>
                <form action="action.php" method="post" class="box">
                    <button type="submit" class="fas fa-eye" name="quick_view"></button>
                    <button type="submit" class="fas fa-shopping-cart" name="add"></button>
                    <img src="assets/images/formule.webp" alt="<?= htmlspecialchars($meal->getName()) ?>">
                    <a href="#" class="category"><?= htmlspecialchars($meal->getName()) ?></a>
                    <div class="cat"><?= htmlspecialchars($meal->getFormat()) ?></div>
                    <div class="name"><?= htmlspecialchars($meal->getName()) ?></div>
                    <div class="flex">
                        <div class="price"><?= number_format($price_ttc, 2) ?><span>€</span></div>
                        <input type="number" name="qt" class="qt" min="1" max="99" value="1">
                        <button type="submit" class="fas fa-edit"></button>
                    </div>
                    <input type="hidden" name="meal_id" value="<?= $meal->getId() ?>">
                </form>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun plat disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</section>
</body>
</html>
