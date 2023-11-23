<?php
    include('conexao.php');

    if (isset($_POST['email']) && isset($_POST['password'])) {
        if (empty($_POST['email'])) {
            echo '<div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        Por favor insira seu email!
                    </div>
                </div>';
        } elseif (empty($_POST['password'])) {
            echo '<div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        Por favor insira sua senha!!
                    </div>
                </div>';
        } elseif ($_POST['password'] !== $_POST['c-password']) {
            echo '<div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        A senha e a senha de confirmação não coincidem!
                    </div>
                </div>';
        } else {
            $email = $_POST['email'];

            $stmt = $conexao->prepare("SELECT email FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $quantidade = $stmt->rowCount();

            if ($quantidade > 0) {
                echo "E-mail já cadastrado. Escolha um e-mail diferente.";
            } else { 
                $nome = $_POST['name'];
                $email = $_POST['email'];
                $senha = $_POST['password'];
                
                $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senha);
                $resultado = $stmt->execute();
                
                if ($resultado) {
                    echo "<script>alert('Cadastro bem sucedido!');</script>";
                    header("Location: login.php");
                } else {
                    echo "<script>alert('Falha ao efetuar o cadastro');</script>";
                    header("Location: cadastro.php");
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/global.css">
</head>
<body>
<section class="d-flex justify-content-centr align-items-center h-form" style="background-color: #eee; height: 100vh;" >
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">
                                    <form method="POST">
                                        <p class="text-center">Crie sua nova conta</p>

                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="name">Nome de usuário</label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Digite seu nome de usuário" />
                                        </div>

                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="email">Endereço de e-mail</label>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu endereço de e-mail" />
                                        </div>

                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="password">Senha</label>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" />
                                        </div>

                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="c-password">Confirme sua Senha</label>
                                            <input type="password" id="c-password" name="c-password" class="form-control" placeholder="Digite sua senha" />
                                        </div>

                                        <div class="text-center pt-1 mb-2 pb-1 w-100">
                                            <button class="btn btn-primary btn-block fa-lg bg-info w-100" type="submit">
                                                Criar Conta
                                            </button>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center pb-2">
                                            <p class="mb-0 me-2">Já tem uma conta?</p>
                                            <a class="btn btn-outline-info" href="./login.php">Fazer login</a>
                                        </div>
                                    </form>
    
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center bg-info">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
