function selecionaHoras() {
    const check = document.getElementById("dia_todo");
    const inicio = document.getElementById("hora_inicio");
    const fim = document.getElementById("hora_fim");

    if (check.checked) {

        inicio.value = "08:00"; 
        fim.value = "17:00";
        
        inicio.readOnly = true;
        fim.readOnly = true;
        
        inicio.style.backgroundColor = "#e9e9e9";
        fim.style.backgroundColor = "#e9e9e9";
    } else {
        
        inicio.readOnly = false;
        fim.readOnly = false;
        inicio.style.backgroundColor = "#fff";
        fim.style.backgroundColor = "#fff";
    }
}

document.getElementById("dia_todo").addEventListener("change", selecionaHoras);