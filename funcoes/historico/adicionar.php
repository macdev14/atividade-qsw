<?php
// Conexão com o banco de dados
// ... (código de conexão)
include('../protegido.php');
include('../conexao.php');

// Função para adicionar uma disciplina ao histórico do usuário
function adicionarDisciplinaHistorico($usuario_id, $disciplina_id, $conexao) {
    $sql = "INSERT INTO historico (usuario_id, disciplina_id) VALUES (:usuario_id, :disciplina_id)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':disciplina_id', $disciplina_id);
    return $stmt->execute();
}

// Exemplo de uso
$usuario_id = 1; // ID do usuário
$disciplina_id = 3; // ID da disciplina cursada

if (adicionarDisciplinaHistorico($usuario_id, $disciplina_id, $conexao)) {
    echo "Disciplina adicionada ao histórico com sucesso.";
} else {
    echo "Erro ao adicionar disciplina ao histórico.";
}
?>
