<?php
    include('../protegido.php');
    include('../conexao.php');


    function exibir_msg($msgs){
        foreach ($msgs as $key => $value){
            echo $value;
        }
    }
    function verificaPreRequisitos($usuario_id, $disciplina_id, $conexao) {
        // Consulta para verificar os pré-requisitos da disciplina
        $sql = "SELECT pre_requisito_id FROM pre_requisitos WHERE disciplina_id = :disciplina_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':disciplina_id', $disciplina_id);
        $stmt->execute();
        $preRequisitos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Consulta para obter as disciplinas no histórico do usuário
        $sql = "SELECT disciplina_id FROM historico WHERE usuario_id = :usuario_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $disciplinasHistorico = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Verificar se os pré-requisitos foram atendidos
        foreach ($preRequisitos as $pre_requisito) {
            if (!in_array($pre_requisito, $disciplinasHistorico)) {
                return false; // Pré-requisito não atendido
            }
        }
        return true; // Todos os pré-requisitos foram atendidos
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
        $msgs_extras = array();
        $turmasSelecionadas = json_decode($_POST['turmas'], true);
        $vazio = count($turmasSelecionadas) < 1;
        if ($vazio) { echo 'Selecione uma turma'; exit;}
        foreach ($turmasSelecionadas as $turma) {
            $disciplina = $turma['disciplina'];
            $turmaId = $turma['turmaId'];
            $temPreRequisto = verificaPreRequisitos($usuarioId, $disciplina, $conexao);
            if($temPreRequisto==false){ $msgs_extras[$turma['disciplina']]='Pré-requisito de '.$turma['disciplina'].' não atendido.'; continue;}
            $sql = "SELECT turma_fechada, nome FROM turmas WHERE turma_id = :turma_id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->execute();
            $rowTurmaFechada = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rowTurmaFechada && $rowTurmaFechada['turma_fechada']) {
                adicionar_a_lista_de_espera($conexao, $usuarioId, $turmaId);
                echo 'A turma ' . $rowTurmaFechada['nome'] . ' da disciplina ' . $disciplina . ' está fechada. Você foi adicionado a lista de espera.';
                exibir_msg($msgs_extras);
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
                exibir_msg($msgs_extras);
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
                        exibir_msg($msgs_extras);
                        exit();
                    }
                }
            }
        }

        $dataAtual = gmdate('Y-m-d');

        $sql = "INSERT INTO inscricoes (usuario_id, turma_id, data_inscricao)
        VALUES (:usuario_id, :turma_id, :data_inscricao)";

        

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':turma_id', $turmaId);
        $stmt->bindParam(':data_inscricao', $dataAtual);
        $resultado = $stmt->execute();

        if ($resultado) {
            exibir_msg($msgs_extras);
            echo "Inscrição realizada com sucesso!";
        } else {
            echo "Erro ao realizar a inscrição.";
        }
    } else {
        echo "Acesso inválido!";
    }

?>
