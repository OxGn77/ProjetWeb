<?php 
include ('./model/user.model.php');
include ('./JWT/JWT.php');

class AuthController {
    
    function login(){
        try {
            $data = file_get_contents("php://input");
            $user = json_decode($data);

            if(empty($user->email) || empty($user->password)){
                throw new Exception("Email et mot de passe requis", 400);
            }

            $authUser = UserModel::login($user->email, $user->password);

            if (!$authUser) {
                http_response_code(401); 
                $error_message = "Identifiants incorrects";
                include('./view/error.json.php'); 
                return;
            }

            
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

            $success = UserModel::register($user->email, $user->password);
            
            if (!$success) {
                throw new Exception("Impossible de créer le compte (cet email est peut-être déjà utilisé)", 409);
            }

            http_response_code(201); // 201 Created
            include('./view/register.json.php');

        } catch (Exception $e) {
    
            $code = ($e->getCode() >= 400 && $e->getCode() <= 500) ? $e->getCode() : 400;
            http_response_code($code);
            $error_message = $e->getMessage();
            include('./view/error.json.php');
        }
    }
}