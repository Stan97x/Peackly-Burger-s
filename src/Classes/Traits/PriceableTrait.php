<?php

namespace App\Classes\Traits;

trait PriceableTrait {
    private $priceHT;
    private $tva;
    private $priceTTC;

    public function getPriceHT() {
        return $this->priceHT;
    }

    public function setPriceHT($priceHT) {
        $this->priceHT = $priceHT;
    }

    public function getTva() {
        return $this->tva;
    }

    public function setTva($tva) {
        $this->tva = $tva;
        $this->calculatePriceTTC();
    }

    public function getPriceTTC() {
        return $this->priceTTC;
    }

    private function calculatePriceTTC() {
        $this->priceTTC = $this->priceHT + ($this->priceHT * $this->tva);
    }
}
