<?php
// Conexão com o banco de dados
// ... (código de conexão)
include('../protegido.php');
include('../conexao.php');

// Função para atualizar uma disciplina no histórico do usuário
function atualizarDisciplinaHistorico($usuario_id, $disciplina_antiga_id, $disciplina_nova_id, $conexao) {
    $sql = "UPDATE historico SET disciplina_id = :disciplina_nova_id 
            WHERE usuario_id = :usuario_id AND disciplina_id = :disciplina_antiga_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':disciplina_antiga_id', $disciplina_antiga_id);
    $stmt->bindParam(':disciplina_nova_id', $disciplina_nova_id);
    return $stmt->execute();
}

// Exemplo de uso
$usuario_id = 1; // ID do usuário
$disciplina_antiga_id = 3; // ID da disciplina antiga no histórico
$disciplina_nova_id = 5; // ID da nova disciplina a ser atualizada

if (atualizarDisciplinaHistorico($usuario_id, $disciplina_antiga_id, $disciplina_nova_id, $conexao)) {
    echo "Disciplina atualizada no histórico com sucesso.";
} else {
    echo "Erro ao atualizar disciplina no histórico.";
}
?>
