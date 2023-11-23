<?php
    try {
        $usuario = "root";
        $senha = "";
        $banco = "qsw";
        $host = "localhost";

        $conexao = new PDO("mysql:host=$host;dbname=$banco", $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
?>
