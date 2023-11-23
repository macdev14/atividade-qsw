<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body class="bg-light text-center d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <h1 class="mb-4">Redirecionando...</h1>
        <p>Você precisa estar logado para acessar esta página!</p>
        <p>Você será redirecionado para a página de login em <span id="contador">5</span> segundos. Se não for redirecionado, <a href="login.php">clique aqui</a>.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-xn3p4i9ERpKlAGzN2YF+ZjbOB5+O9PXykVTHNl+nFS8IurF3R9Iip+qqRQ4bgiJL" crossorigin="anonymous"></script>
    <script>
        var segundos = 5;
        function atualizarContador() {
            document.getElementById('contador').textContent = segundos;
            segundos--;
            
            if (segundos < 0) {
                window.location.href = 'login.php';
            } else {
                setTimeout(atualizarContador, 1000);
            }
        }

        setTimeout(atualizarContador, 1000);
    </script>

</body>
</html>
