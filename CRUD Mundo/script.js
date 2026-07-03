const formularios = document.querySelectorAll(".formulario");

formularios.forEach(formulario => {

    formulario.addEventListener("submit", function(event){

        const campos = this.querySelectorAll(
            "input, select"
        );

        for(let campo of campos){

            if(
                campo.type !== "hidden" &&
                campo.value.trim() === ""
            ){
                alert("Preencha todos os campos.");
                event.preventDefault();
                return;
            }
        }

        if(
            !confirm("Deseja salvar este registro?")
        ){
            event.preventDefault();
        }

    });

});