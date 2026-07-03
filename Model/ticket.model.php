<?php
include_once('bdd.php');
    Class ticket{
        static function getAll(){
            $bdd = getConnexion();
            $req = $bdd->prepare('SELECT * FROM ticket');
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        }
        static function getById($id){
            $bdd = getConnexion();
            $req = $bdd->prepare('SELECT * FROM ticket WHERE id = ?');
            $req->execute(array($id));
            return $req->fetch(PDO::FETCH_OBJ);
        }
        static function create($ticket){
            $bdd = getConnexion();
            $req = $bdd->prepare('INSERT INTO ticket (titre, categorie, priorite, statut) VALUES (?, ?, ?, ?)');
            $req->execute(array($ticket->titre, $ticket->categorie, $ticket->priorite, $ticket->statut));
            return $req->fetch();
        }
        static function updateStatus($id, $statut){
            $bdd = getConnexion();
            $req = $bdd->prepare('UPDATE ticket SET statut = ? WHERE id = ?');
            $req->execute(array($statut, $id));
            return $req->rowCount() > 0;
        }
    }
