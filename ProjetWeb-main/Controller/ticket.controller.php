<?php
include_once('./ticket.model.php');
    Class ticketController{
        public function getAll(){
            $tickets = ticket::getAll();
            include('./view/ticketlist.view.php');
        }
    }