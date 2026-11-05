<?php
    session_start();    // Iniciar a sessão
    
    //Capturar os valores do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar dados de login
    if($username === "admin" && $password === "123") {  // Credenciais válidas
        $_SESSION['nome_user'] = $username;             // Definir variável de sessão
        $_SESSION['login'] = true;                      // Definir variável de sessão
        $_SESSION['tipo'] = 'admin';                    // Definir variável de sessão      

        header('Location: index.php');                  // Redirecionar para a página protegida
        exit();                                         // Finalizar o script
        
    } else {
        // Credenciais inválidas
        header('Location: login.php');                  // Redirecionar de volta para a página de login
        exit();                                         // Finalizar o script
    }


?>