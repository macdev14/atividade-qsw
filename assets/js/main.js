const revisarBtn = document.getElementById('revisarInscricao');
const confirmarBtn = document.getElementById('confirmarInscricao');

function excluirInscricao(inscricaoId, elementoVisual) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './funcoes/inscricao-crud.php', true); // Update the endpoint to your delete script
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var resp = xhr.responseText;
                if (resp.includes("Inscrição excluída com sucesso.")) {
                    // Inscrição excluída com sucesso
                    if (elementoVisual) {
                        elementoVisual.remove();
                    }
                    alert(resp);
                } else {
                    // Exibição de alerta em caso de erro
                    alert(resp);
                }
            } else {
                // Exibição de alerta em caso de erro na requisição
                alert('Erro ao excluir a inscrição. Por favor, tente novamente.');
            }
        }
    };

    var dados = "inscricao_id=" + inscricaoId; // Adjust the data you need to pass for deletion
    xhr.send(dados);
}


function limparSelecao(disciplina) {
    var radioButtons = document.getElementsByName('turma_' + disciplina);
    for (var i = 0; i < radioButtons.length; i++) {
        radioButtons[i].checked = false;
    }
}

function verificarSelecao() {
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    const peloMenosUmSelecionado = Array.from(radioButtons).some(radio => radio.checked);
    try {

        revisarBtn.disabled = !peloMenosUmSelecionado;
        confirmarBtn.disabled = !peloMenosUmSelecionado;
    } catch (error) {

    }
}

function obterTurmasSelecionadas() {
    const turmasSelecionadas = [];

    document.querySelectorAll('input[type="radio"]:checked').forEach(function (radio) {
        const disciplina = radio.getAttribute('data-disciplina');
        const turmaId = radio.value;
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
                window.location.href = 'inscricoes.php';
            } else {
                alert(resp);
            }
        }
    };

    var dados = "turmas=" + encodeURIComponent(JSON.stringify(turmasSelecionadas));
    xhr.send(dados);
}
try {
    [revisarBtn, confirmarBtn].forEach(function (btn) {
        btn.addEventListener('click', function () {
            event.preventDefault();
            var turmasSelecionadas = obterTurmasSelecionadas();
            inscrever(turmasSelecionadas);
        });
    });

} catch (error) {

}


document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
    radio.addEventListener('change', verificarSelecao);
});

verificarSelecao();
