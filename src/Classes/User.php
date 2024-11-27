<?php
//ajouter tableaux choix roles??

namespace App\Classes;

class User {
    const ROLE_COOK = 'cook';
    const ROLE_MANAGEMENT = 'management';
    const ROLE_STOCK = 'stock';

    private $id;
    private $name;
    private $surname;
    private $matricule;
    private $password;
    private $role;

    public function __construct($id, $name, $surname, $matricule, $password, $role) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->matricule = $matricule;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
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

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getMatricule() {
        return $this->matricule;
    }

    public function setMatricule($matricule) {
        $this->matricule = $matricule;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $validRoles = [self::ROLE_COOK, self::ROLE_MANAGEMENT, self::ROLE_STOCK];

        if (!in_array($role, $validRoles)) {
            throw new \InvalidArgumentException("Rôle invalide : $role");
        }

        $this->role = $role;
    }
}
