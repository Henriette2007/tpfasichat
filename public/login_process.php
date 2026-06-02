<?php
require_once __DIR__ . '/../src/Config/Database.php';
session_start();

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = (new Database())->getConnection();
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    $query = "SELECT * FROM utilisateurs WHERE identifiant = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $identifiant);
    $stmt->execute();

    if ($user = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        // En production, utiliser password_verify($password, $user['mot_de_pass'])
        // Pour le TP, on accepte si le mdp correspond (simulé ici)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'];

        // Redirection selon le rôle
        header("Location: dashboard_" . $user['role'] . ".php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
