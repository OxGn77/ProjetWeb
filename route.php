<?php
    $json = file_get_contents("route.config.json");
    $routes = json_decode($json);

    $resource = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    $result = array_filter($routes,function($route) use($resource,$method){ 
        return preg_match('#^' . $route->path . '$#', $resource) && $route->method == $method; });
    
    if(count($result) == 0 || count($result) > 1){
        throw new Exception("Route not found");
    }

    $currentRoute = array_shift($result);

    preg_match('#^' . $currentRoute->path . '$#', $resource, $match);

    include_once('./controller/' . $currentRoute->controller . '.controller.php');

    $controllerName = strtoupper($currentRoute->controller[0]) . substr($currentRoute->controller,1) . 'Controller';
    $controller = new $controllerName();

    try {if (!empty($match[1])){
        $controller->{$currentRoute->action}($match[1]);
    }
    else{
        $controller->{$currentRoute->action}();
    }}
    catch (Exception $e){
        $error = $e->getMessage();
       include('./view/error.json.php');
    }
