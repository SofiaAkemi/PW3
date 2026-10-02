<?php
session_start();

// Verificar se já está logado
if (isset($_SESSION['usuario_id'])) {
    if (isset($_SESSION['primeiro_acesso']) && $_SESSION['primeiro_acesso'] == true) {
        header('Location: alterar_senha.php?primeiro_acesso=true');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}

require_once('backend/database.php');

$erro = '';
$usuario_bloqueado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = escapar_string($conn, $_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $endereco_ip = $_SERVER['REMOTE_ADDR'];
    
    // Verificar se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado = $conn->query($sql);
    
    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        
        // Verificar se o usuário está bloqueado
        if ($row['bloqueado']) {
            $erro = "Sua conta foi bloqueada. Contate o administrador!";
            $usuario_bloqueado = true;
        } else if (!$row['ativo']) {
            $erro = "Usuário desativado. Contate o administrador!";
        } else {
            // Verificar a senha
            if (hash('sha256', $senha) === $row['senha']) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $row['id_usuario'];
                $_SESSION['usuario_nome'] = $row['nome_completo'];
                $_SESSION['primeiro_acesso'] = $row['primeiro_acesso'];
                
                // Atualizar último acesso e resetar tentativas
                $sql_update = "UPDATE usuarios SET 
                              data_ultimo_acesso = NOW(),
                              tentativas_falhas = 0 
                              WHERE id_usuario = " . $row['id_usuario'];
                $conn->query($sql_update);
                
                // Registrar login no log
                $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes, endereco_ip) 
                           VALUES (" . $row['id_usuario'] . ", 'LOGIN', 'usuarios', 'Usuário realizou login', '$endereco_ip')";
                $conn->query($sql_log);
                
                // Redirecionar
                if ($row['primeiro_acesso']) {
                    header('Location: alterar_senha.php?primeiro_acesso=true');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                // Senha incorreta - incrementar tentativas
                $tentativas = $row['tentativas_falhas'] + 1;
                
                if ($tentativas >= 3) {
                    // Bloquear usuário
                    $sql_bloqueio = "UPDATE usuarios SET 
                                    bloqueado = TRUE,
                                    data_bloqueio = NOW(),
                                    tentativas_falhas = $tentativas 
                                    WHERE id_usuario = " . $row['id_usuario'];
                    $conn->query($sql_bloqueio);
                    
                    // Registrar tentativa de login falha no log
                    $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes, endereco_ip) 
                               VALUES (" . $row['id_usuario'] . ", 'LOGIN_FALHO', 'usuarios', 'Login bloqueado após 3 tentativas', '$endereco_ip')";
                    $conn->query($sql_log);
                    
                    $erro = "Conta bloqueada! Você digitou a senha errada 3 vezes. Contate o administrador.";
                    $usuario_bloqueado = true;
                } else {
                    // Atualizar tentativas
                    $sql_update = "UPDATE usuarios SET tentativas_falhas = $tentativas WHERE id_usuario = " . $row['id_usuario'];
                    $conn->query($sql_update);
                    
                    $tentativas_restantes = 3 - $tentativas;
                    $erro = "Usuário ou senha incorretos! Você tem mais $tentativas_restantes tentativa(s).";
                    
                    // Registrar tentativa de login falha no log
                    $sql_log = "INSERT INTO logs (id_usuario, acao, tabela_afetada, detalhes, endereco_ip) 
                               VALUES (" . $row['id_usuario'] . ", 'LOGIN_FALHO', 'usuarios', 'Senha incorreta - Tentativa $tentativas/3', '$endereco_ip')";
                    $conn->query($sql_log);
                }
            }
        }
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GALD - Gerenciamento Geográfico | Login</title>
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

        .container-login {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
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

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #888;
            font-size: 14px;
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

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group.erro {
            margin-top: 15px;
            padding: 12px;
            background-color: #fee;
            border: 1px solid #fcc;
            border-radius: 5px;
            color: #c33;
            font-size: 14px;
            display: none;
        }

        .form-group.erro.show {
            display: block;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-login {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 3px;
            font-size: 13px;
            color: #555;
        }

        .info-box strong {
            color: #667eea;
        }

        .password-toggle {
            position: relative;
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

        .password-toggle input {
            padding-right: 40px;
        }

        @media (max-width: 480px) {
            .container-login {
                margin: 20px;
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container-login">
        <div class="login-header">
            <h1>🌍 GALD</h1>
            <p>Gerenciamento de Informações Geográficas</p>
        </div>

        <div class="info-box">
            <strong>Demo:</strong> Usuário: <strong>admin</strong> | Senha: <strong>admin123</strong>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="form-group erro show">
                <strong>⚠️ Erro:</strong> <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="usuario">Usuário</label>
                <input 
                    type="text" 
                    id="usuario" 
                    name="usuario" 
                    placeholder="Digite seu usuário"
                    required
                    <?php echo $usuario_bloqueado ? 'disabled' : ''; ?>
                >
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="password-toggle">
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        placeholder="Digite sua senha"
                        required
                        <?php echo $usuario_bloqueado ? 'disabled' : ''; ?>
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <button 
                type="submit" 
                class="btn-login"
                <?php echo $usuario_bloqueado ? 'disabled' : ''; ?>
            >
                Entrar
            </button>
        </form>

        <div class="footer-login">
            <p>© 2024 GALD - Todos os direitos reservados</p>
        </div>
    </div>

    <script>
        function validarFormulario() {
            const usuario = document.getElementById('usuario').value.trim();
            const senha = document.getElementById('senha').value;

            if (usuario === '') {
                alert('Por favor, digite seu usuário!');
                return false;
            }

            if (senha === '') {
                alert('Por favor, digite sua senha!');
                return false;
            }

            if (senha.length < 3) {
                alert('A senha deve ter pelo menos 3 caracteres!');
                return false;
            }

            return true;
        }

        function togglePassword() {
            const senhaInput = document.getElementById('senha');
            const toggleBtn = document.querySelector('.toggle-btn');

            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                senhaInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Auto-focus no campo de usuário
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('usuario').focus();
        });
    </script>
</body>
</html>