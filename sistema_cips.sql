CREATE DATABASE sistema_cips;
USE sistema_cips;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL -- Guardaremos o hash da senha aqui!
);

CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT NOT NULL,
    id_usuario INT NOT NULL,
    data_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    FOREIGN KEY (id_sala) REFERENCES salas(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO usuarios (nome, email, senha) 
VALUES ('Renan Teste', 'renan@teste.com', '123456');

INSERT INTO salas (nome) VALUES 
('Espaço Relax'), 
('Mídiateca'), 
('Sala de espera'), 
('Sala de jogos 1'),
('Sala de jogos 2'),
('Laboratório de Informática'),
('Parque'),
('Campo'),
('Auditório Espaço Vida'),
('Auditório Nelson Elias'),
('Laboratório Prático'),
('Sala Tecnológica');

