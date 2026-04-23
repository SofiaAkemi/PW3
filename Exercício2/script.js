const produtos = [
    {bolo: "bCh", qtd: "bChQt", prc: 20},
    {bolo: "bBa", qtd: "bBaQt", prc: 20},
    {bolo: "bCe", qtd: "bCeQt", prc: 30},
    {bolo: "bFo", qtd: "bFoQt", prc: 25},
    {bolo: "bMi", qtd: "bMiQt", prc: 30},
    {bolo: "bLa", qtd: "bLaQt", prc: 30},
    {bolo: "bPa", qtd: "bPaQt", prc: 40}
];

produtos.forEach(p => {
    const bolosEscolhidos = document.getElementById(p.bolo);
    const quantidadeInserida = document.getElementById(p.qtd);

    bolosEscolhidos.addEventListener("change", () => {
        if (bolosEscolhidos.checked) {
            quantidadeInserida.disabled = false;
        } else {
            quantidadeInserida.disabled = true;
            quantidadeInserida.value = "";
        }
    });
});

document.getElementById("formulario").addEventListener("submit", function(event) {
    event.preventDefault();

    let total = 0;
    let algumBoloEscolhido = false;

    for (let p of produtos) {
        const boloCheck = document.getElementById(p.bolo);
        const qtdInput = document.getElementById(p.qtd);

        if (boloCheck.checked) {
            algumBoloEscolhido = true;

            let qtd = Number(qtdInput.value);

            if (!qtd || qtd <= 0) {
                alert("Quantidade inválida!");
                return;
            }

            total += p.prc * qtd;
        }
    }

    if (!algumBoloEscolhido) {
        alert("Selecione pelo menos um produto!");
        return;
    }

    const pagamento = document.querySelector('input[name="pagamento"]:checked');

    if (!pagamento) {
        alert("Escolha a forma de pagamento!");
        return;
    }

    let resultadoFinal = 0;
    let mensagem = "";

    if (pagamento.value === "À Vista") {
        resultadoFinal = total - (total * 0.085);
        mensagem = "Pagamento à vista com desconto de 8,5%";
    }
    else {
        const parcelasSelect = document.getElementById("parcelas").value;
        const parcelas = Number(parcelasSelect);

        let totalComTaxa = total + (total * 0.06) + (6.90 * parcelas);
        let valorParcela = totalComTaxa / parcelas;

        if (valorParcela < 10) {
            alert("Cada parcela deve ser no mínimo R$10,00!");
            return;
        }

        resultadoFinal = totalComTaxa;

        mensagem = `${parcelas}x de R$${valorParcela.toFixed(2)}`;
    }

    document.getElementById("lucro").innerHTML =
        `<label>Total: R$${resultadoFinal.toFixed(2)} (${mensagem})</label>`;
});