const operacoes = {
    dobro(n1, n2, n3, n4){
        return n1*2
    },
    triplo(n1, n2, n3, n4){
        return n1*3
    },
    quadruplo(n1, n2, n3, n4){
        return n1*4
    },
    quintuplo(n1, n2, n3, n4){
        return n1*5
    },
    sextuplo(n1, n2, n3, n4){
        return n1*6
    },
    quadrado(n1, n2, n3, n4){
        return n1**2
    },
    cubo(n1, n2, n3, n4){
        return n1**3
    },
    quarta(n1, n2, n3, n4){
        return n1**4
    },
    quinta(n1, n2, n3, n4){
        return n1**5
    },
    sexta(n1, n2, n3, n4){
        return n1**6
    },
	baskara(n1, n2, n3, n4){
        const delta = (n2*n2)-4*n1*n3

        if (delta < 0){
            return "Não existem raízes reais."
        }

        const x1 = (-n2 + Math.sqrt(delta))/(2*n1)
        const x2 = (-n2 - Math.sqrt(delta))/(2*n1)

        return "x1 = " + x1 + " e x2 = " + x2
    },
    media(n1, n2, n3, n4){
        const media = (n1+n2+n3+n4)/4

        return media
    },
    imparpar(n1, n2, n3, n4){
        if(n1 % 2 === 0){
            return "É par"
        }
        else{
            return "É impar"
        }
    }
}

document.getElementById("formulario").addEventListener("submit", function(event) //Adiciona uma função ao receber o evento "submit"
{
    event.preventDefault(); //Evita que a página recaregue

    const operacao = document.getElementById("operacao").value;

    const n1 = Number(document.getElementById("n1").value);
    const n2 = Number(document.getElementById("n2").value);
    const n3 = Number(document.getElementById("n3").value);
    const n4 = Number(document.getElementById("n4").value);

    //Acima, pegando as variáveis no doc

    const resultado = operacoes[operacao](n1, n2, n3, n4);

    //Seta o resultado, faz ele ser o valor retornado de acordo com a operação escolhida

    document.getElementById("resultado").innerHTML =
    `
        <p>Resultado: ${resultado}</p>
    `;

    //Joga o resultado na página
});