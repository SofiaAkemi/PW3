<?php
session_start();
require_once('backend/database.php');

// Registrar logout no log
if (isset($_SESSION['usuario_id'])) {
    $id_usuario = $_SESSION['usuario_id'];
    $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes, endereco_ip) 
               VALUES ($id_usuario, 'LOGOUT', 'usuarios', 'Usuário realizou logout', '" . $_SERVER['REMOTE_ADDR'] . "')";
    $conn->query($sql_log);
}

// Destruir a sessão
session_destroy();

// Redirecionar para login
header('Location: login.php');
exit;
?>