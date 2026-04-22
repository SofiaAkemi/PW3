//Array para armazenar os gastos
let gastos = [];

//Salvando no Local Storage
function salvarGastos(){
    localStorage.setItem("gastos", JSON.stringify(gastos));
    //Código para converter o array em texto ("stringify")
}

//Adicionando um gasto
function adicionarGasto(descricao, valor, categoria){
    const gasto = {descricao: descricao, valor: valor, categoria: categoria}; //Criamos um objeto para um gasto

    gastos.push(gasto); //Adiciona o gasto no array "gastos"
    salvarGastos(); //Puxa a função
    renderizarTela(); //Puxa a função
}

//Calculando o total dos gastos
function calcularTotalGasto(){
    let total = 0; //Cria variável para o total

    gastos.forEach(g => {
        total += g.valor;
    });
    //Verifica o valor de cada gasto para somar um a um

    document.getElementById("ttl").innerHTML = "O Valor Total Gasto é: R$" + total.toFixed(2); //Joga isso na tela
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

//Atualizando a tela
function renderizarTela(lista = gastos){
    const ptListaGastos = document.getElementById("gastosLayout");
    ptListaGastos.innerHTML = "";

    lista.forEach((gasto, index) => {
        const gastoNaLista = document.createElement("div"); //Cria um div para cada gasto
        gastoNaLista.innerHTML = `<p>${descricao}<br>R$${valor}<br>${categoria}</p>`; //Conteúdo
        ptListaGastos.appendChild(gastoNaLista); //Põe o gasto na tela
    });
    //O forEach pega: (gasto(ou seja, o elemento), index(posição no array))

    calcularTotalGasto(); //Puxa a função
}

//Removendo um gasto do Local Storage
function removerGasto(index){
    gastos.splice(index, 1); //O splice remove 1 item, começando no index
    salvarGastos(); //Puxa a função
    renderizarTela(); //Puxa a função
}

//Filtrando os gastos
function filtrarGastos(){
    let gastosFiltrados = []; //Array para os gastos filtrados

    //A estrutura switch
    switch(categoria){
        //Caso seja escolhido "Todos"
        case "todos":
            gastosFiltrados = gastos;
            break;

        //Caso seja escolhido algum dos outros
        case "alimentacao":
        case "lazer":
        case "transporte":
        case "saude":
        case "trabalho":
        case "escola":
        case "assinatura":
        case "conta":
        case "outro":
            gastosFiltrados = gastos.filter(g => g.categoria === categoria); //=== verifica se é o mesmo valor e tipo
            break;

        //Por precaução, no caso de algum problema
        default:
            gastosFiltrados = gastos;
    }

    renderizarTela(gastosFiltrados); //Carrega somente os gastos desejados
}

//Evento que ocorre ao enviar o formulário
document.getElementById("formGasto").addEventListener("submit", function(event) //Adiciona uma função ao receber o evento "submit"
{
    event.preventDefault(); //Evita que a página recaregue

    //Pegando dados do formuário
    const desc = document.getElementById("desc").value;
    let val = document.getElementById("val").value;
    const categoria = document.getElementById("cat").value;

    val = Number(val.replace(",", ".")); //Troca , por . e define a variável como número

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