<?php

    session_start();                    //  Iniciar a sessão
    session_unset();                    //  Remover todas as variáveis de sessão
    session_destroy();                  //  Destruir a sessão
    header('Location: login.php');      //  Redirecionar para a página de login
    exit();                             // Finalizar o script

?>