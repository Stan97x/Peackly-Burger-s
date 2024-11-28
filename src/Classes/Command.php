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
    private $datetimeCommand;
    private $datetimeBegin;
    private $datetimeEnd;
    private $userId;

    public function __construct($id, $status, $datetimeCommand, $datetimeBegin, $datetimeEnd, $priceHT, $tva, $priceTTC, $userId) {
        $this->id = $id;
        $this->status = $status;
        $this->datetimeCommand = $datetimeCommand;
        $this->datetimeBegin = $datetimeBegin;
        $this->datetimeEnd = $datetimeEnd;
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

    public function getDatetimeCommand() {
        return $this->datetimeCommand;
    }

    public function setDatetimeCommand($datetimeCommand) {
        $this->datetimeCommand = $datetimeCommand;
    }

    public function getDatetimeBegin() {
        return $this->datetimeBegin;
    }

    public function setDatetimeBegin($datetimeBegin) {
        $this->datetimeBegin = $datetimeBegin;
    }

    public function getDatetimeEnd() {
        return $this->datetimeEnd;
    }

    public function setDatetimeEnd($datetimeEnd) {
        $this->datetimeEnd = $datetimeEnd;
    }


    //rELATION Many to one donc à revoir
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }
}