<?php
include "database.php";
header('Content-Type: application/json');
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$reponse = [
    "success" => false,
    "message" => "Une erreur est survenue"
];
$nom = $data['nom'] ?? null;
$pass = $data['pass'] ?? null;

if ($nom && $pass) {
    $sql = "SELECT * FROM users WHERE username='$nom'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    if ($nom ===  $user['username'] ){
        echo json_encode([
        "status" => "false",
        "message" => "nom d'utilisateur existant"
    ]);
    }
    else{
        $sql = "INSERT INTO users (username, password) VALUES ('$nom','$pass' )";
        $conn->query($sql);
        echo json_encode([
            "status" => "success",
            "message" => "Compte créé avec succès"
    ]);
    }
    
}
else{
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Données du formulaire manquantes"
    ]);
}



?>