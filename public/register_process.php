<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: register.php');
    exit();
}

$nom = trim((string)($_POST['nom'] ?? ''));
$identifiant = trim((string)($_POST['identifiant'] ?? ''));
$role = trim((string)($_POST['role'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($nom === '' || $identifiant === '' || $role === '' || $password === '') {
    header('Location: register.php?error=' . urlencode('Tous les champs sont obligatoires.'));
    exit();
}

$rolesValides = ['etudiant', 'enseignant', 'apparitaire'];
if (!in_array($role, $rolesValides, true)) {
    header('Location: register.php?error=' . urlencode('Role invalide.'));
    exit();
}

if (strlen($password) < 6) {
    header('Location: register.php?error=' . urlencode('Le mot de passe doit contenir au moins 6 caracteres.'));
    exit();
}

$db = (new Database())->getConnection();
if ($db === null) {
    header('Location: register.php?error=' . urlencode('Connexion a la base impossible.'));
    exit();
}

$check = $db->prepare('SELECT id FROM utilisateurs WHERE identifiant = :identifiant LIMIT 1');
$check->bindParam(':identifiant', $identifiant);
$check->execute();

if ($check->fetch(\PDO::FETCH_ASSOC)) {
    header('Location: register.php?error=' . urlencode('Cet identifiant existe deja.'));
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $db->prepare('INSERT INTO utilisateurs (identifiant, nom, role, mot_de_pass) VALUES (:identifiant, :nom, :role, :mot_de_pass)');
$insert->bindParam(':identifiant', $identifiant);
$insert->bindParam(':nom', $nom);
$insert->bindParam(':role', $role);
$insert->bindParam(':mot_de_pass', $hash);

if ($insert->execute()) {
    header('Location: login.html?success=1');
    exit();
}

header('Location: register.php?error=' . urlencode('Echec lors de la creation du compte.'));
exit();
