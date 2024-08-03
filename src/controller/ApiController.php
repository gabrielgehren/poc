<?php 
require_once('./src/infra/Util.php');
require_once('./src/service/ZipCodeService.php');
class ApiController {
    private $zipCodeService;

    function __construct() {
        $this->zipCodeService = new ZipCodeService();
    }

    public function listaCalculateDistante() {
        die( json_encode(
            ["resultado" => $this->zipCodeService->listarDistanciaCeps()]
        ));
    } 

    public function registerCalculateDistante() {
        $putData = json_decode(file_get_contents("php://input"), true);

        $status = $this->zipCodeService->cadastrarDistancia($putData);

        die( json_encode(
            ["resultado" => $status]
        ) );
    }

    public function excluir() {
        $putData = json_decode(file_get_contents("php://input"), true);
        $result = $this->zipCodeService->excluirRegistro($putData["id"]);
        die( json_encode(
            ["resultado" => $result]
        ) );
    }

    public function importDistancias() {
        $putData = json_decode(file_get_contents("php://input"), true);
        $result = $this->zipCodeService->importCeps($putData);
        die(json_encode(
            ["resultado" => $putData]
        ));
    }
}
?>