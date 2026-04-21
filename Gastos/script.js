const cat = {
    alimentacao: "Alimentação",
    lazer: "Lazer",
    transporte: "Transporte",
    saude: "Saúde",
    trabalho: "Trabalho",
    escola: "Escola",
    assinatura: "Assinatura",
    conta: "Conta",
    outro: "Outro",
}

document.getElementById("formGasto").addEventListener("submit", function(event) //Adiciona uma função ao receber o evento "submit"
{
    event.preventDefault(); //Evita que a página recaregue

    const desc = document.getElementById("desc").value;
    const val = document.getElementById("val").value;
    const cat = document.getElementById("cat").value;

    //Acima, pegando as variáveis no doc

    const resultado = '${desc} - ${val} - ${cat}';

    //Seta o resultado, faz ele ser o valor retornado de acordo com a operação escolhida

    document.getElementById("resultado").innerHTML =
    `
        <p>Resultado: ${resultado}</p>
    `;

    //Joga o resultado na página
});