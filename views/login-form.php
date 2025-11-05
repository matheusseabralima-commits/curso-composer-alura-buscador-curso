<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/estilos.css">
    <link rel="stylesheet" href="/css/formulario.css"> <link rel="stylesheet" href="/css/flexbox.css">
    <title>AluraPlay - Login</title>
</head>

<body>
    <header>
        <nav class="cabecalho">
            <a class="logo" href="/"></a> </nav>
    </header>

    <main class="container">
        <form class="container__formulario" method="post" action="/login">
            <h2 class="formulario__titulo">Efetue o Login</h2>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="email">E-mail</label>
                <input type="email" name="email" class="campo__escrita" required
                       placeholder="Digite seu e-mail" id='email' />
            </div>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="password">Senha</label>
                <input type="password" name="password" class="campo__escrita" required
                       placeholder="Digite sua senha" id='password' />
            </div>

            <input class="formulario__botao" type="submit" value="Entrar" />
        </form>
    </main>
</body>
</html>