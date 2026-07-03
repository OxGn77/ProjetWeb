<?php 
include ('./model/user.model.php');
include ('./JWT/JWT.php');

class AuthController {
    
    function login(){
        try {
            $data = file_get_contents("php://input");
            $user = json_decode($data);

            // Vérification de la présence des données
            if(empty($user->email) || empty($user->password)){
                throw new Exception("Email et mot de passe requis", 400);
            }

            // Tentative de connexion via le modèle
            $authUser = UserModel::login($user->email, $user->password);

            // SI L'UTILISATEUR N'EST PAS TROUVÉ (Mots de passe faux ou email inexistant)
            if (!$authUser) {
                http_response_code(401); // Statut HTTP 401 Unauthorized
                $error_message = "Identifiants incorrects";
                include('./view/error.json.php'); // On affiche la vue d'erreur
                return;
            }

            // Si tout est ok, on génère le Token (idéalement, on ne passe pas tout l'objet $authUser brut, juste l'id et le rôle)
            $payload = [
                "id" => $authUser->id ?? null,
                "email" => $authUser->email
            ];

            $token = JWT::encode($payload, "dioebzfhfeipjapeoaj§opeao§èzjbiebpz(p1^^3éù28219631", "HS256");
            
            http_response_code(200);
            include('./view/login.json.php');

        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            $error_message = $e->getMessage();
            include('./view/error.json.php');
        }
    }

    function register(){
        try {
            $data = file_get_contents("php://input");
            $user = json_decode($data);

            if(empty($user->email) || empty($user->password)){
                throw new Exception("L'email et le mot de passe sont obligatoires", 400);
            }

            // Appel au modèle pour l'inscription
            $success = UserModel::register($user->email, $user->password);
            
            if (!$success) {
                throw new Exception("Impossible de créer le compte (cet email est peut-être déjà utilisé)", 409);
            }

            http_response_code(201); // 201 Created
            include('./view/register.json.php');

        } catch (Exception $e) {
            // Si une erreur survient, on adapte le code HTTP et on inclut la vue d'erreur
            $code = ($e->getCode() >= 400 && $e->getCode() <= 500) ? $e->getCode() : 400;
            http_response_code($code);
            $error_message = $e->getMessage();
            include('./view/error.json.php');
        }
    }
}