<?php
//ajouter  tableaux choix types[plat, ou menu]??
namespace App\Classes;

class CommandItem {
    private $id;
    private $quantity;
    private $meal; 
    private $menu;

    public function __construct($id, $quantity, $meal = null, $menu = null) {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->meal = $meal;
        $this->menu = $menu;

        if ($meal !== null && $menu !== null) {
            throw new \InvalidArgumentException("Un CommandItem ne peut pas être à la fois un meal et un menu.");
        }

        if ($meal === null && $menu === null) {
            throw new \InvalidArgumentException("Un CommandItem doit être soit un meal, soit un menu.");
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getMeal() {
        return $this->meal;
    }

    public function setMeal($meal) {
        if ($this->menu !== null) {
            throw new \InvalidArgumentException("Ce CommandItem est déjà associé à un menu.");
        }
        $this->meal = $meal;
    }

    public function getMenu() {
        return $this->menu;
    }

    public function setMenu($menu) {
        if ($this->meal !== null) {
            throw new \InvalidArgumentException("Ce CommandItem est déjà associé à un meal.");
        }
        $this->menu = $menu;
    }
}