<?php
require('./src/repository/CepsDistanciaRepository.php');
class ZipCodeService {
    private $cepsDistanciaRepository;

    function __construct() {
        $this->cepsDistanciaRepository = new CepsDistanciaRepository();
    }

    public function listarDistanciaCeps(): array {
        return $this->cepsDistanciaRepository->findAll();
    }

    public function cadastrarDistancia($data) {
       
        $lat1 = $data["origem"]["location"]["coordinates"]["latitude"];
        $lon1 = $data["origem"]["location"]["coordinates"]["longitude"];
       
        $lat2 = $data["destino"]["location"]["coordinates"]["latitude"];
        $lon2 = $data["destino"]["location"]["coordinates"]["longitude"];

        $distance =  $this->calcularDistancia(
            $lat1, $lon1, $lat2, $lon2
        );

        $distance = $this->formatarDistancia($distance);
        $distanciaCalculada = [
            "cep_origem" => $data["origem"]["cep"],
            "cep_destino" => $data["destino"]["cep"],
            "distanciaKM" => $distance
        ];

        $this->cepsDistanciaRepository->putDistancia($distanciaCalculada);

        return $distance;
    }

    public function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = pow(sin($dLat / 2), 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * pow(sin($dLon / 2), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distancia = $earthRadius * $c;
      
        return $distancia;
    }
      
    public function deg2rad($deg) {
        return $deg * pi() / 180;
    }

    function formatarDistancia($distance, $decimals = 2) {
        $formattedDistance = round($distance, $decimals);
        return $formattedDistance;
    }

    public function excluirRegistro($id) {
        return $this->cepsDistanciaRepository->deletar($id);
    }

    public function importCeps($data) {
        foreach ($data as $key => $value) {
            $distance = $this->formatarDistancia($value[2]);
            $distanciaCalculada = [
                "cep_origem" => $value[0],
                "cep_destino" => $value[1],
                "distanciaKM" => $distance
            ];
    
            $this->cepsDistanciaRepository->putDistancia($distanciaCalculada);
        }
    }
}


?>