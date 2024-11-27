<?php
//ajouter setters + tableaux choix roles??
namespace App\Classes;

use App\Classes\Traits\PriceableTrait;

class Command {
    use PriceableTrait;

    private $id;
    private $date;
    private $time;
    private $preparationTime;
    private $userId;

    public function __construct($id, $date, $time, $preparationTime, $priceHT, $tva, $priceTTC, $userId) {
        $this->id = $id;
        $this->date = $date;
        $this->time = $time;
        $this->preparationTime = $preparationTime;
        $this->priceHT = $priceHT;
        $this->tva = $tva;
        $this->priceTTC = $priceTTC;
        $this->userId = $userId;
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getPreparationTime() {
        return $this->preparationTime;
    }


    //rELATION Many to one donc à revoir
    public function getUserId() {
        return $this->userId;
    }
}