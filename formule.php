<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Peackly Burger's - Nos Formules</title>
    <link rel="stylesheet" href="/assets/css/formule.css">
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
        <div class="info">
            <h1 class="text-center text-white">Nos Formules</h1>
        </div>
    </div>
</div>

<section class="products">
    <div class="box-containeur">
        <?php
        require_once __DIR__ . '/vendor/autoload.php';

        use App\Classes\Factories\ItemFactory;

        try {
            // Récupérer tous les menus
            $menus = ItemFactory::menus();
        } catch (Exception $e) {
            $menus = [];
            error_log("Erreur lors de la récupération des menus : " . $e->getMessage());
        }

        if (!empty($menus)) {
            foreach ($menus as $menu) {
                $priceHT = number_format((float)$menu->getPriceHT(), 2);
                $tva = number_format((float)$menu->getTVA(), 2);
                $name = htmlspecialchars($menu->getName());
                $type = htmlspecialchars($menu->getType());
                $format = htmlspecialchars($menu->getFormat());
                
                echo "
                <form action='' method='Post' class='box'>
                    <button type='submit' class='fas fa-eye' name='quick_view' title='Voir les détails'></button>
                    <button type='submit' class='fas fa-shopping-cart' name='add' title='Ajouter au panier'></button>
                    <img src='assets/images/formule.webp' alt='$name' class='img-fluid'>
                    <a href='#' class='category'>$name</a>
                    <div class='cat'>Type: $type</div>
                    <div class='cat'>Format: $format</div>
                    <div class='price'>Prix: $priceHT € (TVA: $tva)</div>
                    <input type='number' name='qt' class='qt' min='1' max='99' value='1' onkeypress='if(this.value.length==2)return false;'>  
                </form>";
            }
        } else {
            echo "<p class='text-white text-center'>Aucune formule disponible pour le moment.</p>";
        }
        ?>
    </div>
</section>

</body>
</html>
