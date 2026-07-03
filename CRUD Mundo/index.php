<?php
include("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Crud Mundo</title>
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Gerenciamento do Mundo</h1>

    <hr>

    <h2>Cadastrar Continente</h2>
    <form action="acoes.php" method="POST" class="formulario">
        <input type="hidden" name="acao" value="cadastrar_continente">
        <input type="text" name="nome" placeholder="Nome do continente">
        <input type="number" name="populacao" placeholder="População">
        <input type="number" name="area" placeholder="Área">
        <input type="number" name="total_paises" placeholder="Total de países">
        <button type="submit">Cadastrar Continente</button>
    </form>

    <hr>

    <h2>Cadastrar Governante</h2>
    <form action="acoes.php" method="POST" class="formulario">
        <input type="hidden" name="acao" value="cadastrar_governante">
        <input type="text" name="nome" placeholder="Nome">
        <input type="text" name="partido" placeholder="Partido político">
        <input type="date" name="nascimento">
        <input type="number" name="idade" placeholder="Idade">
        <input type="date" name="inicio">
        <input type="date" name="fim">
        <button type="submit">Cadastrar Governante</button>
    </form>

    <hr>

    <h2>Cadastrar País</h2>
    <form action="acoes.php" method="POST" class="formulario">
        <input type="hidden" name="acao" value="cadastrar_pais">
        <input type="text" name="nome" placeholder="Nome do país">
        <select name="continente_id">
            <option value="">Continente</option>
            <?php
                $resultado = mysqli_query($conexao,"SELECT * FROM continentes");
                while($linha = mysqli_fetch_assoc($resultado)){
                    echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                }
            ?>
        </select>
        <input type="number" name="populacao" placeholder="População">
        <input type="number" name="area" placeholder="Área">
        <input type="text" name="idioma" placeholder="Idioma">
        <input type="text" name="clima" placeholder="Clima">
        <input type="text" name="regime" placeholder="Regime político">
        <input type="text" name="moeda" placeholder="Moeda">
        <select name="governante_id">
            <option value="">Governante</option>
            <?php
                $resultado = mysqli_query($conexao,"SELECT * FROM governantes");
                while($linha = mysqli_fetch_assoc($resultado)){
                    echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                }
            ?>
        </select>
        <button type="submit">Cadastrar País</button>
    </form>

    <hr>

    <h2>Cadastrar Cidade</h2>
    <form action="acoes.php" method="POST" class="formulario">
        <input type="hidden" name="acao" value="cadastrar_cidade">
        <input type="text" name="nome" placeholder="Nome da cidade">
        <select name="pais_id">
            <option value="">País</option>
            <?php
                $resultado = mysqli_query($conexao,"SELECT * FROM paises");
                while($linha = mysqli_fetch_assoc($resultado)){
                    echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                }
            ?>
        </select>
        <input type="number" name="populacao" placeholder="População">
        <input type="number" name="area" placeholder="Área">
        <input type="text" name="clima" placeholder="Clima">
        <select name="governante_id">
            <option value="">Governante</option>
            <?php
                $resultado = mysqli_query($conexao,"SELECT * FROM governantes");
                while($linha = mysqli_fetch_assoc($resultado)){
                    echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                }
            ?>
        </select>
        <input type="date" name="fundacao">
        <button type="submit">Cadastrar Cidade</button>
    </form>

    <hr>

    <h2>Países cadastrados</h2>
    <?php
        $resultado = mysqli_query($conexao,"SELECT * FROM paises");
        while($linha = mysqli_fetch_assoc($resultado)){
        ?>
        <p>
            <?php echo $linha['nome']; ?>
            <form action="acoes.php" method="POST">
                <input type="hidden" name="acao" value="excluir_pais">
                <input type="hidden" name="id" value="<?php echo $linha['id']; ?>">
                <button type="submit">Excluir</button>
            </form>
        </p>
    <?php } ?>

</body>
</html>