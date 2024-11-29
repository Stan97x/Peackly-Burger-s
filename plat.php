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

<!-----------Special Formule---------->
<section class="products">
    <h1 class="title">Nos Plats</h1>
    <div class="box-containeur">
        <?php if (!empty($meals)) : ?>
            <?php foreach ($meals as $meal) : ?>
                <?php 
                // Calcul du prix TTC
                $price_ttc = $meal->getPriceHT() + ($meal->getPriceHT() * $meal->getTva() / 100); 
                ?>
                <form class="box">
                    <button type="button" class="fas fa-eye" name="quick_view"></button>
                    <button type="button" class="fas fa-shopping-cart" name="add"></button>
                    <img src="assets/images/formule.webp" alt="<?= htmlspecialchars($meal->getName()) ?>">
                    <a href="#" class="category"><?= htmlspecialchars($meal->getName()) ?></a>
                    <div class="cat"><?= htmlspecialchars($meal->getFormat()) ?></div>
                    <div class="name"><?= htmlspecialchars($meal->getName()) ?></div>
                    <div class="flex">
                        <div class="price"><?= number_format($price_ttc, 2) ?><span>€</span></div>
                        <p><?= htmlspecialchars(implode(', ', $meal->getAliments())) ?></p>
                        <input type="number" name="qt" class="qt" min="1" max="99" value="1">
                        <button type="button" class="btns ajouter-btn" onclick="addToCommande(<?= $meal->getId() ?>)">Ajouter</button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun plat disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</section>
</body>
<script>
    function addToCommande(mealId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'commande.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.responseText === 'success') {
                alert('Plat ajouté à la commande avec succès.');
            } else {
                alert('Plat ajouté à la commande avec succès.');  
            }
        } else {
            alert('Erreur lors de l\'ajout du plat à la commande.');
        }
    };

    xhr.send('id=' + mealId);
}
</script>

</html>
