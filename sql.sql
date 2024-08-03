create database poc;
use poc;
CREATE TABLE calculo_distancia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cep_origem VARCHAR(9) NOT NULL,
    cep_destino VARCHAR(9) NOT NULL,
    distancia DECIMAL(10, 2) NOT NULL,
    data_hora_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_hora_alteracao DATETIME DEFAULT CURRENT_TIMESTAMP
);


