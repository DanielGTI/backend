<?php
    require_once 'config/autoload.php';     // Habilita o uso das classes (pasta classes/)

    $auth = new Autenticacao();             // Cria o objeto que cuida do login (já inicia a sessão)

    // Capturar os valores do formulário
    $username = $_POST['username'] ?? '';   // "?? ''" evita o aviso "Undefined index" quando acesso.php é aberto sem POST
    $password = $_POST['password'] ?? '';   // idem: sem POST o valor vira string vazia e o login falha normalmente

    // Pedimos ao objeto para validar. Ele devolve true (ok) ou false (inválido)
    if ($auth->entrar($username, $password)) {
        header('Location: index.php');              // Login ok: redirecionar para a página protegida
    } else {
        header('Location: login.php?erro=1');       // "?erro=1" faz o login.php exibir o aviso "Usuário ou senha inválidos."
    }
    exit();                                         // Finalizar o script
