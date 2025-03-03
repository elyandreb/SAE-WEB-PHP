<?php
session_start();
require_once 'bd/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérifier si les champs sont remplis
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Veuillez remplir tous les champs.");
        exit();
    }

    // Vérification en BDD
    $sql = "SELECT email_u, password FROM USER WHERE email_u = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) { // Vérification du hash
            $_SESSION['user'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Mot de passe incorrect.");
            exit();
        }
    } else {
        header("Location: login.php?error=Utilisateur introuvable.");
        exit();
    }
}
?>
