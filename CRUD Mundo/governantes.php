<?php
session_start();
require_once('../../config/database.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'inserir') {
        $nome = escapar_string($conn, $_POST['nome']);
        $partido = escapar_string($conn, $_POST['partido_politico']);
        $data_nasc = $_POST['data_nascimento'] ?: 'NULL';
        $data_inicio = $_POST['data_inicio_mandato'] ?: 'NULL';
        $data_fim = $_POST['data_fim_mandato'] ?: 'NULL';
        
        if (!empty($_POST['data_nascimento'])) {
            $data_nasc = "'" . $_POST['data_nascimento'] . "'";
        }
        if (!empty($_POST['data_inicio_mandato'])) {
            $data_inicio = "'" . $_POST['data_inicio_mandato'] . "'";
        }
        if (!empty($_POST['data_fim_mandato'])) {
            $data_fim = "'" . $_POST['data_fim_mandato'] . "'";
        }
        
        // Calcular idade
        $idade = 0;
        if (!empty($_POST['data_nascimento'])) {
            $data_obj = new DateTime($_POST['data_nascimento']);
            $agora = new DateTime();
            $idade = $agora->diff($data_obj)->y;
        }
        
        $sql = "INSERT INTO governantes (nome, partido_politico, data_nascimento, idade, data_inicio_mandato, data_fim_mandato) 
               VALUES ('$nome', '$partido', $data_nasc, $idade, $data_inicio, $data_fim)";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'INSERIR', 'governantes', 'Governante $nome inserido')";
            $conn->query($sql_log);
            $sucesso = "Governante inserido com sucesso!";
        } else {
            $erro = "Erro ao inserir governante!";
        }
    } 
    elseif ($acao === 'atualizar') {
        $id = $_POST['id_governante'];
        $nome = escapar_string($conn, $_POST['nome']);
        $partido = escapar_string($conn, $_POST['partido_politico']);
        $data_nasc = $_POST['data_nascimento'] ?: 'NULL';
        $data_inicio = $_POST['data_inicio_mandato'] ?: 'NULL';
        $data_fim = $_POST['data_fim_mandato'] ?: 'NULL';
        
        if (!empty($_POST['data_nascimento'])) {
            $data_nasc = "'" . $_POST['data_nascimento'] . "'";
        }
        if (!empty($_POST['data_inicio_mandato'])) {
            $data_inicio = "'" . $_POST['data_inicio_mandato'] . "'";
        }
        if (!empty($_POST['data_fim_mandato'])) {
            $data_fim = "'" . $_POST['data_fim_mandato'] . "'";
        }
        
        $idade = 0;
        if (!empty($_POST['data_nascimento'])) {
            $data_obj = new DateTime($_POST['data_nascimento']);
            $agora = new DateTime();
            $idade = $agora->diff($data_obj)->y;
        }
        
        $sql = "UPDATE governantes SET nome = '$nome', partido_politico = '$partido', 
                data_nascimento = $data_nasc, idade = $idade, data_inicio_mandato = $data_inicio, 
                data_fim_mandato = $data_fim WHERE id_governante = $id";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'ATUALIZAR', 'governantes', 'Governante atualizado')";
            $conn->query($sql_log);
            $sucesso = "Governante atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar governante!";
        }
    }
    elseif ($acao === 'deletar') {
        $id = $_POST['id_governante'];
        
        $sql_check = "SELECT COUNT(*) as total FROM paises WHERE id_governante = $id UNION ALL 
                     SELECT COUNT(*) as total FROM cidades WHERE id_governante = $id";
        $resultado = $conn->query($sql_check);
        $total = 0;
        while ($row = $resultado->fetch_assoc()) {
            $total += $row['total'];
        }
        
        if ($total > 0) {
            $erro = "Não é possível deletar! Este governante está associado a países ou cidades.";
        } else {
            $sql = "DELETE FROM governantes WHERE id_governante = $id";
            if ($conn->query($sql)) {
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'DELETAR', 'governantes', 'Governante deletado')";
                $conn->query($sql_log);
                $sucesso = "Governante deletado com sucesso!";
            } else {
                $erro = "Erro ao deletar governante!";
            }
        }
    }
}

// Buscar governantes
$sql = "SELECT * FROM governantes ORDER BY nome";
$resultado = $conn->query($sql);
$governantes = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $governantes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Governantes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="layout">
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

        <main class="main-content">
            <header class="header">
                <div class="header-content">
                    <h2>👔 Gerenciar Governantes</h2>
                </div>
            </header>

            <div class="container">
                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($sucesso); ?></div>
                <?php endif; ?>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger">❌ <?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <div style="margin-bottom: 20px;">
                    <button class="btn btn-primary" onclick="abrirModalInserir()">➕ Novo Governante</button>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Idade</th>
                                    <th>Partido Político</th>
                                    <th>Início do Mandato</th>
                                    <th>Fim do Mandato</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($governantes) > 0): ?>
                                    <?php foreach ($governantes as $gov): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($gov['nome']); ?></td>
                                        <td><?php echo $gov['idade'] ?? '-'; ?></td>
                                        <td><?php echo htmlspecialchars($gov['partido_politico'] ?? '-'); ?></td>
                                        <td><?php echo $gov['data_inicio_mandato'] ? date('d/m/Y', strtotime($gov['data_inicio_mandato'])) : '-'; ?></td>
                                        <td><?php echo $gov['data_fim_mandato'] ? date('d/m/Y', strtotime($gov['data_fim_mandato'])) : '-'; ?></td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $gov['ativo'] ? '#10b981' : '#ef4444'; ?>">
                                                <?php echo $gov['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <button class="action-btn action-edit" 
                                                    onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($gov)); ?>)">
                                                    ✏️ Editar
                                                </button>
                                                <button class="action-btn action-delete" 
                                                    onclick="confirmarDeletar(<?php echo $gov['id_governante']; ?>)">
                                                    🗑️ Deletar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: #999;">Nenhum governante cadastrado</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <p>&copy; 2024 GALD - Gerenciamento de Informações Geográficas | Todos os direitos reservados</p>
            </footer>
        </main>
    </div>

    <!-- Modal Inserir/Editar -->
    <div id="modalGovernante" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2 id="modalTitulo">Novo Governante</h2>
                <button type="button" class="modal-close" onclick="fecharModal()">✕</button>
            </div>

            <form method="POST" onsubmit="return validarFormulario()">
                <input type="hidden" id="modalAcao" name="acao" value="inserir">
                <input type="hidden" id="modalId" name="id_governante">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomeGovernante">Nome *</label>
                        <input type="text" id="nomeGovernante" name="nome" required placeholder="Ex: Luiz Inácio Lula da Silva">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="partido">Partido Político</label>
                            <input type="text" id="partido" name="partido_politico" placeholder="Ex: PT">
                        </div>

                        <div class="form-group">
                            <label for="dataNasc">Data de Nascimento</label>
                            <input type="date" id="dataNasc" name="data_nascimento">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="dataInicio">Início do Mandato</label>
                            <input type="date" id="dataInicio" name="data_inicio_mandato">
                        </div>

                        <div class="form-group">
                            <label for="dataFim">Fim do Mandato</label>
                            <input type="date" id="dataFim" name="data_fim_mandato">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmação Deleção -->
    <div id="modalConfirm" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <button type="button" class="modal-close" onclick="fecharModalConfirm()">✕</button>
            </div>

            <div class="modal-body">
                <p>Tem certeza que deseja deletar este governante? Esta ação não pode ser desfeita.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" id="confirmId" name="id_governante">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModalConfirm()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInserir() {
            document.getElementById('modalTitulo').textContent = 'Novo Governante';
            document.getElementById('modalAcao').value = 'inserir';
            document.getElementById('modalId').value = '';
            document.getElementById('nomeGovernante').value = '';
            document.getElementById('partido').value = '';
            document.getElementById('dataNasc').value = '';
            document.getElementById('dataInicio').value = '';
            document.getElementById('dataFim').value = '';
            document.getElementById('modalGovernante').classList.add('active');
        }

        function abrirModalEditar(governante) {
            document.getElementById('modalTitulo').textContent = 'Editar Governante';
            document.getElementById('modalAcao').value = 'atualizar';
            document.getElementById('modalId').value = governante.id_governante;
            document.getElementById('nomeGovernante').value = governante.nome;
            document.getElementById('partido').value = governante.partido_politico || '';
            document.getElementById('dataNasc').value = governante.data_nascimento || '';
            document.getElementById('dataInicio').value = governante.data_inicio_mandato || '';
            document.getElementById('dataFim').value = governante.data_fim_mandato || '';
            document.getElementById('modalGovernante').classList.add('active');
        }

        function fecharModal() {
            document.getElementById('modalGovernante').classList.remove('active');
        }

        function confirmarDeletar(id) {
            document.getElementById('confirmId').value = id;
            document.getElementById('modalConfirm').classList.add('active');
        }

        function fecharModalConfirm() {
            document.getElementById('modalConfirm').classList.remove('active');
        }

        function validarFormulario() {
            const nome = document.getElementById('nomeGovernante').value.trim();
            
            if (nome === '') {
                alert('Por favor, digite o nome do governante!');
                return false;
            }
            
            return true;
        }

        document.getElementById('modalGovernante').addEventListener('click', function(e) {
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