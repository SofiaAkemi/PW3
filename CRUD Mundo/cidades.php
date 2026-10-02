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
        $id_pais = $_POST['id_pais'];
        $id_governante = $_POST['id_governante'] ?: 'NULL';
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        $clima = escapar_string($conn, $_POST['clima']);
        $data_fundacao = $_POST['data_fundacao'] ?: 'NULL';
        
        if (!empty($_POST['data_fundacao'])) {
            $data_fundacao = "'" . $_POST['data_fundacao'] . "'";
        }
        
        $sql_check = "SELECT id_cidade FROM cidades WHERE nome = '$nome' AND id_pais = $id_pais";
        if ($conn->query($sql_check)->num_rows > 0) {
            $erro = "Cidade já existe neste país!";
        } else {
            $sql = "INSERT INTO cidades (nome, id_pais, id_governante, populacao, area_km2, clima, data_fundacao) 
                   VALUES ('$nome', $id_pais, $id_governante, $populacao, $area_km2, '$clima', $data_fundacao)";
            if ($conn->query($sql)) {
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'INSERIR', 'cidades', 'Cidade $nome inserida')";
                $conn->query($sql_log);
                $sucesso = "Cidade inserida com sucesso!";
            } else {
                $erro = "Erro ao inserir cidade!";
            }
        }
    } 
    elseif ($acao === 'atualizar') {
        $id = $_POST['id_cidade'];
        $nome = escapar_string($conn, $_POST['nome']);
        $id_pais = $_POST['id_pais'];
        $id_governante = $_POST['id_governante'] ?: 'NULL';
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        $clima = escapar_string($conn, $_POST['clima']);
        $data_fundacao = $_POST['data_fundacao'] ?: 'NULL';
        
        if (!empty($_POST['data_fundacao'])) {
            $data_fundacao = "'" . $_POST['data_fundacao'] . "'";
        }
        
        $sql = "UPDATE cidades SET nome = '$nome', id_pais = $id_pais, id_governante = $id_governante,
                populacao = $populacao, area_km2 = $area_km2, clima = '$clima', data_fundacao = $data_fundacao 
                WHERE id_cidade = $id";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'ATUALIZAR', 'cidades', 'Cidade atualizada')";
            $conn->query($sql_log);
            $sucesso = "Cidade atualizada com sucesso!";
        } else {
            $erro = "Erro ao atualizar cidade!";
        }
    }
    elseif ($acao === 'deletar') {
        $id = $_POST['id_cidade'];
        $sql = "DELETE FROM cidades WHERE id_cidade = $id";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'DELETAR', 'cidades', 'Cidade deletada')";
            $conn->query($sql_log);
            $sucesso = "Cidade deletada com sucesso!";
        } else {
            $erro = "Erro ao deletar cidade!";
        }
    }
}

// Buscar dados
$sql_cidades = "SELECT c.*, p.nome as pais_nome, g.nome as governante_nome 
               FROM cidades c 
               LEFT JOIN paises p ON c.id_pais = p.id_pais 
               LEFT JOIN governantes g ON c.id_governante = g.id_governante 
               ORDER BY c.nome";
$resultado_cidades = $conn->query($sql_cidades);
$cidades = [];
if ($resultado_cidades) {
    while ($row = $resultado_cidades->fetch_assoc()) {
        $cidades[] = $row;
    }
}

$sql_paises = "SELECT * FROM paises ORDER BY nome";
$resultado_paises = $conn->query($sql_paises);
$paises = [];
while ($row = $resultado_paises->fetch_assoc()) {
    $paises[] = $row;
}

$sql_governantes = "SELECT * FROM governantes WHERE ativo = TRUE ORDER BY nome";
$resultado_governantes = $conn->query($sql_governantes);
$governantes = [];
while ($row = $resultado_governantes->fetch_assoc()) {
    $governantes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Cidades</title>
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
                    <h2>🏙️ Gerenciar Cidades</h2>
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
                    <button class="btn btn-primary" onclick="abrirModalInserir()">➕ Nova Cidade</button>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>País</th>
                                    <th>População</th>
                                    <th>Governante</th>
                                    <th>Data Fundação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($cidades) > 0): ?>
                                    <?php foreach ($cidades as $cidade): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cidade['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($cidade['pais_nome'] ?? '-'); ?></td>
                                        <td><?php echo number_format($cidade['populacao'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($cidade['governante_nome'] ?? '-'); ?></td>
                                        <td><?php echo $cidade['data_fundacao'] ? date('d/m/Y', strtotime($cidade['data_fundacao'])) : '-'; ?></td>
                                        <td>
                                            <div class="actions">
                                                <button class="action-btn action-edit" 
                                                    onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($cidade)); ?>)">
                                                    ✏️ Editar
                                                </button>
                                                <button class="action-btn action-delete" 
                                                    onclick="confirmarDeletar(<?php echo $cidade['id_cidade']; ?>)">
                                                    🗑️ Deletar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #999;">Nenhuma cidade cadastrada</td>
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
    <div id="modalCidade" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2 id="modalTitulo">Nova Cidade</h2>
                <button type="button" class="modal-close" onclick="fecharModal()">✕</button>
            </div>

            <form method="POST" onsubmit="return validarFormulario()">
                <input type="hidden" id="modalAcao" name="acao" value="inserir">
                <input type="hidden" id="modalId" name="id_cidade">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomeCidade">Nome da Cidade *</label>
                        <input type="text" id="nomeCidade" name="nome" required placeholder="Ex: São Paulo">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="pais">País *</label>
                            <select id="pais" name="id_pais" required>
                                <option value="">-- Selecione --</option>
                                <?php foreach ($paises as $p): ?>
                                    <option value="<?php echo $p['id_pais']; ?>">
                                        <?php echo htmlspecialchars($p['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="governante">Governante</label>
                            <select id="governante" name="id_governante">
                                <option value="">-- Nenhum --</option>
                                <?php foreach ($governantes as $gov): ?>
                                    <option value="<?php echo $gov['id_governante']; ?>">
                                        <?php echo htmlspecialchars($gov['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="populacao">População</label>
                            <input type="number" id="populacao" name="populacao" value="0">
                        </div>

                        <div class="form-group">
                            <label for="area">Área (km²)</label>
                            <input type="number" id="area" name="area_km2" value="0">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="clima">Clima</label>
                            <input type="text" id="clima" name="clima" placeholder="Ex: Tropical">
                        </div>

                        <div class="form-group">
                            <label for="dataFundacao">Data de Fundação</label>
                            <input type="date" id="dataFundacao" name="data_fundacao">
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
                <p>Tem certeza que deseja deletar esta cidade? Esta ação não pode ser desfeita.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" id="confirmId" name="id_cidade">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModalConfirm()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInserir() {
            document.getElementById('modalTitulo').textContent = 'Nova Cidade';
            document.getElementById('modalAcao').value = 'inserir';
            document.getElementById('modalId').value = '';
            document.getElementById('nomeCidade').value = '';
            document.getElementById('pais').value = '';
            document.getElementById('governante').value = '';
            document.getElementById('populacao').value = '0';
            document.getElementById('area').value = '0';
            document.getElementById('clima').value = '';
            document.getElementById('dataFundacao').value = '';
            document.getElementById('modalCidade').classList.add('active');
        }

        function abrirModalEditar(cidade) {
            document.getElementById('modalTitulo').textContent = 'Editar Cidade';
            document.getElementById('modalAcao').value = 'atualizar';
            document.getElementById('modalId').value = cidade.id_cidade;
            document.getElementById('nomeCidade').value = cidade.nome;
            document.getElementById('pais').value = cidade.id_pais;
            document.getElementById('governante').value = cidade.id_governante || '';
            document.getElementById('populacao').value = cidade.populacao;
            document.getElementById('area').value = cidade.area_km2;
            document.getElementById('clima').value = cidade.clima || '';
            document.getElementById('dataFundacao').value = cidade.data_fundacao || '';
            document.getElementById('modalCidade').classList.add('active');
        }

        function fecharModal() {
            document.getElementById('modalCidade').classList.remove('active');
        }

        function confirmarDeletar(id) {
            document.getElementById('confirmId').value = id;
            document.getElementById('modalConfirm').classList.add('active');
        }

        function fecharModalConfirm() {
            document.getElementById('modalConfirm').classList.remove('active');
        }

        function validarFormulario() {
            const nome = document.getElementById('nomeCidade').value.trim();
            const pais = document.getElementById('pais').value;
            
            if (nome === '') {
                alert('Por favor, digite o nome da cidade!');
                return false;
            }
            
            if (pais === '') {
                alert('Por favor, selecione um país!');
                return false;
            }
            
            return true;
        }

        document.getElementById('modalCidade').addEventListener('click', function(e) {
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