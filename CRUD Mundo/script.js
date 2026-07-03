const formularios = [
    "formContinente",
    "formGovernante",
    "formPais",
    "formCidade"
];

formularios.forEach(formulario => {

    document.getElementById(formulario)
    .addEventListener("submit", function(event){

        const campos =
            this.querySelectorAll("input[type=text]");

        for(let campo of campos){

            if(campo.value.trim() === ""){
                alert("Preencha todos os campos obrigatórios!");
                event.preventDefault();
                return;
            }

        }

        if(confirm("Deseja realmente salvar o registro?")){
            alert("Registro enviado com sucesso!");
        }
        else{
            event.preventDefault();
        }

    });

});

function excluirRegistro(id){

    if(confirm("Deseja realmente excluir este registro?")){
        window.location =
            "excluir.php?id=" + id;
    }

}