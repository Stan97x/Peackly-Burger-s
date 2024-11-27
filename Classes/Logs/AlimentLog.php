<?php
//ajouter setters 
namespace App\Classes\Logs;

class AlimentLog {
    private $id;
    private $date;
    private $quantity;

    public function __construct($id, $date, $quantity) {
        $this->id = $id;
        $this->date = $date;
        $this->quantity = $quantity;
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getQuantity() {
        return $this->quantity;
    }
}