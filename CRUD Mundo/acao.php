<?php
    include("conexao.php");

    $acao = $_POST['acao'];

    if($acao == "cadastrar_continente"){
        mysqli_query(
            $conexao,
            "INSERT INTO tb_continentes(nm_continente,n_populacao_continente,n_area_continente,n_paises_continente)
            VALUES(
            '".$_POST['nm_continente']."',
            '".$_POST['n_populacao_continente']."',
            '".$_POST['n_area_continente']."',
            '".$_POST['n_paises_continente']."')"
        );
    }

    if($acao == "cadastrar_governante"){
        mysqli_query(
            $conexao,
            "INSERT INTO tb_governantes(nm_governante,nm_partido,dt_nascimento,n_idade,dt_inicio_mandato,dt_fim_mandato)
            VALUES(
            '".$_POST['nm_governante']."',
            '".$_POST['nm_partido']."',
            '".$_POST['dt_nascimento']."',
            '".$_POST['n_idade']."',
            '".$_POST['dt_inicio_mandato']."',
            '".$_POST['dt_fim_mandato']."')"
        );
    }

    if($acao == "cadastrar_pais"){
        mysqli_query(
            $conexao,
            "INSERT INTO tb_paises(nm_pais,n_populacao_pais,n_area_pais,nm_idioma,clima,regime_politico,moeda,cd_continente,cd_governante)
            VALUES(
            '".$_POST['nm_pais']."',
            '".$_POST['n_populacao_pais']."',
            '".$_POST['n_area_pais']."',
            '".$_POST['nm_idioma']."',
            '".$_POST['clima']."',
            '".$_POST['regime_politico']."',
            '".$_POST['moeda']."',
            '".$_POST['cd_continente']."',
            '".$_POST['cd_governante']."')"
        );
    }

    if($acao == "cadastrar_cidade"){
        mysqli_query(
            $conexao,
            "INSERT INTO cidades(nm_cidade,n_populacao_cidade,n_area_cidade,nm_clima,dt_fundacao,cd_pais,cd_governante)
            VALUES(
            '".$_POST['nome']."',
            '".$_POST['pais_id']."',
            '".$_POST['populacao']."',
            '".$_POST['area']."',
            '".$_POST['clima']."',
            '".$_POST['governante_id']."',
            '".$_POST['fundacao']."')"
        );
    }

    if($acao == "excluir_pais"){
        mysqli_query(
            $conexao,
            "DELETE FROM tb_paises
            WHERE id_pais=".$_POST['id_pais']
        );
    }

    header("Location:index.php");
?>

```php
<?php

include("conexao.php");

$acao = $_POST['acao'];


/* ==========================
   CONTINENTES
========================== */

if($acao == "cadastrar_continente"){

    $sql = "
    INSERT INTO continentes(
        nome,
        populacao,
        area,
        total_paises
    )
    VALUES(
        '".$_POST['nome']."',
        '".$_POST['populacao']."',
        '".$_POST['area']."',
        '".$_POST['total_paises']."'
    )
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "editar_continente"){

    $sql = "
    UPDATE continentes
    SET
        nome='".$_POST['nome']."',
        populacao='".$_POST['populacao']."',
        area='".$_POST['area']."',
        total_paises='".$_POST['total_paises']."'
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "excluir_continente"){

    $sql = "
    DELETE FROM continentes
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}


/* ==========================
   GOVERNANTES
========================== */

if($acao == "cadastrar_governante"){

    $sql = "
    INSERT INTO governantes(
        nome,
        partido_politico,
        data_nascimento,
        idade,
        inicio_mandato,
        fim_mandato
    )
    VALUES(
        '".$_POST['nome']."',
        '".$_POST['partido']."',
        '".$_POST['nascimento']."',
        '".$_POST['idade']."',
        '".$_POST['inicio']."',
        '".$_POST['fim']."'
    )
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "editar_governante"){

    $sql = "
    UPDATE governantes
    SET
        nome='".$_POST['nome']."',
        partido_politico='".$_POST['partido']."',
        data_nascimento='".$_POST['nascimento']."',
        idade='".$_POST['idade']."',
        inicio_mandato='".$_POST['inicio']."',
        fim_mandato='".$_POST['fim']."'
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "excluir_governante"){

    $sql = "
    DELETE FROM governantes
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}


/* ==========================
   PAÍSES
========================== */

if($acao == "cadastrar_pais"){

    $sql = "
    INSERT INTO paises(
        nome,
        continente_id,
        populacao,
        area,
        idioma,
        governante_id,
        clima,
        regime_politico,
        moeda
    )
    VALUES(
        '".$_POST['nome']."',
        '".$_POST['continente_id']."',
        '".$_POST['populacao']."',
        '".$_POST['area']."',
        '".$_POST['idioma']."',
        '".$_POST['governante_id']."',
        '".$_POST['clima']."',
        '".$_POST['regime']."',
        '".$_POST['moeda']."'
    )
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "editar_pais"){

    $sql = "
    UPDATE paises
    SET
        nome='".$_POST['nome']."',
        continente_id='".$_POST['continente_id']."',
        populacao='".$_POST['populacao']."',
        area='".$_POST['area']."',
        idioma='".$_POST['idioma']."',
        governante_id='".$_POST['governante_id']."',
        clima='".$_POST['clima']."',
        regime_politico='".$_POST['regime']."',
        moeda='".$_POST['moeda']."'
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "excluir_pais"){

    $sql = "
    DELETE FROM paises
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}


/* ==========================
   CIDADES
========================== */

if($acao == "cadastrar_cidade"){

    $sql = "
    INSERT INTO cidades(
        nome,
        pais_id,
        populacao,
        area,
        clima,
        governante_id,
        data_fundacao
    )
    VALUES(
        '".$_POST['nome']."',
        '".$_POST['pais_id']."',
        '".$_POST['populacao']."',
        '".$_POST['area']."',
        '".$_POST['clima']."',
        '".$_POST['governante_id']."',
        '".$_POST['fundacao']."'
    )
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "editar_cidade"){

    $sql = "
    UPDATE cidades
    SET
        nome='".$_POST['nome']."',
        pais_id='".$_POST['pais_id']."',
        populacao='".$_POST['populacao']."',
        area='".$_POST['area']."',
        clima='".$_POST['clima']."',
        governante_id='".$_POST['governante_id']."',
        data_fundacao='".$_POST['fundacao']."'
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}

if($acao == "excluir_cidade"){

    $sql = "
    DELETE FROM cidades
    WHERE id='".$_POST['id']."'
    ";

    mysqli_query($conexao,$sql);
}


header("Location: index.php");

?>
```
