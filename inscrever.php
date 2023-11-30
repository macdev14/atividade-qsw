<?php
    include('protegido.php');
    include('conexao.php');
    require_once 'header.php';
?>

<section>
    <div class="container my-4">
        <h2 class="text-center mb-4">Escolha as turmas que deseja cursar</h2>
        <form action="processar_inscricao.php" method="post">
        <?php
            $modulo_id = 1;
            $disciplinas = array();

            $sql = "SELECT d.disciplina_id, d.nome as disciplina, t.turma_id, t.nome as turma, t.horario_inicio, t.horario_termino, t.professor_nome
                    FROM turmas t
                    INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
                    WHERE d.modulo_id = :modulo_id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':modulo_id', $modulo_id);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $row) {
                $disciplina = $row['disciplina'];

                $disciplinas[$disciplina][] = array(
                    'turmaId' => $row['turma_id'], 
                    'nome' => $row['turma'],
                    'horarioInicio' => $row['horario_inicio'],
                    'horarioTermino' => $row['horario_termino'],
                    'professorNome' => $row['professor_nome']
                );
            }

            foreach ($disciplinas as $disciplina => $turmas) {
                echo '<div class="card mb-4">';
                echo '<div class="card-header">' . $disciplina . '</div>';
                echo '<ul class="list-group list-group-flush">';

                foreach ($turmas as $turma) {
                    echo '<li class="list-group-item">';
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="radio" name="turma_' . $disciplina . '" value="' . $turma['turmaId'] . '" id="' . $turma['turmaId'] . '" data-disciplina="' . $disciplina . '">';
                    echo '<label class="form-check-label" for="' . $turma['turmaId'] . '">' . $turma['nome'] . '</label>';
                    echo '<span class="ms-2">Horário: ' . $turma['horarioInicio'] . ' - ' . $turma['horarioTermino'] . '</span>';
                    echo '<span class="ms-2">Professor: ' . $turma['professorNome'] . '</span>';
                    echo '</div>';
                    echo '</li>';
                }
                echo '<button type="button" class="btn btn-outline-danger mt-2" onclick="limparSelecao(\'' . $disciplina . '\')">Limpar Seleção</button>';

                echo '</ul>';
                echo '</div>';
            }
            ?>

       <div class="d-flex justify-content-between align-items-center">
            <a href="./index.php" class="btn btn-secondary">Sair</a>
            <div class="text-center">
                <button id="revisarInscricao" type="submit" class="btn btn-success" name="revisar_inscricao">Revisar Inscrição</button>
                <button id="confirmarInscricao" type="submit" class="btn btn-primary" name="confirmar_inscricao">Confirmar</button>
            </div>
        </div>
        </form> 
    </div>    
</section>

<?php
    require_once 'footer.php';
?>
