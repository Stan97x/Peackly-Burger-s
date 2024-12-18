<?php
//plat
namespace App\Classes;

use App\Classes\Abstracts\AbstractItem;

class Meal extends AbstractItem {
    const TYPE_BURGER = 'burger';
    const TYPE_DRINK = 'drink';
    const TYPE_ENTREE = 'entree';
    const TYPE_DESSERT = 'dessert';

    const FORMAT_PETIT = 'petit';
    const FORMAT_GRAND = 'grand';
    private $type;
    private $format;
    private $sortOrder;
    private $aliments = [];

    public function __construct($id, $type, $name, $format,  $priceHT, $tva, $sortOrder=99) {
        parent::__construct($id, $name, $priceHT, $tva);
        $this->type = $type;
        $this->format = $format;
        $this->sortOrder = $sortOrder ?? 99;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $validTypes = [
            self::TYPE_BURGER,
            self::TYPE_DRINK,
            self::TYPE_ENTREE,
            self::TYPE_DESSERT,
        ];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        $this->type = $type;
    }
  

    public function getFormat() {
        return $this->format;
    }

    public function setFormat($format) {
        if (!in_array($format, [self::FORMAT_PETIT, self::FORMAT_GRAND])) {
            throw new \InvalidArgumentException("Format invalide : $format");
        }
        $this->format = $format;
    }

    public function getSortOrder() {
        return $this->sortOrder;
    }

    public function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }

    public function getAliments() {
        if (empty($this->aliments)) {
            $pdo = Database::getConnection();
    
            $query = "
                SELECT a.name
                FROM aliments a
                INNER JOIN meal_aliment ma ON a.id = ma.aliment_id
                WHERE ma.meal_id = :meal_id
            ";
    
            $stmt = $pdo->prepare($query);
            $stmt->execute(['meal_id' => $this->getId()]);
            $this->aliments = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }
    
        return $this->aliments;
    }
}





