<?php
//centralise la création d'objets
namespace App\Classes\Factories;

use App\Classes\Meal;
use App\Classes\Menu;
use App\Classes\Database;

class ItemFactory {
    // Crée un objet Meal
    public static function createMeal($id, $type, $name, $format, $priceHT, $tva, $sortOrder) {
        return new Meal($id,$type, $name, $format, $priceHT, $tva, $sortOrder);
    }

    // Crée un objet Menu
    public static function createMenu($id, $name, $type, $format, $priceHT, $tva, $sortOrder) {
        return new Menu($id, $name, $priceHT, $tva, $type, $format, $sortOrder);
    }

    // Récupère tous les Meals de la base de données
    public static function getMeals($type = null) {
        $pdo = Database::getConnection();
        
        // Préparer la requête avec un type facultatif
        $query = "SELECT * FROM meals";
        if ($type) {
            $query .= " WHERE type = :type";
        }
        $query .= " ORDER BY sort_order ASC";
        $stmt = $pdo->prepare($query);
        
        // Exécuter la requête
        $stmt->execute($type ? ['type' => $type] : []);
        $mealsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Transformer les données en objets Meal
        $meals = [];
        foreach ($mealsData as $mealData) {
            $meals[] = self::createMeal(
                $mealData['id'],
                $mealData['type'], 
                $mealData['name'],
                $mealData['format'],
                $mealData['price_ht'],
                $mealData['tva'],
                $mealData['sort_order'] 
            );
        }

        return $meals;
    }

    public static function updateSortOrder(array $data) {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("UPDATE meals SET sort_order = :sortOrder WHERE id = :id");

        foreach ($data as $item) {
            if (isset($item['id'], $item['sortOrder'])) {
                $stmt->execute([
                    ':sortOrder' => $item['sortOrder'],
                    ':id' => $item['id']
                ]);
            }
        }

        return ['success' => true, 'message' => 'Ordre mis à jour avec succès.'];
    }
}
