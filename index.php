<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    include('protegido.php');
    include('conexao.php');
    require_once 'header.php';
?>

<section class="bg-light">
    <div class="container my-4 d-flex justify-content-center">
        <div class="text-center">
            <h2 class="mb-4">Selecione uma opção</h2>
            <div class="list-group">
                <a href="inscrever.php" class="list-group-item list-group-item-action">Realizar inscrição</a>
                <a href="inscricoes.php" class="list-group-item list-group-item-action">Visualizar minhas inscrições</a>
            </div>
        </div>
    </div>
</section>

<?php
    require_once 'footer.php';
?>
