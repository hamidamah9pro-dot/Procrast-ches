<?php
session_start();
include "database.php";
header('Content-Type: application/json');

try {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $contenu = $data['contenu'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "Non connecté"]);
        exit;
    }

    // 1. Insertion de la nouvelle tâche (pense aux requêtes préparées plus tard !)
    if ($contenu) {
        $sql = "INSERT INTO tasks (title, user_id) VALUES ('$contenu', $user_id)";
        $conn->query($sql);
    }

    // 2. Récupération de TOUTES les tâches
    $result = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id'");
    
    $taches = []; // On crée un tableau vide pour stocker les résultats
    while($row = $result->fetch_assoc()) {
        $taches[] = $row; // On ajoute chaque ligne de la BDD dans notre tableau
    }

    // 3. ON ENVOIE TOUT D'UN COUP (Le message + la liste des tâches)
    echo json_encode([
        "success" => true, 
        "message" => "Envoyé !",
        "tasks" => $taches // Ton JS pourra boucler sur ce tableau
    ]);

} catch (Exception $e) {
    // Note : on garde le même format de réponse même en cas d'erreur
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>