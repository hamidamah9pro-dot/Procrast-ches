<?php
session_start();
include "database.php";
header('Content-Type: application/json');
try{
    $json = file_get_contents("php://input");
$data = json_decode($json, true);

$reponse = [
    "success" => false,
    "message" => "Une erreur est survenue"
];
$nom = $data['nom'] ?? null;
$pass = $data['pass'] ?? null;


if ($nom && $pass){
    $sql = "SELECT * FROM users WHERE username='$nom'";
    $result = $conn->query($sql);

    $user = $result->fetch_assoc();

    if ( $pass === $user['password'] ) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode([
                    "success" => true, 
                    "message" => "Connecté"
                ]);
    } else {
        echo json_encode([
                    "success" => false, 
                    "message" => "Mot de passe incorrect"
                ]);
    }
} else{
    echo json_encode([
            "success" => false, 
            "message" => "Données incomplètes"
        ]);
}
}
catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

?>