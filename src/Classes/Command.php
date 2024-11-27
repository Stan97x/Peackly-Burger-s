<?php

namespace App\Classes;

use App\Classes\Traits\PriceableTrait;

class Command {
    use PriceableTrait;

    const STATUS_COMMANDE = 'commandé';
    const STATUS_PREPARATION = 'en préparation';
    const STATUS_PRETE = 'prête';
    const STATUS_LIVREE = 'livrée';

    private $id;
    private $status;
    private $datetime;
    private $preparationTime;
    private $userId;

    public function __construct($id, $status, $datetime, $preparationTime, $priceHT, $tva, $priceTTC, $userId) {
        $this->id = $id;
        $this->status = $status;
        $this->datetime = $datetime;
        $this->preparationTime = $preparationTime;
        $this->priceHT = $priceHT;
        $this->tva = $tva;
        $this->priceTTC = $priceTTC;
        $this->userId = $userId;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        if (!in_array($status, [
            self::STATUS_COMMANDE,
            self::STATUS_PREPARATION,
            self::STATUS_PRETE,
            self::STATUS_LIVREE
        ])) {
            throw new \InvalidArgumentException("Status invalide : $status");
        }
        $this->status = $status;
    }

    public function getDatetime() {
        return $this->datetime;
    }

    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }


    public function getPreparationTime() {
        return $this->preparationTime;
    }

    public function setPreparationTime($preparationTime) {
        $this->preparationTime = $preparationTime;
    }


    //rELATION Many to one donc à revoir
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }
}