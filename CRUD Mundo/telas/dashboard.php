<?php
session_start();
require_once('../../backend/database.php');

// Verificar autenticação
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Buscar estatísticas
$stats = [
    'continentes' => 0,
    'paises' => 0,
    'cidades' => 0,
    'governantes' => 0
];

$sql_continentes = "SELECT COUNT(*) as total FROM continentes";
$resultado = $conn->query($sql_continentes);
if ($resultado) $stats['continentes'] = $resultado->fetch_assoc()['total'];

$sql_paises = "SELECT COUNT(*) as total FROM paises";
$resultado = $conn->query($sql_paises);
if ($resultado) $stats['paises'] = $resultado->fetch_assoc()['total'];

$sql_cidades = "SELECT COUNT(*) as total FROM cidades";
$resultado = $conn->query($sql_cidades);
if ($resultado) $stats['cidades'] = $resultado->fetch_assoc()['total'];

$sql_governantes = "SELECT COUNT(*) as total FROM governantes";
$resultado = $conn->query($sql_governantes);
if ($resultado) $stats['governantes'] = $resultado->fetch_assoc()['total'];

// Buscar últimas ações do usuário
$id_usuario = $_SESSION['usuario_id'];
$sql_logs = "SELECT * FROM logs WHERE id_usuario = $id_usuario ORDER BY data_log DESC LIMIT 5";
$resultado_logs = $conn->query($sql_logs);
$logs = [];
if ($resultado_logs) {
    while ($row = $resultado_logs->fetch_assoc()) {
        $logs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Dashboard</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>🌍 GALD</h1>
                <p>Gerenciamento Geográfico</p>
            </div>

            <nav class="menu">
                <div class="menu-section">
                    <h3>Gestão de Dados</h3>
                    <ul>
                        <li><a href="continentes.php" class="menu-item">🌐 Continentes</a></li>
                        <li><a href="paises.php" class="menu-item">🏳️ Países</a></li>
                        <li><a href="cidades.php" class="menu-item">🏙️ Cidades</a></li>
                        <li><a href="governantes.php" class="menu-item">👔 Governantes</a></li>
                    </ul>
                </div>

                <div class="menu-section">
                    <h3>Configurações</h3>
                    <ul>
                        <li><a href="alterar_senha.php" class="menu-item">🔐 Alterar Senha</a></li>
                        <li><a href="logout.php" class="menu-item logout">🚪 Sair</a></li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <p>Usuário: <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong></p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-content">
                    <h2>Bem-vindo ao GALD!</h2>
                    <p>Sistema de Gerenciamento de Informações Geográficas</p>
                </div>
                <div class="header-user">
                    <span><?php echo date('d/m/Y - H:i'); ?></span>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="container">
                <!-- Estatísticas -->
                <section class="statistics">
                    <div class="stat-card">
                        <div class="stat-icon">🌐</div>
                        <div class="stat-content">
                            <h3>Continentes</h3>
                            <p class="stat-number"><?php echo $stats['continentes']; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">🏳️</div>
                        <div class="stat-content">
                            <h3>Países</h3>
                            <p class="stat-number"><?php echo $stats['paises']; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">🏙️</div>
                        <div class="stat-content">
                            <h3>Cidades</h3>
                            <p class="stat-number"><?php echo $stats['cidades']; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">👔</div>
                        <div class="stat-content">
                            <h3>Governantes</h3>
                            <p class="stat-number"><?php echo $stats['governantes']; ?></p>
                        </div>
                    </div>
                </section>

                <!-- Bem-vindo -->
                <section class="welcome-section">
                    <div class="welcome-card">
                        <h3>📋 O que você pode fazer?</h3>
                        <ul>
                            <li>✅ Gerenciar continentes, países, cidades e governantes</li>
                            <li>✅ Consultar informações geográficas completas</li>
                            <li>✅ Manter a integridade referencial dos dados</li>
                            <li>✅ Visualizar histórico de ações do sistema</li>
                            <li>✅ Gerenciar sua conta e senha</li>
                        </ul>
                    </div>

                    <div class="welcome-card">
                        <h3>🚀 Começar Agora</h3>
                        <p>Escolha uma das opções no menu lateral para gerenciar os dados geográficos do sistema.</p>
                        <div class="quick-links">
                            <a href="continentes.php" class="btn btn-primary">Gerenciar Continentes</a>
                            <a href="paises.php" class="btn btn-primary">Gerenciar Países</a>
                        </div>
                    </div>
                </section>

                <!-- Histórico de Ações -->
                <?php if (count($logs) > 0): ?>
                <section class="history-section">
                    <h3>📜 Últimas Ações</h3>
                    <div class="history-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Ação</th>
                                    <th>Tabela</th>
                                    <th>Detalhes</th>
                                    <th>Data/Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><span class="badge"><?php echo htmlspecialchars($log['acao']); ?></span></td>
                                    <td><?php echo htmlspecialchars($log['tabela_afetada'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($log['detalhes'] ?? '-'); ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($log['data_log'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Informações do Sistema -->
                <section class="info-section">
                    <div class="info-card">
                        <h3>ℹ️ Sobre o GALD</h3>
                        <p>
                            O GALD (Gerenciamento de Informações Geográficas) é um sistema desenvolvido para centralizar 
                            e gerenciar dados sobre continentes, países, cidades e governantes do mundo. Com uma interface 
                            intuitiva e funcionalidades robustas, oferece uma solução completa para controle de dados geográficos.
                        </p>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; 2024 GALD - Gerenciamento de Informações Geográficas | Todos os direitos reservados</p>
            </footer>
        </main>
    </div>
</body>
</html>