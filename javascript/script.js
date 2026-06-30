// Controla o comportamento do checkbox "Dia Todo"
function selecionaHoras() {
    const check = document.getElementById("dia_todo");
    const inicio = document.getElementById("hora_inicio");
    const fim = document.getElementById("hora_fim");

    if (check.checked) {
        inicio.value = "08:00";
        fim.value = "17:00";

        inicio.disabled = true;
        fim.disabled = true;

        inicio.style.backgroundColor = "#e9e9e9";
        fim.style.backgroundColor = "#e9e9e9";

    } else {
        inicio.disabled = false;
        fim.disabled = false;
        inicio.style.backgroudColor = "#fff";
        fim.style.backgroundColor = "#fff"
    }
}

//Valida se o horário digitado está entre 08:00 e 17:00
function validarLimitesHorario() {
    const inicio = document.getElementById("hora_inicio").value;
    const fim = document.getElementById("hora_fim").value;

    //se ainda estiver vazio, não valida
    if (!inicio || !fim) return;

    //comparando string de hora
    if (inicio < "08:00" || inicio > "17:00" || fim < "08:00" || fim > "17:00") {
        alert("Horário inválido! Horário de funcionamento das 08:00 às 17:00");

        //limpa campos para o usuário corrigir
        document.getElementById("hora_inicio").value = "";
        document.getElementById("hora_fim").value = "";
        return;
        
    }

    //impede que a hora do fim seja menor que a hora do início.
    if (fim <= inicio) {
        alert("A hora do fim deve ser maior que a hora do início");
        document.getElementById("hora_fim").value = "";
    }
}

document.getElementById("dia_todo").addEventListener("change", selecionaHoras);
document.getElementById("hora_inicio").addEventListener("change", validarLimitesHorario);
document.getElementById("hora_fim").addEventListener("change", validarLimitesHorario);