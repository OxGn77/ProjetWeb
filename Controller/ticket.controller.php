<?php
include_once('./Model/ticket.model.php');
    Class ticketController{
        public function getAll(){
            $tickets = ticket::getAll();
           header('Content-Type: application/json');
        echo json_encode($tickets);
        }
    }