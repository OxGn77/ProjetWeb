<?php
include_once('bdd.php');
    Class ticket{
        static function getAll(){
            $bdd = getConnexion();
            $req = $bdd->prepare('SELECT * FROM ticket');
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        }
    }