<?php
 
namespace App\Classes;

class Aliment {
    private $id;
    private $name;
    private $pricePerUnitHT;
    private $pricePerKiloHT;
    private $tva;
    private $supplier;
    private $quantity;

    public function __construct($id, $name, $pricePerUnitHT, $pricePerKiloHT, $tva, $supplier, $quantity) {
        $this->id = $id;
        $this->name = $name;
        $this->pricePerUnitHT = $pricePerUnitHT;
        $this->pricePerKiloHT = $pricePerKiloHT;
        $this->tva = $tva;
        $this->supplier = $supplier;
        $this->quantity = $quantity;
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

    public function getPricePerUnitHT() {
        return $this->pricePerUnitHT;
    }

    public function setPricePerUnitHT($pricePerUnitHT) {
        $this->pricePerUnitHT = $pricePerUnitHT;
    }

    public function getPricePerKiloHT() {
        return $this->pricePerKiloHT;
    }

    public function setPricePerKiloHT($pricePerKiloHT) {
        $this->pricePerKiloHT = $pricePerKiloHT;
    }

    public function getTva() {
        return $this->tva;
    }

    public function setTva($tva) {
        $this->tva = $tva;
    }

    public function getSupplier() { 
        return $this->supplier;
    }

    public function setSupplier($supplier) { 
        $this->supplier = $supplier;
    }

    public function getQuantity() { 
        return $this->quantity;
    }

    public function setQuantity($quantity) { 
        $this->quantity = $quantity;
    }
}