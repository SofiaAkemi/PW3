<?php
session_start();
require_once('../backend/database.php');

// Verificar autenticação
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$erro = '';
$sucesso = '';
$primeiro_acesso = isset($_GET['primeiro_acesso']) && $_GET['primeiro_acesso'] == 'true';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $senha_nova = $_POST['senha_nova'] ?? '';
    $senha_confirma = $_POST['senha_confirma'] ?? '';
    $id_usuario = $_SESSION['usuario_id'];
    
    // Validações
    if (empty($senha_atual)) {
        $erro = "Por favor, digite sua senha atual!";
    } else if (empty($senha_nova)) {
        $erro = "Por favor, digite uma nova senha!";
    } else if (empty($senha_confirma)) {
        $erro = "Por favor, confirme sua nova senha!";
    } else if (strlen($senha_nova) < 6) {
        $erro = "A nova senha deve ter pelo menos 6 caracteres!";
    } else if ($senha_nova !== $senha_confirma) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verificar se a senha atual está correta
        $sql = "SELECT senha FROM usuarios WHERE id_usuario = $id_usuario";
        $resultado = $conn->query($sql);
        
        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            
            if (hash('sha256', $senha_atual) === $row['senha']) {
                // Atualizar senha
                $senha_hash = hash('sha256', $senha_nova);
                $sql_update = "UPDATE usuarios SET 
                              senha = '$senha_hash',
                              primeiro_acesso = FALSE
                              WHERE id_usuario = $id_usuario";
                
                if ($conn->query($sql_update)) {
                    // Registrar no log
                    $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes) 
                               VALUES ($id_usuario, 'ALTERAR_SENHA', 'usuarios', 'Senha alterada pelo usuário')";
                    $conn->query($sql_log);
                    
                    $_SESSION['primeiro_acesso'] = false;
                    $sucesso = "Senha alterada com sucesso!";
                    
                    // Redirecionar após 2 segundos
                    header("refresh:2;url=dashboard.php");
                } else {
                    $erro = "Erro ao atualizar a senha. Tente novamente!";
                }
            } else {
                $erro = "A senha atual está incorreta!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Alterar Senha</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container-alterar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .header p {
            color: #888;
            font-size: 14px;
            line-height: 1.5;
        }

        .alerta {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alerta.erro {
            background-color: #fee;
            border-left: 4px solid #c33;
            color: #c33;
            display: block;
        }

        .alerta.sucesso {
            background-color: #efe;
            border-left: 4px solid #3c3;
            color: #3c3;
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .password-input {
            position: relative;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #667eea;
            font-size: 18px;
        }

        .requisitos {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 3px;
            font-size: 13px;
            color: #555;
        }

        .requisitos ul {
            margin-left: 20px;
            margin-top: 8px;
        }

        .requisitos li {
            margin-bottom: 5px;
        }

        .requisitos strong {
            color: #667eea;
        }

        .botoes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 25px;
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-alterar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-alterar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-sair {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-sair:hover {
            background: #e0e0e0;
        }

        .primeiro-acesso-info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 3px;
            color: #856404;
            font-size: 14px;
        }

        .primeiro-acesso-info strong {
            color: #ffc107;
        }

        @media (max-width: 480px) {
            .container-alterar {
                margin: 20px;
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .botoes {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container-alterar">
        <div class="header">
            <h1>🔐 Alterar Senha</h1>
            <p>Bem-vindo ao GALD!</p>
        </div>

        <?php if ($primeiro_acesso): ?>
            <div class="primeiro-acesso-info">
                <strong>⚠️ Primeiro Acesso:</strong> Você precisa alterar sua senha para continuar usando o sistema.
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alerta erro">
                <strong>❌ Erro:</strong> <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="alerta sucesso">
                <strong>✅ Sucesso:</strong> <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>

        <div class="requisitos">
            <strong>📋 Requisitos para a nova senha:</strong>
            <ul>
                <li>Mínimo de 6 caracteres</li>
                <li>Confirme a senha nos dois campos</li>
            </ul>
        </div>

        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="senha_atual">Senha Atual</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="senha_atual" 
                        name="senha_atual" 
                        placeholder="Digite sua senha atual"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword('senha_atual')">👁️</button>
                </div>
            </div>

            <div class="form-group">
                <label for="senha_nova">Nova Senha</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="senha_nova" 
                        name="senha_nova" 
                        placeholder="Digite uma nova senha"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword('senha_nova')">👁️</button>
                </div>
            </div>

            <div class="form-group">
                <label for="senha_confirma">Confirmar Senha</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="senha_confirma" 
                        name="senha_confirma" 
                        placeholder="Confirme sua nova senha"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword('senha_confirma')">👁️</button>
                </div>
            </div>

            <div class="botoes">
                <button type="submit" class="btn-alterar">Alterar Senha</button>
                <a href="logout.php" style="text-decoration: none;">
                    <button type="button" class="btn-sair">Sair</button>
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const button = event.target;

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }

        function validarFormulario() {
            const senhaAtual = document.getElementById('senha_atual').value;
            const senhaNova = document.getElementById('senha_nova').value;
            const senhaConfirma = document.getElementById('senha_confirma').value;

            if (senhaAtual === '') {
                alert('Por favor, digite sua senha atual!');
                return false;
            }

            if (senhaNova === '') {
                alert('Por favor, digite uma nova senha!');
                return false;
            }

            if (senhaNova.length < 6) {
                alert('A nova senha deve ter pelo menos 6 caracteres!');
                return false;
            }

            if (senhaNova !== senhaConfirma) {
                alert('As senhas não coincidem!');
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('senha_atual').focus();
        });
    </script>
</body>
</html>