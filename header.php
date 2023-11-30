<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/global.css">
</head>
<body>

<header class="bg-light">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="./index.php">SCA</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="./index.php">Início</a>
                    </li>
                </ul>

                <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                    if (isset($_SESSION['id'])) {
                        echo '<div class="navbar-text d-flex ms-auto align-items-center">
                                <div class="me-2">
                                    <p class="d-none d-lg-block mb-0">Bem vindo,</p>
                                    <p class="mb-0">' . $_SESSION['name'] . '!</p>
                                </div>
                                <a class="btn btn-danger" href="./sair.php">Sair</a>
                            </div>';
                    } else {
                        echo '<ul class="navbar-nav ms-auto">
                                <li class="nav-item me-2">
                                    <a class="btn btn-outline-primary" href="./login.php">Entrar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-primary" href="./cadastro.php">Cadastrar</a>
                                </li>
                            </ul>';
                    }
                ?>
            </div>
        </nav>
    </div>
</header>
