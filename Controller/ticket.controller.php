<?php
include_once('./Model/ticket.model.php');
    Class ticketController{
        public function getAll(){
            $tickets = ticket::getAll();
            include_once('./View/ticketlist.view.php');
        }
        public function getById($id){
            $tickets = ticket::getById($id);
            include_once('./View/ticketlist.view.php');
        }
        public function create(){
            $data = file_get_contents('php://input');
            $ticket = json_decode($data);
            ticket::create($ticket);
        }
        public function updateStatus($id){
            $data = json_decode(file_get_contents("php://input"), true);
            ticket::updateStatus($id, $data["statut"]);
        }
    }