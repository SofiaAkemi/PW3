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

// Processar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'inserir') {
        $nome = escapar_string($conn, $_POST['nome']);
        $id_continente = $_POST['id_continente'];
        $id_governante = $_POST['id_governante'] ?: 'NULL';
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        $idioma = escapar_string($conn, $_POST['idioma']);
        $clima = escapar_string($conn, $_POST['clima']);
        $regime_politico = escapar_string($conn, $_POST['regime_politico']);
        $moeda = escapar_string($conn, $_POST['moeda']);
        
        $sql_check = "SELECT id_pais FROM paises WHERE nome = '$nome'";
        if ($conn->query($sql_check)->num_rows > 0) {
            $erro = "País já existe!";
        } else {
            $sql = "INSERT INTO paises (nome, id_continente, id_governante, populacao, area_km2, idioma, clima, regime_politico, moeda) 
                   VALUES ('$nome', $id_continente, $id_governante, $populacao, $area_km2, '$idioma', '$clima', '$regime_politico', '$moeda')";
            if ($conn->query($sql)) {
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'INSERIR', 'paises', 'País $nome inserido')";
                $conn->query($sql_log);
                $sucesso = "País inserido com sucesso!";
            } else {
                $erro = "Erro ao inserir país!";
            }
        }
    } 
    elseif ($acao === 'atualizar') {
        $id = $_POST['id_pais'];
        $nome = escapar_string($conn, $_POST['nome']);
        $id_continente = $_POST['id_continente'];
        $id_governante = $_POST['id_governante'] ?: 'NULL';
        $populacao = $_POST['populacao'] ?? 0;
        $area_km2 = $_POST['area_km2'] ?? 0;
        $idioma = escapar_string($conn, $_POST['idioma']);
        $clima = escapar_string($conn, $_POST['clima']);
        $regime_politico = escapar_string($conn, $_POST['regime_politico']);
        $moeda = escapar_string($conn, $_POST['moeda']);
        
        $sql = "UPDATE paises SET nome = '$nome', id_continente = $id_continente, id_governante = $id_governante,
                populacao = $populacao, area_km2 = $area_km2, idioma = '$idioma', clima = '$clima', 
                regime_politico = '$regime_politico', moeda = '$moeda' WHERE id_pais = $id";
        if ($conn->query($sql)) {
            $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                       VALUES ($id_usuario, 'ATUALIZAR', 'paises', 'País atualizado')";
            $conn->query($sql_log);
            $sucesso = "País atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar país!";
        }
    }
    elseif ($acao === 'deletar') {
        $id = $_POST['id_pais'];
        
        $sql_check = "SELECT COUNT(*) as total FROM cidades WHERE id_pais = $id";
        $resultado = $conn->query($sql_check);
        $row = $resultado->fetch_assoc();
        
        if ($row['total'] > 0) {
            $erro = "Não é possível deletar! Existem cidades associadas a este país.";
        } else {
            $sql = "DELETE FROM paises WHERE id_pais = $id";
            if ($conn->query($sql)) {
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                           VALUES ($id_usuario, 'DELETAR', 'paises', 'País deletado')";
                $conn->query($sql_log);
                $sucesso = "País deletado com sucesso!";
            } else {
                $erro = "Erro ao deletar país!";
            }
        }
    }
}

// Buscar dados
$sql_paises = "SELECT p.*, c.nome as continente_nome, g.nome as governante_nome 
              FROM paises p 
              LEFT JOIN continentes c ON p.id_continente = c.id_continente 
              LEFT JOIN governantes g ON p.id_governante = g.id_governante 
              ORDER BY p.nome";
$resultado_paises = $conn->query($sql_paises);
$paises = [];
if ($resultado_paises) {
    while ($row = $resultado_paises->fetch_assoc()) {
        $paises[] = $row;
    }
}

$sql_continentes = "SELECT * FROM continentes ORDER BY nome";
$resultado_continentes = $conn->query($sql_continentes);
$continentes = [];
while ($row = $resultado_continentes->fetch_assoc()) {
    $continentes[] = $row;
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
    <title>GALD - Países</title>
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
                    <h2>🏳️ Gerenciar Países</h2>
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
                    <button class="btn btn-primary" onclick="abrirModalInserir()">➕ Novo País</button>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Continente</th>
                                    <th>População</th>
                                    <th>Governante</th>
                                    <th>Idioma</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($paises) > 0): ?>
                                    <?php foreach ($paises as $pais): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pais['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($pais['continente_nome'] ?? '-'); ?></td>
                                        <td><?php echo number_format($pais['populacao'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($pais['governante_nome'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($pais['idioma'] ?? '-'); ?></td>
                                        <td>
                                            <div class="actions">
                                                <button class="action-btn action-edit" 
                                                    onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($pais)); ?>)">
                                                    ✏️ Editar
                                                </button>
                                                <button class="action-btn action-delete" 
                                                    onclick="confirmarDeletar(<?php echo $pais['id_pais']; ?>)">
                                                    🗑️ Deletar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #999;">Nenhum país cadastrado</td>
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
    <div id="modalPais" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2 id="modalTitulo">Novo País</h2>
                <button type="button" class="modal-close" onclick="fecharModal()">✕</button>
            </div>

            <form method="POST" onsubmit="return validarFormulario()">
                <input type="hidden" id="modalAcao" name="acao" value="inserir">
                <input type="hidden" id="modalId" name="id_pais">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomePais">Nome do País *</label>
                        <input type="text" id="nomePais" name="nome" required placeholder="Ex: Brasil">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="continente">Continente *</label>
                            <select id="continente" name="id_continente" required>
                                <option value="">-- Selecione --</option>
                                <?php foreach ($continentes as $cont): ?>
                                    <option value="<?php echo $cont['id_continente']; ?>">
                                        <?php echo htmlspecialchars($cont['nome']); ?>
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
                            <label for="idioma">Idioma</label>
                            <input type="text" id="idioma" name="idioma" placeholder="Ex: Português">
                        </div>

                        <div class="form-group">
                            <label for="moeda">Moeda</label>
                            <input type="text" id="moeda" name="moeda" placeholder="Ex: Real">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="clima">Clima</label>
                            <input type="text" id="clima" name="clima" placeholder="Ex: Tropical">
                        </div>

                        <div class="form-group">
                            <label for="regime">Regime Político</label>
                            <input type="text" id="regime" name="regime_politico" placeholder="Ex: República">
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
                <p>Tem certeza que deseja deletar este país? Esta ação não pode ser desfeita.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" id="confirmId" name="id_pais">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModalConfirm()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInserir() {
            document.getElementById('modalTitulo').textContent = 'Novo País';
            document.getElementById('modalAcao').value = 'inserir';
            document.getElementById('modalId').value = '';
            document.getElementById('nomePais').value = '';
            document.getElementById('continente').value = '';
            document.getElementById('governante').value = '';
            document.getElementById('populacao').value = '0';
            document.getElementById('area').value = '0';
            document.getElementById('idioma').value = '';
            document.getElementById('moeda').value = '';
            document.getElementById('clima').value = '';
            document.getElementById('regime').value = '';
            document.getElementById('modalPais').classList.add('active');
        }

        function abrirModalEditar(pais) {
            document.getElementById('modalTitulo').textContent = 'Editar País';
            document.getElementById('modalAcao').value = 'atualizar';
            document.getElementById('modalId').value = pais.id_pais;
            document.getElementById('nomePais').value = pais.nome;
            document.getElementById('continente').value = pais.id_continente;
            document.getElementById('governante').value = pais.id_governante || '';
            document.getElementById('populacao').value = pais.populacao;
            document.getElementById('area').value = pais.area_km2;
            document.getElementById('idioma').value = pais.idioma || '';
            document.getElementById('moeda').value = pais.moeda || '';
            document.getElementById('clima').value = pais.clima || '';
            document.getElementById('regime').value = pais.regime_politico || '';
            document.getElementById('modalPais').classList.add('active');
        }

        function fecharModal() {
            document.getElementById('modalPais').classList.remove('active');
        }

        function confirmarDeletar(id) {
            document.getElementById('confirmId').value = id;
            document.getElementById('modalConfirm').classList.add('active');
        }

        function fecharModalConfirm() {
            document.getElementById('modalConfirm').classList.remove('active');
        }

        function validarFormulario() {
            const nome = document.getElementById('nomePais').value.trim();
            const continente = document.getElementById('continente').value;
            
            if (nome === '') {
                alert('Por favor, digite o nome do país!');
                return false;
            }
            
            if (continente === '') {
                alert('Por favor, selecione um continente!');
                return false;
            }
            
            return true;
        }

        document.getElementById('modalPais').addEventListener('click', function(e) {
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