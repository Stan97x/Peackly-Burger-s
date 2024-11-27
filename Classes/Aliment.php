<?php
//ajouter setters 
namespace App\Classes;

class Aliment {
    private $id;
    private $name;
    private $pricePerUnitHT;
    private $pricePerKiloHT;
    private $tva;

    public function __construct($id, $name, $pricePerUnitHT, $pricePerKiloHT, $tva) {
        $this->id = $id;
        $this->name = $name;
        $this->pricePerUnitHT = $pricePerUnitHT;
        $this->pricePerKiloHT = $pricePerKiloHT;
        $this->tva = $tva;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPricePerUnitHT() {
        return $this->pricePerUnitHT;
    }

    public function getPricePerKiloHT() {
        return $this->pricePerKiloHT;
    }

    public function getTva() {
        return $this->tva;
    }
}