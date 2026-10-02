-- =====================================================
-- Script de criação do banco de dados BD_MUNDO
-- Projeto GALD - Gerenciamento de Informações Geográficas
-- =====================================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS bd_mundo;
USE bd_mundo;

-- =====================================================
-- Tabela de CONTINENTES
-- =====================================================
CREATE TABLE continentes (
    id_continente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    populacao BIGINT DEFAULT 0,
    area_km2 BIGINT DEFAULT 0,
    total_paises INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela de GOVERNANTES
-- =====================================================
CREATE TABLE governantes (
    id_governante INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    partido_politico VARCHAR(100),
    data_nascimento DATE,
    idade INT,
    data_inicio_mandato DATE,
    data_fim_mandato DATE,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela de PAÍSES
-- =====================================================
CREATE TABLE paises (
    id_pais INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    id_continente INT NOT NULL,
    id_governante INT,
    populacao BIGINT DEFAULT 0,
    area_km2 BIGINT DEFAULT 0,
    idioma VARCHAR(100),
    clima VARCHAR(100),
    regime_politico VARCHAR(100),
    moeda VARCHAR(50),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_continente) REFERENCES continentes(id_continente) ON DELETE RESTRICT,
    FOREIGN KEY (id_governante) REFERENCES governantes(id_governante) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela de CIDADES
-- =====================================================
CREATE TABLE cidades (
    id_cidade INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    id_governante INT,
    populacao BIGINT DEFAULT 0,
    area_km2 BIGINT DEFAULT 0,
    clima VARCHAR(100),
    data_fundacao DATE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pais) REFERENCES paises(id_pais) ON DELETE CASCADE,
    FOREIGN KEY (id_governante) REFERENCES governantes(id_governante) ON DELETE SET NULL,
    UNIQUE KEY unique_cidade_pais (nome, id_pais)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela de USUÁRIOS (Para autenticação)
-- =====================================================
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nome_completo VARCHAR(150) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    primeiro_acesso BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_ultimo_acesso TIMESTAMP NULL,
    tentativas_falhas INT DEFAULT 0,
    bloqueado BOOLEAN DEFAULT FALSE,
    data_bloqueio TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela de LOGS (Para rastreamento de ações)
-- =====================================================
CREATE TABLE logs (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    acao VARCHAR(255) NOT NULL,
    tabela_afetada VARCHAR(50),
    registro_id INT,
    detalhes TEXT,
    endereco_ip VARCHAR(45),
    data_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Índices para melhor performance
-- =====================================================
CREATE INDEX idx_pais_continente ON paises(id_continente);
CREATE INDEX idx_pais_governante ON paises(id_governante);
CREATE INDEX idx_cidade_pais ON cidades(id_pais);
CREATE INDEX idx_cidade_governante ON cidades(id_governante);
CREATE INDEX idx_logs_usuario ON logs(id_usuario);
CREATE INDEX idx_logs_data ON logs(data_log);
CREATE INDEX idx_usuario_ativo ON usuarios(ativo);
CREATE INDEX idx_usuario_bloqueado ON usuarios(bloqueado);

-- =====================================================
-- Dados iniciais (opcional)
-- =====================================================

-- Inserir continentes iniciais
INSERT INTO continentes (nome, populacao, area_km2) VALUES
('América do Norte', 0, 0),
('América do Sul', 0, 0),
('Europa', 0, 0),
('Ásia', 0, 0),
('África', 0, 0),
('Oceania', 0, 0),
('Antártida', 0, 0);

-- Inserir um usuário padrão para teste (senha: admin123)
-- Nota: Em produção, use uma senha hash segura
INSERT INTO usuarios (usuario, senha, email, nome_completo, primeiro_acesso) VALUES
('admin', SHA2('admin123', 256), 'admin@gald.com', 'Administrador', TRUE);

COMMIT;