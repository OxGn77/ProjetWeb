<?php
include_once('./Model/ticket.model.php');

class TicketController {
    
    public function getAll(){
        try {
            $tickets = ticket::getAll();
            http_response_code(200);
            include_once('./View/ticketlist.view.php');
        } catch (Exception $e) {
            http_response_code(500);
            $error_message = $e->getMessage();
            include('./View/error.json.php');
        }
    }

    public function getById($id){
        try {
            $tickets = ticket::getById($id); 
            http_response_code(200);
            include_once('./View/ticketlist.view.php');
        } catch (Exception $e) {
            http_response_code(404);
            $error_message = "Ticket introuvable";
            include('./View/error.json.php');
        }
    }

    public function create(){
        try {
            $data = file_get_contents('php://input');
            $ticket = json_decode($data);

            if (empty($ticket)) {
                throw new Exception("Données du ticket manquantes", 400);
            }

            // RÉCUPÉRATION DE L'ID USER DEPUIS LE JWT (généré dans route.php)
            if (isset($_SERVER['USER_DATA']) && isset($_SERVER['USER_DATA']->id)) {
                $ticket->user_id = $_SERVER['USER_DATA']->id; 
            } else {
                throw new Exception("Erreur d'authentification : ID utilisateur manquant", 401);
            }

            
            ticket::create($ticket);

            http_response_code(21); 
            echo json_encode(["status" => "success", "message" => "Ticket créé avec succès"]);

        } catch (Exception $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() <= 500) ? $e->getCode() : 400;
            http_response_code($code);
            $error_message = $e->getMessage();
            include('./View/error.json.php');
        }
    }
    
    public function updateStatus($id){
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (!isset($data["statut"])) {
                throw new Exception("Le champ 'statut' est requis", 400);
            }

            ticket::updateStatus($id, $data["statut"]);

            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Statut mis à jour"]);

        } catch (Exception $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() <= 500) ? $e->getCode() : 400;
            http_response_code($code);
            $error_message = $e->getMessage();
            include('./View/error.json.php');
        }
    }
}