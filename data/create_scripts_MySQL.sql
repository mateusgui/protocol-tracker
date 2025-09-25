CREATE TABLE usuarios (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    cpf CHAR(11) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME NOT NULL
);

CREATE TABLE protocolos (
    id VARCHAR(36) PRIMARY KEY,
    id_usuario INTEGER NOT NULL,
    numero VARCHAR(6) NOT NULL UNIQUE,
    quantidade_paginas INTEGER NOT NULL,
    observacoes TEXT,
    criado_em DATETIME NOT NULL,
    alterado_em DATETIME,
    deletado_em DATETIME,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE protocolos_auditoria (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    protocolo_id VARCHAR(36) NOT NULL,
    usuario_id INTEGER NOT NULL,
    numero_protocolo VARCHAR(6) NOT NULL,
    acao VARCHAR(20) NOT NULL,
    data_acao DATETIME NOT NULL,
    FOREIGN KEY (protocolo_id) REFERENCES protocolos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);