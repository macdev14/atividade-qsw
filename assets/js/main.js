const revisarBtn = document.getElementById('revisarInscricao');
const confirmarBtn = document.getElementById('confirmarInscricao');

function verificarSelecao() {
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    const peloMenosUmSelecionado = Array.from(radioButtons).some(radio => radio.checked);

    revisarBtn.disabled = !peloMenosUmSelecionado;
    confirmarBtn.disabled = !peloMenosUmSelecionado;
}

function obterTurmasSelecionadas() {
    const turmasSelecionadas = [];

    document.querySelectorAll('input[type="radio"]:checked').forEach(function (radio) {
        const disciplina = radio.getAttribute('data-disciplina');
        const turmaId  = radio.value;
        console.log(turmasSelecionadas)
        turmasSelecionadas.push({ disciplina, turmaId });
    });

    return turmasSelecionadas;
}

function inscrever(turmasSelecionadas) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './funcoes/inscricao-turma.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = xhr.responseText;
            if (resp == "A turma está fechada.") {
                
            } else if (resp == "Inscrição realizada com sucesso!") {
                alert(resp);
            } else {
                alert(resp);
            }
        }
    };

    var dados = "turmas=" + encodeURIComponent(JSON.stringify(turmasSelecionadas));
    xhr.send(dados);
}

[revisarBtn, confirmarBtn].forEach(function (btn) {
    btn.addEventListener('click', function () {
        event.preventDefault();
        var turmasSelecionadas = obterTurmasSelecionadas();
        inscrever(turmasSelecionadas);
    });
});


document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
    radio.addEventListener('change', verificarSelecao);
});

verificarSelecao();
