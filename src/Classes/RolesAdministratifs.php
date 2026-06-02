<?php
namespace App\Classes;

interface Convocable {
    public function envoyerConvocation($objet, $date, $lieu, $message);
}

class Doyen extends Utilisateur implements Convocable {
    public function __construct($id, $identifiant, $nom) {
        parent::__construct($id, $identifiant, $nom, 'doyen');
    }

    public function getPermissions() {
        return ['convocation_collective', 'msg_prive_confidentiel', 'lecture_valve'];
    }

    public function envoyerConvocation($objet, $date, $lieu, $message) {
        // Logique d'envoi de convocation
    }
}

class Apparitaire extends Utilisateur {
    public function __construct($id, $identifiant, $nom) {
        parent::__construct($id, $identifiant, $nom, 'apparitaire');
    }

    public function getPermissions() {
        return ['crud_valve', 'lecture_valve'];
    }
}
