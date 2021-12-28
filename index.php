<?php

use App\Services\Route;

require_once "./vendor/autoload.php";
require_once "./config.php";
$dbConnect = new PDO("mysql:host=".DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

require_once "./route.php";

error_reporting(E_ERROR | E_PARSE);

try{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(Route::start(), JSON_UNESCAPED_UNICODE);
    die();
}catch (Exception $e){
    header('Content-Type: application/json; charset=utf-8');
    http_response_code((int) $e->getCode() ?: 500);
    echo json_encode(['message' => $e->getMessage(), 'statusCode' => $e->getCode() ?: 200], JSON_UNESCAPED_UNICODE);
    die();
}