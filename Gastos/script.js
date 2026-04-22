//Nome das categorias
const categoriasNome = {
    alimentacao: "Alimentação",
    lazer: "Lazer",
    transporte: "Transporte",
    saude: "Saúde",
    trabalho: "Trabalho",
    escola: "Escola",
    assinatura: "Assinatura",
    conta: "Conta",
    outro: "Outro"
};

//Array para armazenar os gastos
let gastos = [];

//Salvando no Local Storage
function salvarGastos(){
    localStorage.setItem("gastos", JSON.stringify(gastos));
    //Código para converter o array em texto ("stringify")
}

//Puxando do Local Storage
function carregarDados(){
    const dados = localStorage.getItem("gastos");
    //Define "dados" como os dados que temos salvos em gastos, ou seja, pega nossos dados do Local Storage

    if (dados){
        gastos = JSON.parse(dados);
    }
    //Converte tudo de texto para array caso hajam dados
}

//Adicionando um gasto
function adicionarGasto(descricao, valor, categoria){
    const gasto = {descricao, valor, categoria}; //Criamos um objeto para um gasto

    gastos.push(gasto); //Adiciona o gasto no array "gastos"
    salvarGastos(); //Puxa a função
    renderizarTela(); //Puxa a função
}

//Calculando o total dos gastos
function calcularTotalGasto(){
    let total = 0; //Cria variável para o total

    gastos.forEach(g => {
        total += Number(g.valor);
    });
    //Verifica o valor de cada gasto para somar um a um

    document.getElementById("ttl").innerHTML = "<label>O Valor Total Gasto é:<label> R$" + total.toFixed(2); //Joga isso na tela
}

//Atualizando a tela
function renderizarTela(lista = gastos){
    const ptListaGastos = document.getElementById("gastosLayout");
    ptListaGastos.innerHTML = "";

    lista.forEach((gasto) => {
        const gastoNaLista = document.createElement("div"); //Cria um div para cada gasto
        gastoNaLista.innerHTML = `
        <p>
            ${gasto.descricao}<br>
            R$${gasto.valor.toFixed(2)}<br>
            ${categoriasNome[gasto.categoria]}<br><br>
            <button type="button" onClick="removerGasto('${gasto.descricao}', ${gasto.valor}, '${gasto.categoria}')">
                Remover Gasto
            </button>
        </p>`; //Conteúdo

        ptListaGastos.appendChild(gastoNaLista); //Põe o gasto na tela
    });
    //O forEach pega o elemento e sua posição no array

    calcularTotalGasto(); //Puxa a função
}

//Removendo um gasto do Local Storage
function removerGasto(descricao, valor, categoria){
    gastos = gastos.filter(g => !(g.descricao === descricao && g.valor === valor && g.categoria === categoria)); //Filtra os gastos, selecionando o que NÃO for igual ao selecionado
    salvarGastos(); //Puxa a função
    renderizarTela(); //Puxa a função
}

//Filtrando os gastos
function filtrarGastos(categoria){
    let gastosFiltrados = []; //Array para os gastos filtrados

    switch(categoria){
        case "todos":
            gastosFiltrados = gastos;
            break;

        case "alimentacao":
            gastosFiltrados = gastos.filter(g => g.categoria === "alimentacao"); //Filtra com a categoria do g, para tudo que for igual a Alimentação
            break;

        case "lazer":
            gastosFiltrados = gastos.filter(g => g.categoria === "lazer"); //Filtra com a categoria do g, para tudo que for igual a Lazer
            break;

        case "transporte":
            gastosFiltrados = gastos.filter(g => g.categoria === "transporte"); //Filtra com a categoria do g, para tudo que for igual a Transporte
            break;

        case "saude":
            gastosFiltrados = gastos.filter(g => g.categoria === "saude"); //Filtra com a categoria do g, para tudo que for igual a Saúde
            break;

        case "trabalho":
            gastosFiltrados = gastos.filter(g => g.categoria === "trabalho"); //Filtra com a categoria do g, para tudo que for igual a Trabalho
            break;

        case "escola":
            gastosFiltrados = gastos.filter(g => g.categoria === "escola"); //Filtra com a categoria do g, para tudo que for igual a Escola
            break;

        case "assinatura":
            gastosFiltrados = gastos.filter(g => g.categoria === "assinatura"); //Filtra com a categoria do g, para tudo que for igual a Assinatura
            break;

        case "conta":
            gastosFiltrados = gastos.filter(g => g.categoria === "conta"); //Filtra com a categoria do g, para tudo que for igual a Conta
            break;

        case "outro":
            gastosFiltrados = gastos.filter(g => g.categoria === "outro"); //Filtra com a categoria do g, para tudo que for igual a Outro
            break;

        default:
            gastosFiltrados = gastos; //O pré definido, por precaução, são todos
    }

    renderizarTela(gastosFiltrados);
}

//Evento que ocorre ao enviar o formulário
document.getElementById("formGasto").addEventListener("submit", function(event) //Adiciona uma função ao receber o evento "submit"
{
    event.preventDefault(); //Evita que a página recaregue

    //Pegando dados do formuário
    const desc = document.getElementById("desc").value.trim();
    let val = document.getElementById("val").value.trim();
    const categoria = document.getElementById("categoria").value;

    val = val.replace(",", "."); //Troca , por .
    val = parseFloat(val); //Define a variável como float

    //Verificando se os valores estão corretos (que os campos estão preenchidos com os dados certos)
    if (desc === "" || isNaN(val) || val <= 0){  //NaN -> Not a Number, || -> OR
        alert("Preencha os campos corretamente!"); //alert -> mostra uma mensagem e um botão de confirmação
        return;
    }

    adicionarGasto(desc, val, categoria); //Adiciona o novo gasto

    this.reset(); //Limpa o formulário
});

//Evento que carrega dados ao abrir a página
document.addEventListener("DOMContentLoaded", function()
{
    carregarDados();
    renderizarTela();
    //Puxa as 2 funções
})