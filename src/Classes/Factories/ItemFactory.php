<?php
//centralise la création d'objets

namespace App\Classes\Factories;

use App\Classes\Meal;
use App\Classes\Menu;

class ItemFactory {
    public static function createMeal($id, $name, $format, $priceHT, $tva) {
        return new Meal($id, $name, $format, $priceHT, $tva);
    }

    public static function createMenu($id, $name, $type, $format, $priceHT, $tva) {
        return new Menu($id, $name, $priceHT, $tva, $type, $format);
    }
}
?>
