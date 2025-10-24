<?php
declare(strict_types=1);
session_start();

$erro = null; // Variável para guardar a mensagem de erro

// --- PARTE 1: PROCESSAR O LOGIN (Se o formulário for enviado - POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbPath = __DIR__ . '/banco.sqlite';
    $pdo = new PDO("sqlite:$dbPath");

    $email = $_POST['email'] ?? '';
    $password = $_POST['senha'] ?? '';

    $sql = 'SELECT * FROM users WHERE email = ?;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica a senha
    if ($userData && password_verify($password, $userData['password'])) {
        // Senha correta
        $_SESSION['logado'] = true;
        $_SESSION['email'] = $userData['email']; 
        header("Location: /index.php"); // Manda para a lista de vídeos
        exit();
    } else {
        // Senha incorreta
        $erro = "E-mail ou senha incorretos!";
    }
}

// --- PARTE 2: SE ALGUÉM JÁ LOGADO TENTAR ACESSAR, EXPULSA ---
// (Isso impede que alguém logado veja o formulário de login)
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true && $_SERVER['REQUEST_METHOD'] !== 'POST') {
     header('Location: /index.php');
     exit();
}

// --- PARTE 3: MOSTRAR O HTML (Se for acesso normal - GET) ---
// O código PHP acima já rodou, agora só mostramos o HTML.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/estilos-form.css">
    <link rel="stylesheet" href="./css/flexbox.css">
    <title>AluraPlay - Login</title>
</head>
<body>
    <header>
        <nav class="cabecalho">
            <a class="logo" href="./index.php"></a>
        </nav>
    </header>

    <main class="container">
        <!-- Este formulário envia os dados PARA ESTE MESMO ARQUIVO (login.php) -->
        <form class="container__formulario" method="POST" action="login.php">
            <h2 class="formulario__titulo">Efetue login</h3>

                <!-- Mostra a mensagem de erro se ela existir -->
                <?php if ($erro !== null): ?>
                    <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $erro; ?></p>
                <?php endif; ?>

                <div class="formulario__campo">
                    <label class="campo__etiqueta" for="email">E-mail</label>
                    <input name="email" class="campo__escrita" required
                        placeholder="Digite seu e-mail" id='email' type="email" />
                </div>
                <div class="formulario__campo">
                    <label class="campo__etiqueta" for="senha">Senha</label>
                    <input type="password" name="senha" class="campo__escrita" required placeholder="Digite sua senha"
                        id='senha' />
                </div>
                <input class="formulario__botao" type="submit" value="Entrar" />
        </form>
    </main>
</body>
</html>

