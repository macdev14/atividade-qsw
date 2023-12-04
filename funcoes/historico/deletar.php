<?php
// Conexão com o banco de dados
// ... (código de conexão)
include('../protegido.php');
include('../conexao.php');

// Função para deletar uma disciplina do histórico do usuário
function deletarDisciplinaHistorico($usuario_id, $disciplina_id, $conexao) {
    $sql = "DELETE FROM historico WHERE usuario_id = :usuario_id AND disciplina_id = :disciplina_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':disciplina_id', $disciplina_id);
    return $stmt->execute();
}

// Exemplo de uso
$usuario_id = 1; // ID do usuário
$disciplina_id = 3; // ID da disciplina a ser removida do histórico

if (deletarDisciplinaHistorico($usuario_id, $disciplina_id, $conexao)) {
    echo "Disciplina removida do histórico com sucesso.";
} else {
    echo "Erro ao remover disciplina do histórico.";
}
?>
