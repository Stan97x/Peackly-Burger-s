<?php
//plat
namespace App\Classes;

use App\Classes\Abstracts\AbstractItem;

class Meal extends AbstractItem {
    const FORMAT_PETIT = 'petit';
    const FORMAT_GRAND = 'grand';
    private $id;
    private $name;
    private $format;

    public function __construct($id, $name, $format, $priceHT, $tva) {
        parent::__construct($id, $name, $priceHT, $tva);
        $this->format = $format;
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
}





