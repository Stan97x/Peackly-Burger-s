<?php
//ajouter  tableaux choix types[plat, ou menu]??
namespace App\Classes;

class CommandItem {
    private $id;
    private $type;
    private $quantity;
    private $item;//meal ou menu

    public function __construct($id, $type, $quantity, $item) {
        $this->id = $id;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->item = $item;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
// à revoir car ici relation avec le plat (meal) ou le menu et many to one
    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

}