<?php
    if (session_status() === PHP_SESSION_NONE) {    // Só inicia a sessão se ainda não houver uma (login.php já pode tê-la iniciado)
        session_start();                            // Iniciar a sessão
    }

    // Inicio da sessão
    // basename($_SERVER['SCRIPT_NAME']): compara só o nome do script em vez de REQUEST_URI, que falhava com query string (login.php?erro=1) ou subpasta
    if( !isset($_SESSION['nome_user']) && basename($_SERVER['SCRIPT_NAME']) !== 'login.php') {     // Verificar se o usuário está logado
        header('Location: login.php');                                                      // Redirecionar para a página de login
        exit();                                                                                     // Finalizar o script
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela!</title>

    <!-- Bootstrap -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="public/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="public/css/nprogress.css" rel="stylesheet">

    <!-- Animate.css - LOGIN.PHP -->
    <link href="public/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="public/css/custom.css" rel="stylesheet">
    <link href="public/css/style-extra-bs4.css" rel="stylesheet">
</head>