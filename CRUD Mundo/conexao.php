<?php
    $conexao = mysqli_connect(
        "localhost",
        "root",
        "",
        "db_mundo"
    );

    if(!$conexao){
        die("Erro na conexão.");
    }
?>