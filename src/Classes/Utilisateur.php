<?php
namespace App\Classes;

abstract class Utilisateur {
    protected $id;
    protected $identifiant;
    protected $nom;
    protected $role;

    public function __construct($id, $identifiant, $nom, $role) {
        $this->id = $id;
        $this->identifiant = $identifiant;
        $this->nom = $nom;
        $this->role = $role;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getRole() { return $this->role; }

    abstract public function getPermissions();
}
