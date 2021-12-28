<?php

namespace App\Services;

class Route {
    static public array $routers = [];

    public static function get($route, $callback){
        self::$routers[] = ['url' => $route, 'method' => 'GET', 'callback' => $callback];
    }

    public static function post($route, $callback){
        self::$routers[] = ['url' => $route, 'method' => 'POST', 'callback' => $callback];
    }

    public static function start(){
        $requestUrl = $_SERVER['REDIRECT_URL'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        foreach(self::$routers as  $route) {
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route['url'])) . "$@D";
            preg_match('/\:[a-zA-Z0-9\_\-]+/', preg_quote($route['url']), $params);

            foreach ($params as &$param){
                $param = trim($param, ':');
            }

            if($requestMethod === $route['method'] && preg_match($pattern, $requestUrl, $matches)) {
                array_shift($matches);

                $method = explode('@', $route['callback']);
                $paramsCombine = array_combine($params, $matches);
                $queryParams = $requestMethod === 'POST' ? json_decode(file_get_contents('php://input'), true) : $_GET;

                return call_user_func_array(["App\Controllers\\".$method[0],  $method[1]], [array_merge($paramsCombine, $queryParams)]);
            }
        }
        throw new \Exception(Text::METHOD_NOT_FOUND, 404);
    }
}