<?php
include("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Mundial</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Gerenciamento Geográfico Mundial</h1>

    <hr>

    <!-- CADASTRAR CONTINENTE -->
    <h2>Cadastrar Continente</h2>

    <form action="acoes.php" method="POST">

        <input type="hidden" name="acao" value="cadastrar_continente">

        <label>Nome:</label><br>
        <input type="text" name="nome"><br><br>

        <label>População:</label><br>
        <input type="number" name="populacao"><br><br>

        <label>Área (km²):</label><br>
        <input type="number" name="area"><br><br>

        <label>Total de países:</label><br>
        <input type="number" name="total_paises"><br><br>

        <button type="submit">Cadastrar Continente</button>

    </form>

    <hr>

    <!-- CADASTRAR GOVERNANTE -->
    <h2>Cadastrar Governante</h2>

    <form action="acoes.php" method="POST">

        <input type="hidden" name="acao" value="cadastrar_governante">

        <label>Nome:</label><br>
        <input type="text" name="nome"><br><br>

        <label>Partido Político:</label><br>
        <input type="text" name="partido"><br><br>

        <label>Data de Nascimento:</label><br>
        <input type="date" name="nascimento"><br><br>

        <label>Idade:</label><br>
        <input type="number" name="idade"><br><br>

        <label>Início do Mandato:</label><br>
        <input type="date" name="inicio"><br><br>

        <label>Fim do Mandato:</label><br>
        <input type="date" name="fim"><br><br>

        <button type="submit">Cadastrar Governante</button>

    </form>

    <hr>

    <!-- CADASTRAR PAÍS -->
    <h2>Cadastrar País</h2>

    <form action="acoes.php" method="POST">

        <input type="hidden" name="acao" value="cadastrar_pais">

        <label>Nome:</label><br>
        <input type="text" name="nome"><br><br>

        <label>Continente ID:</label><br>
        <input type="number" name="continente"><br><br>

        <label>População:</label><br>
        <input type="number" name="populacao"><br><br>

        <label>Área:</label><br>
        <input type="number" name="area"><br><br>

        <label>Idioma:</label><br>
        <input type="text" name="idioma"><br><br>

        <label>Governante ID:</label><br>
        <input type="number" name="governante"><br><br>

        <label>Clima:</label><br>
        <input type="text" name="clima"><br><br>

        <label>Regime Político:</label><br>
        <input type="text" name="regime"><br><br>

        <label>Moeda:</label><br>
        <input type="text" name="moeda"><br><br>

        <button type="submit">Cadastrar País</button>

    </form>

    <hr>

    <!-- CADASTRAR CIDADE -->
    <h2>Cadastrar Cidade</h2>

    <form action="acoes.php" method="POST">

        <input type="hidden" name="acao" value="cadastrar_cidade">

        <label>Nome:</label><br>
        <input type="text" name="nome"><br><br>

        <label>País ID:</label><br>
        <input type="number" name="pais"><br><br>

        <label>População:</label><br>
        <input type="number" name="populacao"><br><br>

        <label>Área:</label><br>
        <input type="number" name="area"><br><br>

        <label>Clima:</label><br>
        <input type="text" name="clima"><br><br>

        <label>Governante ID:</label><br>
        <input type="number" name="governante"><br><br>

        <label>Data de Fundação:</label><br>
        <input type="date" name="fundacao"><br><br>

        <button type="submit">Cadastrar Cidade</button>

    </form>

    <hr>

    <!-- LISTA DE PAÍSES -->
    <h2>Países Cadastrados</h2>

    <?php

    $sql = "SELECT * FROM paises";
    $resultado = mysqli_query($conexao, $sql);

    while($linha = mysqli_fetch_assoc($resultado)){

        echo "<p>";
        echo $linha['nome'];

        echo "
        <form action='acoes.php' method='POST'>

            <input type='hidden' name='acao' value='excluir_pais'>
            <input type='hidden' name='id' value='".$linha['id']."'>
            <button type='submit'>Excluir</button>

        </form>
        ";

        echo "</p>";
    }

    ?>

    <script src="script.js"></script>

</body>
</html>