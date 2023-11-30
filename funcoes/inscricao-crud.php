<?php
    include('../protegido.php');
    include('../conexao.php');

    if (isset($_POST['inscricao_id'])) {
       
        $inscricao_id = $_POST['inscricao_id'];
        $usuario_id = $_SESSION['id'];

        // Consulta para obter informações da inscrição a ser excluída
        $sql_inscricao = "SELECT turma_id FROM inscricoes WHERE inscricao_id = :inscricao_id AND usuario_id = :usuario_id";
        $stmt_inscricao = $conexao->prepare($sql_inscricao);
        $stmt_inscricao->bindParam(':inscricao_id', $inscricao_id);
        $stmt_inscricao->bindParam(':usuario_id', $usuario_id);
        $stmt_inscricao->execute();
        $inscricao = $stmt_inscricao->fetch(PDO::FETCH_ASSOC);

        if ($inscricao) {
            $turma_id = $inscricao['turma_id'];

            // Excluir a inscrição
            $sql_excluir = "DELETE FROM inscricoes WHERE inscricao_id = :inscricao_id";
            $stmt_excluir = $conexao->prepare($sql_excluir);
            $stmt_excluir->bindParam(':inscricao_id', $inscricao_id);
            $stmt_excluir->execute();

            // Verificar se há alunos na lista de espera para a mesma turma
            $sql_lista_espera = "SELECT usuario_id FROM lista_de_espera WHERE turma_id = :turma_id LIMIT 1";
            $stmt_lista_espera = $conexao->prepare($sql_lista_espera);
            $stmt_lista_espera->bindParam(':turma_id', $turma_id);
            $stmt_lista_espera->execute();
            $aluno_lista_espera = $stmt_lista_espera->fetch(PDO::FETCH_ASSOC);

            if ($aluno_lista_espera) {
                $novo_usuario_id = $aluno_lista_espera['usuario_id'];

                // Remover o aluno da lista de espera
                $sql_remover_lista = "DELETE FROM lista_de_espera WHERE usuario_id = :usuario_id AND turma_id = :turma_id";
                $stmt_remover_lista = $conexao->prepare($sql_remover_lista);
                $stmt_remover_lista->bindParam(':usuario_id', $novo_usuario_id);
                $stmt_remover_lista->bindParam(':turma_id', $turma_id);
                $stmt_remover_lista->execute();

                // Adicionar o aluno como inscrito
                $sql_adicionar_inscrito = "INSERT INTO inscricoes (usuario_id, turma_id, data_inscricao) VALUES (:usuario_id, :turma_id, NOW())";
                $stmt_adicionar_inscrito = $conexao->prepare($sql_adicionar_inscrito);
                $stmt_adicionar_inscrito->bindParam(':usuario_id', $novo_usuario_id);
                $stmt_adicionar_inscrito->bindParam(':turma_id', $turma_id);
                $stmt_adicionar_inscrito->execute();

                echo "Inscrição excluída com sucesso. Aluno da lista de espera adicionado como inscrito.";
            } else {
                echo "Inscrição excluída com sucesso. Não há alunos na lista de espera para esta turma.";
            }
        } else {
            echo "Erro: Não foi possível encontrar a inscrição para exclusão.";
        }
    } else {
        echo "Erro: Parâmetros inválidos.";
    }
?>
