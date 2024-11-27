<?php

namespace App\Classes\Abstracts;
use App\Classes\Traits\PriceableTrait;

abstract class AbstractItem {
    use PriceableTrait;
    protected $id;
    protected $name;

    public function __construct($id, $name, $priceHT, $tva) {
        $this->id = $id;
        $this->name = $name;
        $this->setPriceHT($priceHT);
        $this->setTva($tva);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
?>
