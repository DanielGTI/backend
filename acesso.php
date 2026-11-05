<?php
    session_start();    // Iniciar a sessão

    //Capturar os valores do formulário
    $username = $_POST['username'] ?? '';   // "?? ''" evita o aviso "Undefined index" quando acesso.php é aberto sem POST
    $password = $_POST['password'] ?? '';   // idem: sem POST o valor vira string vazia e o login falha normalmente

    // Validar dados de login
    if($username === "admin" && $password === "123") {  // Credenciais válidas
        session_regenerate_id(true);                    // Gera novo ID de sessão e descarta o antigo: evita session fixation
        $_SESSION['nome_user'] = $username;             // Definir variável de sessão
        $_SESSION['login'] = true;                      // Definir variável de sessão
        $_SESSION['tipo'] = 'admin';                    // Definir variável de sessão

        header('Location: index.php');                  // Redirecionar para a página protegida
        exit();                                         // Finalizar o script

    } else {
        // Credenciais inválidas
        header('Location: login.php?erro=1');           // "?erro=1" faz o login.php exibir o aviso "Usuário ou senha inválidos."
        exit();                                         // Finalizar o script
    }


?>
