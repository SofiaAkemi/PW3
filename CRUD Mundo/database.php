<?php
// Configuração de conexão com o banco de dados MySQL

// Definindo constantes de configuração
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'bd_mundo');

// Criando conexão
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Verificando conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }
    
    // Configurando charset para UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit;
}

// Funções auxiliares para tratamento de erros
function verificar_conexao($conn) {
    if (!$conn) {
        return false;
    }
    return true;
}

// Função para escapar strings (proteção contra SQL Injection)
function escapar_string($conn, $string) {
    return $conn->real_escape_string($string);
}

// Função para redirecionar caso não esteja autenticado
function verificar_autenticacao() {
    session_start();
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../frontend/pages/login.php');
        exit;
    }
    
    // Verificar se é o primeiro acesso
    if (isset($_SESSION['primeiro_acesso']) && $_SESSION['primeiro_acesso'] == true) {
        if ($_SERVER['REQUEST_URI'] !== '/frontend/pages/alterar_senha.php') {
            header('Location: ../frontend/pages/alterar_senha.php?primeiro_acesso=true');
            exit;
        }
    }
}
?>