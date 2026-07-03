<?php
include_once ('./bdd.php');
    
class UserModel{
    static function register($email, $password){
        $bdd = getConnexion();
        $req = $bdd->prepare("SELECT count(*) FROM user WHERE email=?");
        $req->execute(array($email));
        $count = $req->fetchColumn();

        if($count > 0){
            // Code 409 : Conflict (L'utilisateur existe déjà)
            throw new Exception("L'utilisateur existe déjà", 409);
        }

        // On remet le MD5 demandé par le prof
        $req = $bdd->prepare("INSERT INTO user(email,password) VALUES(?,?)");
        $req->execute(array($email, md5($password)));
        return true;
    }

    static function login($email, $password){
        $bdd = getConnexion();
        $req = $bdd->prepare("SELECT * FROM user WHERE email=?");
        $req->execute(array($email));
        $user = $req->fetch(PDO::FETCH_OBJ);

        // Code 401 : Unauthorized (Email introuvable)
        if (!$user){
            throw new Exception("Email ou mot de passe incorrect", 401);
        }

        // On remet la comparaison en MD5 d'origine
        if (md5($password) != $user->password){
            throw new Exception("Email ou mot de passe incorrect", 401);
        }

        // Sécurité pour le JWT : on retire le mot de passe de l'objet avant de le renvoyer
        unset($user->password);

        return $user;
    }       
}