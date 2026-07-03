<?php
header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "token" => $token // Récupère le jeton généré
]);  