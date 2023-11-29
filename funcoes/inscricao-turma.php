<?php
    include('../protegido.php');
    include('../conexao.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    function adicionar_a_lista_de_espera($conexao, $usuario_id, $turma_id){
        $sql = "INSERT INTO lista_de_espera (usuario_id, turma_id)
        VALUES (:usuario_id, :turma_id)";
         $stmt = $conexao->prepare($sql);
         $stmt->bindParam(':usuario_id', $usuario_id);
         $stmt->bindParam(':turma_id', $turma_id);
         $stmt->execute();
    }

    $usuarioId = $_SESSION['id']; 

    if(isset($_POST['turmas'])) {
        $turmasSelecionadas = json_decode($_POST['turmas'], true);

        foreach ($turmasSelecionadas as $turma) {
            $disciplina = $turma['disciplina'];
            $turmaId = $turma['turmaId'];

            $sql = "SELECT turma_fechada, nome FROM turmas WHERE turma_id = :turma_id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->execute();
            $rowTurmaFechada = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rowTurmaFechada && $rowTurmaFechada['turma_fechada']) {
                adicionar_a_lista_de_espera($conexao, $usuarioId, $turmaId);
                echo 'A turma ' . $rowTurmaFechada['nome'] . ' da disciplina ' . $disciplina . ' está fechada. Você foi adicionado a lista de espera.';
                exit();
            }

            $sql = "SELECT turmas.nome 
                    FROM inscricoes
                    JOIN turmas ON inscricoes.turma_id = turmas.turma_id
                    WHERE inscricoes.usuario_id = :usuario_id AND inscricoes.turma_id = :turma_id";

            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->execute();

            $quantidade = $stmt->rowCount();
            
            if ($quantidade == 1) {
                $rowInscrito = $stmt->fetch(PDO::FETCH_ASSOC);
                echo 'Você já está inscrito na turma ' . $rowInscrito['nome'] . ' da disciplina de ' . $disciplina;
                exit();
            }

            $sql = "SELECT turma_id, horario_inicio, horario_termino FROM turmas
                    JOIN inscricoes ON turmas.turma_id = inscricoes.turma_id
                    WHERE inscricoes.usuario_id = :usuario_id";
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $row) {
                $turmaInscrita = $row['turma_id'];

                $sql = "SELECT horario_inicio, horario_termino FROM turmas WHERE turma_id = :turma_id";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(':turma_id', $turmaId);
                $stmt->execute();
                $rowTurmaDesejada = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($rowTurmaDesejada) {
                    $horarioInicioDesejado = $rowTurmaDesejada['horario_inicio'];
                    $horarioTerminoDesejado = $rowTurmaDesejada['horario_termino'];

                    $sql = "SELECT horario_inicio, horario_termino FROM turmas WHERE turma_id = :turma_inscrita";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':turma_inscrita', $turmaInscrita);
                    $stmt->execute();
                    $rowTurmaInscrita = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $horarioInicioInscrito = $rowTurmaInscrita['horario_inicio'];
                    $horarioTerminoInscrito = $rowTurmaInscrita['horario_termino'];

                    if (
                        ($horarioInicioDesejado <= $horarioTerminoInscrito && $horarioTerminoDesejado >= $horarioInicioInscrito) ||
                        ($horarioInicioInscrito <= $horarioTerminoDesejado && $horarioTerminoInscrito >= $horarioInicioDesejado)
                    ) {
                        echo "Choque de horários com a outra turma a qual você está inscrito(a). Selecione outra turma.";
                        exit();
                    }
                }
            }
        }

        $dataAtual = date('Y-m-d');

        $sql = "INSERT INTO inscricoes (usuario_id, turma_id, data_inscricao)
        VALUES (:usuario_id, :turma_id, :data_inscricao)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':turma_id', $turmaId);
        $stmt->bindParam(':data_inscricao', $dataAtual);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo "Inscrição realizada com sucesso!";
        } else {
            echo "Erro ao realizar a inscrição.";
        }
    } else {
        echo "Acesso inválido!";
    }

?>
