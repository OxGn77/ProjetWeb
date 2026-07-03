<?php
    $json = file_get_contents("route.config.json");
    $routes = json_decode($json);

    $json = file_get_contents("route.config.json");
    $routes = json_decode($json);

   
    $resource = isset($_GET['url']) ? $_GET['url'] : $_SERVER['REQUEST_URI'];

    
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']); 
    if ($scriptDir !== '/' && strpos($resource, $scriptDir) === 0) {
        $resource = substr($resource, strlen($scriptDir));
    }
    $resource = explode('?', $resource)[0];
    $method = $_SERVER['REQUEST_METHOD'];

    $result = array_filter($routes, function($route) use($resource, $method){ 
        return preg_match('#^' . $route->path . '$#', $resource) && $route->method == $method; 
    });
    
    if(count($result) == 0 || count($result) > 1){
        http_response_code(404);
        $error_message = "Route not found";
        include('./view/error.json.php');
        exit;
    }

    $currentRoute = array_shift($result);

    
    if (isset($currentRoute->auth) && $currentRoute->auth === true) {
        $authHeader = null;
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) { 
            $authHeader = $headers['authorization'];
        } elseif (isset($headers['X-Auth-Token'])) { 
            $authHeader = $headers['X-Auth-Token'];
        } elseif (isset($headers['x-auth-token'])) { 
            $authHeader = $headers['x-auth-token'];
        } elseif (isset($_SERVER['HTTP_X_AUTH_TOKEN'])) { // nécessaire pour MACos
            $authHeader = $_SERVER['HTTP_X_AUTH_TOKEN'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { 
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!$authHeader) {
            http_response_code(401);
            $error_message = "Accès refusé. Token manquant dans les en-têtes.";
            include('./view/error.json.php');
            exit;
        }

        // Extraction du token (format "Bearer <token>")
        $token = str_replace('Bearer ', '', $authHeader);
        
        try {
            include_once('./JWT/JWT.php');
            
            $decodedToken = JWT::decode($token, "dioebzfhfeipjapeoaj§opeao§èzjbiebpz(p1^^3éù28219631", ["HS256"]);
           
            $_SERVER['USER_DATA'] = $decodedToken;

        } catch (Exception $e) {
            http_response_code(401);
            $error_message = "Session expirée ou Token invalide : " . $e->getMessage();
            include('./view/error.json.php');
            exit;
        }
    }

    preg_match('#^' . $currentRoute->path . '$#', $resource, $match);

    include_once('./controller/' . $currentRoute->controller . '.controller.php');

    $controllerName = strtoupper($currentRoute->controller[0]) . substr($currentRoute->controller, 1) . 'Controller';
    $controller = new $controllerName();

    try {
        if (!empty($match[1])){
            $controller->{$currentRoute->action}($match[1]);
        }
        else{
            $controller->{$currentRoute->action}();
        }
    }
    catch (Exception $e){
        http_response_code(500);
        $error_message = $e->getMessage();
        include('./view/error.json.php');
    }
