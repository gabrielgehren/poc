<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once('./src/infra/Router.php');
require_once('./src/infra/Util.php');
$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];
 
Router::addRoute('PUT','/POC/registrarDistancia','ApiController','registerCalculateDistante');
Router::addRoute('PUT','/POC/importDistances','ApiController','importDistancias');
Router::addRoute('GET','/POC/listar/all','ApiController','listaCalculateDistante');
Router::addRoute('DELETE','/POC/excluir','ApiController','excluir');

if (!Router::routerExec($method, $path)) {
    Util::redirectPageTo("/POC/administracao/filadeprocessos"); 
}
?>
