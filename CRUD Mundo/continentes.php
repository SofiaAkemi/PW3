<?php
session_start();
require_once('../../config/database.php');

// Verificar autenticação
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';
$operacao = '';

// Processar POST (Inserir/Atualizar/Deletar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'inserir') {
        $nome = escapar_string($conn, $_POST['nome']);
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        
        // Verificar duplicação
        $sql_check = "SELECT id_continente FROM continentes WHERE nome = '$nome'";
        if ($conn->query($sql_check)->num_rows > 0) {
            $erro = "Continente já existe!";
        } else {
            $sql = "INSERT INTO continentes (nome, populacao, area_km2) VALUES ('$nome', $populacao, $area_km2)";
            if ($conn->query($sql)) {
                // Registrar no log
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'INSERIR', 'continentes', 'Continente $nome inserido')";
                $conn->query($sql_log);
                $sucesso = "Continente inserido com sucesso!";
                $operacao = 'sucesso';
            } else {
                $erro = "Erro ao inserir continente!";
            }
        }
    } 
    elseif ($acao === 'atualizar') {
        $id = $_POST['id_continente'];
        $nome = escapar_string($conn, $_POST['nome']);
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        
        $sql = "UPDATE continentes SET nome = '$nome', populacao = $populacao, area_km2 = $area_km2 WHERE id_continente = $id";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'ATUALIZAR', 'continentes', 'Continente atualizado')";
            $conn->query($sql_log);
            $sucesso = "Continente atualizado com sucesso!";
            $operacao = 'sucesso';
        } else {
            $erro = "Erro ao atualizar continente!";
        }
    }
    elseif ($acao === 'deletar') {
        $id = $_POST['id_continente'];
        
        // Verificar se existe país associado
        $sql_check = "SELECT COUNT(*) as total FROM paises WHERE id_continente = $id";
        $resultado = $conn->query($sql_check);
        $row = $resultado->fetch_assoc();
        
        if ($row['total'] > 0) {
            $erro = "Não é possível deletar! Existem países associados a este continente.";
        } else {
            $sql = "DELETE FROM continentes WHERE id_continente = $id";
            if ($conn->query($sql)) {
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'DELETAR', 'continentes', 'Continente deletado')";
                $conn->query($sql_log);
                $sucesso = "Continente deletado com sucesso!";
                $operacao = 'sucesso';
            } else {
                $erro = "Erro ao deletar continente!";
            }
        }
    }
}

// Buscar continentes
$sql = "SELECT * FROM continentes ORDER BY nome";
$resultado = $conn->query($sql);
$continentes = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $continentes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Continentes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar (igual ao dashboard) -->
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
                        <li><a href="dashboard.php" class="menu-item">📊 Dashboard</a></li>
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
                    <h2>🌐 Gerenciar Continentes</h2>
                </div>
            </header>

            <div class="container">
                <!-- Alertas -->
                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($sucesso); ?></div>
                <?php endif; ?>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger">❌ <?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <!-- Botão para novo continente -->
                <div style="margin-bottom: 20px;">
                    <button class="btn btn-primary" onclick="abrirModalInserir()">➕ Novo Continente</button>
                </div>

                <!-- Tabela de continentes -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>População</th>
                                    <th>Área (km²)</th>
                                    <th>Data Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($continentes) > 0): ?>
                                    <?php foreach ($continentes as $continente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($continente['nome']); ?></td>
                                        <td><?php echo number_format($continente['populacao'], 0, ',', '.'); ?></td>
                                        <td><?php echo number_format($continente['area_km2'], 0, ',', '.'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($continente['data_criacao'])); ?></td>
                                        <td>
                                            <div class="actions">
                                                <button class="action-btn action-edit" 
                                                    onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($continente)); ?>)">
                                                    ✏️ Editar
                                                </button>
                                                <button class="action-btn action-delete" 
                                                    onclick="confirmarDeletar(<?php echo $continente['id_continente']; ?>)">
                                                    🗑️ Deletar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #999;">Nenhum continente cadastrado</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; 2024 GALD - Gerenciamento de Informações Geográficas | Todos os direitos reservados</p>
            </footer>
        </main>
    </div>

    <!-- Modal para Inserir/Editar -->
    <div id="modalContinente" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitulo">Novo Continente</h2>
                <button type="button" class="modal-close" onclick="fecharModal()">✕</button>
            </div>

            <form method="POST" onsubmit="return validarFormulario()">
                <input type="hidden" id="modalAcao" name="acao" value="inserir">
                <input type="hidden" id="modalId" name="id_continente">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomeContinente">Nome do Continente *</label>
                        <input type="text" id="nomeContinente" name="nome" required placeholder="Ex: América do Sul">
                    </div>

                    <div class="form-group">
                        <label for="populacao">População</label>
                        <input type="number" id="populacao" name="populacao" value="0" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label for="area">Área (km²)</label>
                        <input type="number" id="area" name="area_km2" value="0" placeholder="0">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmação de Deleção -->
    <div id="modalConfirm" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <button type="button" class="modal-close" onclick="fecharModalConfirm()">✕</button>
            </div>

            <div class="modal-body">
                <p>Tem certeza que deseja deletar este continente? Esta ação não pode ser desfeita.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" id="confirmId" name="id_continente">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModalConfirm()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInserir() {
            document.getElementById('modalTitulo').textContent = 'Novo Continente';
            document.getElementById('modalAcao').value = 'inserir';
            document.getElementById('modalId').value = '';
            document.getElementById('nomeContinente').value = '';
            document.getElementById('populacao').value = '0';
            document.getElementById('area').value = '0';
            document.getElementById('modalContinente').classList.add('active');
        }

        function abrirModalEditar(continente) {
            document.getElementById('modalTitulo').textContent = 'Editar Continente';
            document.getElementById('modalAcao').value = 'atualizar';
            document.getElementById('modalId').value = continente.id_continente;
            document.getElementById('nomeContinente').value = continente.nome;
            document.getElementById('populacao').value = continente.populacao;
            document.getElementById('area').value = continente.area_km2;
            document.getElementById('modalContinente').classList.add('active');
        }

        function fecharModal() {
            document.getElementById('modalContinente').classList.remove('active');
        }

        function confirmarDeletar(id) {
            document.getElementById('confirmId').value = id;
            document.getElementById('modalConfirm').classList.add('active');
        }

        function fecharModalConfirm() {
            document.getElementById('modalConfirm').classList.remove('active');
        }

        function validarFormulario() {
            const nome = document.getElementById('nomeContinente').value.trim();
            if (nome === '') {
                alert('Por favor, digite o nome do continente!');
                return false;
            }
            return true;
        }

        // Fechar modal ao clicar fora
        document.getElementById('modalContinente').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });

        document.getElementById('modalConfirm').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalConfirm();
            }
        });
    </script>
</body>
</html>