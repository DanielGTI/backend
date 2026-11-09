<?php
    require_once 'config/autoload.php';     // Habilita o uso das classes (pasta classes/)

    $auth = new Autenticacao();             // Cria o objeto que cuida do login (já inicia a sessão)
    $auth->sair();                          // Apaga a sessão (logout)

    header('Location: login.php');          // Redirecionar para a página de login
    exit();                                 // Finalizar o script
