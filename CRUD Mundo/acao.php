<?php

include("conexao.php");

$acao = $_POST['acao'];

if($acao == "cadastrar_pais"){

    $nome = $_POST['nome'];
    $continente = $_POST['continente'];

    $sql = "
    INSERT INTO paises(nome, continente_id)
    VALUES('$nome', '$continente')
    ";

    mysqli_query($conexao, $sql);
}

if($acao == "cadastrar_cidade"){

    $nome = $_POST['nome'];
    $pais = $_POST['pais'];

    $sql = "
    INSERT INTO cidades(nome, pais_id)
    VALUES('$nome', '$pais')
    ";

    mysqli_query($conexao, $sql);
}

if($acao == "excluir_pais"){

    $id = $_POST['id'];

    $sql = "
    DELETE FROM paises
    WHERE id = '$id'
    ";

    mysqli_query($conexao, $sql);
}

if($acao == "editar_pais"){

    $id = $_POST['id'];
    $nome = $_POST['nome'];

    $sql = "
    UPDATE paises
    SET nome='$nome'
    WHERE id='$id'
    ";

    mysqli_query($conexao, $sql);
}

header("Location: index.php");

?>