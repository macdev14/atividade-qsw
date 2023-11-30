<?php
    include('protegido.php'); // Inclua o arquivo de proteção, se necessário
    include('conexao.php'); // Inclua o arquivo de conexão com o banco de dados
    require_once 'header.php'; // Inclua o cabeçalho
    $usuario_id = $_SESSION['id']; 

    // Consulta para obter as inscrições do usuário logado
    $sql_inscricoes = "SELECT i.inscricao_id, d.nome as disciplina, t.nome as turma, t.horario_inicio, t.horario_termino, t.professor_nome
            FROM inscricoes i
            INNER JOIN turmas t ON i.turma_id = t.turma_id
            INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
            WHERE i.usuario_id = :usuario_id";
    
    $stmt_inscricoes = $conexao->prepare($sql_inscricoes);
    $stmt_inscricoes->bindParam(':usuario_id', $usuario_id);
    $stmt_inscricoes->execute();

    $inscricoes = $stmt_inscricoes->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obter a lista de espera do usuário logado
    $sql_lista_espera = "SELECT t.nome as turma, d.nome as disciplina 
            FROM lista_de_espera le
            INNER JOIN turmas t ON le.turma_id = t.turma_id
            INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
            WHERE le.usuario_id = :usuario_id";

    $stmt_lista_espera = $conexao->prepare($sql_lista_espera);
    $stmt_lista_espera->bindParam(':usuario_id', $usuario_id);
    $stmt_lista_espera->execute();

    $lista_de_espera = $stmt_lista_espera->fetchAll(PDO::FETCH_ASSOC);
?>

<section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const excluirInscricaoBtns = document.querySelectorAll('.excluir-inscricao');

        excluirInscricaoBtns.forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const inscricaoId = btn.dataset.inscricaoId; // Obtém o ID da inscrição a ser excluída

                // Chamada à função de exclusão passando o ID da inscrição
                excluirInscricao(inscricaoId, btn.closest('tr')); // Passa o elemento visual da linha para remover após a exclusão
            });
        });
    });
</script>

    <div class="container my-4">
        <h2 class="text-center mb-4">Minhas Inscrições e Lista de Espera</h2>

        <h3>Minhas Inscrições:</h3>

        <?php if (count($inscricoes) > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Turma</th>
                        <th>Horário</th>
                        <th>Professor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $inscricao) : ?>
                        <tr>
                            <td><?php echo $inscricao['disciplina']; ?></td>
                            <td><?php echo $inscricao['turma']; ?></td>
                            <td><?php echo $inscricao['horario_inicio'] . ' - ' . $inscricao['horario_termino']; ?></td>
                            <td><?php echo $inscricao['professor_nome']; ?></td>
                            <td><?php echo "<button class='excluir-inscricao btn btn-danger' data-inscricao-id='".$inscricao['inscricao_id']."'>Excluir</button>"?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Você ainda não possui inscrições.</p>
        <?php endif; ?>

        <h3>Lista de Espera:</h3>

        <?php if (count($lista_de_espera) > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Turma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_de_espera as $espera) : ?>
                        <tr>
                            <td><?php echo $espera['disciplina']; ?></td>
                            <td><?php echo $espera['turma']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Você não está na lista de espera de nenhuma turma.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'footer.php'; // Inclua o rodapé ?>
