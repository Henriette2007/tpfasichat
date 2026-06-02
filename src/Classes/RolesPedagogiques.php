<?php
namespace App\Classes;

class Etudiant extends Utilisateur {
    private $promotion;

    public function __construct($id, $identifiant, $nom, $promotion) {
        parent::__construct($id, $identifiant, $nom, 'etudiant');
        $this->promotion = $promotion;
    }

    public function getPermissions() {
        return ['msg_prive_etudiant', 'msg_public_enseignant', 'lecture_valve'];
    }
}

class Enseignant extends Utilisateur {
    public function __construct($id, $identifiant, $nom) {
        parent::__construct($id, $identifiant, $nom, 'enseignant');
    }

    public function getPermissions() {
        return ['msg_prive_enseignant', 'mur_pedagogique', 'lecture_valve', 'reception_convocation'];
    }
}
