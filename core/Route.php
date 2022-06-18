<?php


// error_reporting(0);
class Route
{
    private static $request; //variable qui va me stocké les routes


    public static function get(string $path, string $action)
    {
        $routes = new Request($path, $action);
        self::$request['GET'][] = $routes;
        return $routes; //ca me permet de faire le chainage sur mes routes "name->"
    }
    public static function post(string $path, string $action)
    {
        $routes = new Request($path, $action);
        self::$request['POST'][] = $routes;
        return $routes;
    }
    public static function run()
    {

        foreach (self::$request[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match(trim($_SERVER['REQUEST_URI'], '/'))) {
                return $route->execute();
                die();
            }
        }
        header('HTTP/1.0 404 Not found');
    }
    public static function url($name, $parameters = [])
    {
        foreach (self::$request as $key => $value) {
            foreach (self::$request[$key] as $routes) {
                if (array_key_exists($name, $routes->name())) {
                    $route = $routes->name();
                    $path = implode($route[$name]);
                    if (!empty($parameters)) {
                        foreach ($parameters as $key => $value) {
                            $path = str_replace("{{$key}}", $value, $path);
                        }
                        return '/' . $path;
                    } else {
                        return '/' . $path;
                    }
                }
            }
        }
    }
}
