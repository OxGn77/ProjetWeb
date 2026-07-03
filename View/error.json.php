<?php
header('Content-Type: application/json');
echo json_encode([
    "status" => "error",
    "message" => $error_message // Récupère le message du contrôleur
]);