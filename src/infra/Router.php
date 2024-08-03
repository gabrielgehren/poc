<?php 
class Router {
    
    public static $routeList = [];

    public static function addRoute(String $method, String $path, String $controller, String $action): void {
        self::$routeList[$method][$path] = fn()=> self::loadRoute($controller, $action);
    }

    private static function loadRoute(String $controller, String $action) {
        require('./src/controller/'.$controller.'.php');
        $newInstancia =  new $controller(); 
        $newInstancia->$action();
    }

    public static function routerExec(String $method, String $path) {
        if (isset(self::$routeList[$method][$path])) {
            self::$routeList[$method][$path]();
            return true;
        } else {
            return false;
        }
    }
}
?>