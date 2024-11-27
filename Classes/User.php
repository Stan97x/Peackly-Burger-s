<?php
//ajouter setters + tableaux choix roles??

namespace App\Classes;

class User {
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
        $this->role = $role;
    }
}
