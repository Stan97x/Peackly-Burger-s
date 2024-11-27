<?php
//plat
namespace App\Classes;

use App\Classes\Abstracts\AbstractItem;

class Meal extends AbstractItem {

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
        $this->format = $format;
    }
}





