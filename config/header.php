<?php
    require_once __DIR__ . '/autoload.php';         // Habilita o uso das classes (pasta classes/)

    // Se a página que incluiu o header (ex.: login.php) já criou o $auth, reaproveita; senão cria um novo.
    // O $auth fica disponível também para sidebar.php e top.php, que são incluídos depois.
    $auth = $auth ?? new Autenticacao();

    // Páginas protegidas exigem login; só o login.php é livre.
    // basename($_SERVER['SCRIPT_NAME']): compara só o nome do script em vez de REQUEST_URI, que falhava com query string (login.php?erro=1) ou subpasta
    if (basename($_SERVER['SCRIPT_NAME']) !== 'login.php') {
        $auth->exigirLogin();                       // Não logado: redireciona para o login.php
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