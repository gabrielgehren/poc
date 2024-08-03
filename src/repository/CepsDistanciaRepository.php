<?php
require_once('./src/infra/Database.php');
class CepsDistanciaRepository {

    public function findAll(): array {
        $query = Database::getInstancia()->prepare('select * from calculo_distancia');
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } 

    public function putDistancia($data) {
        $sql = "INSERT INTO calculo_distancia
                    (cep_origem,cep_destino,distancia,data_hora_cadastro)
                VALUES
                    (:cep_origem,:cep_destino,:distancia,NOW())";

        $query = Database::getInstancia()->prepare($sql);
        $query->bindValue(":cep_origem",$data['cep_origem'], PDO::PARAM_STR);
        $query->bindValue(":cep_destino",$data['cep_destino'],PDO::PARAM_INT);
        $query->bindValue(":distancia",$data['distanciaKM'],PDO::PARAM_INT);

        if ($query->execute()) {
            return Database::getInstancia()->lastInsertId();
        } else {
            throw new Exception("Erro ao cadastrar processo");
        }
    }

    public function deletar($id) {
        $query = Database::getInstancia()->prepare("DELETE FROM calculo_distancia WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $query->execute();
        
        if ($result) {
            return $result;
        } else {
            throw new Exception("Erro ao excluir");
        }
    }
}
?>