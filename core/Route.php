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
        //je dois parcourir toutes mes routes (elles sont stockées dans $request) pour avoir les clés et les valeurs
        //je parcours par rapport à la clé GET ou POST et par rapport à la valeur qui sera stockée
        foreach (self::$request as $key => $value) {
            //ici je parcours en utilisant la clé et j'aurai un objet de la class Request "$routes=new Request()"
            foreach (self::$request[$key] as $routes) {
                //Si la clé existe au niveau de la méthode name()
                if (array_key_exists($name, $routes->name())) {
                    $route = $routes->name(); //récupère la route
                    $path = implode($route[$name]); //récupère le path en chaine de caractère ex:/home/show
                    //on teste si le paramètre n'est pas vide - tableau
                    if (!empty($parameters)) {
                        foreach ($parameters as $key => $value) { //je parcoure le tableau
                            //je vais remplacer le paramètre {id} par sa valeur dans le path
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
