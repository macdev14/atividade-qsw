<?php
// Conexão com o banco de dados
// ... (código de conexão)
include('../protegido.php');
include('../conexao.php');

// Função para obter o histórico de disciplinas de um usuário
function obterHistoricoUsuario($usuario_id, $conexao) {
    $sql = "SELECT d.nome AS disciplina
            FROM historico h
            INNER JOIN disciplinas d ON h.disciplina_id = d.disciplina_id
            WHERE h.usuario_id = :usuario_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemplo de uso
$usuario_id = 1; // ID do usuário
$historico = obterHistoricoUsuario($usuario_id, $conexao);

// Exibindo o histórico
foreach ($historico as $disciplina) {
    echo $disciplina['disciplina'] . "<br>";
}
?>
