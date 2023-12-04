<!DOCTYPE html>
<?php
    include('protegido.php');
    include('conexao.php');
    require_once 'header.php';
?>

<section>
    <div class="container my-4">
    <h1>Gerenciamento do Histórico de Disciplinas</h1>

    <!-- Formulário para adicionar disciplinas ao histórico -->
    <h2>Adicionar Disciplina</h2>
    <form id="formAdicionar">
        <label for="inputDisciplina">Nome da Disciplina:</label>
        <input type="text" id="inputDisciplina" name="disciplina">
        <button type="submit">Adicionar</button>
    </form>

    <!-- Lista de disciplinas no histórico -->
    <h2>Disciplinas no Histórico</h2>
    <ul id="listaHistorico">
        <!-- As disciplinas serão exibidas aqui -->
    </ul>

    </div>

    <script>
        // Função para excluir disciplina do histórico
        function excluirDoHistorico(disciplinaId) {
            // Simular uma requisição AJAX para exclusão
            setTimeout(function() {
                alert("Excluindo disciplina com o ID: " + disciplinaId);
                // Aqui você faria a lógica de exclusão no servidor usando AJAX
                // Por exemplo, XMLHttpRequest ou fetch para fazer uma solicitação ao servidor
                // Depois de excluir no servidor, atualize a interface removendo o item da lista
                var elementoRemover = document.getElementById("disciplina-" + disciplinaId);
                if (elementoRemover) {
                    elementoRemover.remove();
                }
            }, 500); // Tempo simulado para a operação no servidor (500ms no exemplo)
        }

        // Função para adicionar disciplina ao histórico
        function adicionarAoHistorico(disciplina) {
            // Simular uma requisição AJAX para adição
            setTimeout(function() {
                alert("Adicionando disciplina: " + disciplina);
                // Aqui você faria a lógica de adição no servidor usando AJAX
                // Por exemplo, XMLHttpRequest ou fetch para fazer uma solicitação ao servidor
                // Após adicionar no servidor, atualize a interface adicionando o item à lista
                var listaHistorico = document.getElementById("listaHistorico");
                var novoItem = document.createElement("li");
                novoItem.textContent = disciplina + " ";
                var botaoExcluir = document.createElement("button");
                botaoExcluir.textContent = "Excluir";
                botaoExcluir.onclick = function() {
                    excluirDoHistorico(disciplinaId); // Chama a função de exclusão ao clicar no botão
                };
                novoItem.appendChild(botaoExcluir);
                novoItem.id = "disciplina-" + disciplinaId;
                listaHistorico.appendChild(novoItem);
            }, 500); // Tempo simulado para a operação no servidor (500ms no exemplo)
        }

        // Adicionar evento de submit ao formulário de adição
        document.getElementById('formAdicionar').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão de submit do formulário
            var disciplina = document.getElementById('inputDisciplina').value;
            adicionarAoHistorico(disciplina); // Chama a função de adição ao histórico
            document.getElementById('inputDisciplina').value = ''; // Limpa o campo após adicionar
        });

        // Exemplo de inicialização com disciplinas pré-existentes
        // var disciplinasPreexistentes = ["Matemática", "Física", "Química"];
        // disciplinasPreexistentes.forEach(function(disciplina, index) {
        //     adicionarAoHistorico(disciplina, index + 1);
        // });
    </script>
</body>
</html>
