<?php
session_start();
include "database.php";
header('Content-Type: application/json');

// 1. Récupération des données
$json = file_get_contents("php://input");
$data = json_decode($json, true);
$id_tache = $data['id'] ?? null;

// 2. Vérification de la session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Non autorisé !"]);
    exit; // On arrête tout ici
}

$user_id = $_SESSION['user_id'];

// 3. Suppression
if ($id_tache) {
    // Requête préparée pour la sécurité
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_tache, $user_id);

    if ($stmt->execute()) {
        // Au lieu de rediriger, on renvoie la liste à jour
        $result = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY id DESC");
        $taches = [];
        while($row = $result->fetch_assoc()) {
            $taches[] = $row;
        }

        echo json_encode([
            "success" => true,
            "message" => "Tâche supprimée",
            "tasks" => $taches // On renvoie la nouvelle liste pour le JS
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID manquant"]);
}

exit;
?>