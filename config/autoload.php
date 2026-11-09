<?php
// Autoload: carrega automaticamente a classe quando ela é usada pela primeira vez.
// Assim não precisamos escrever "require" para cada classe.
//
// Regra: o nome do arquivo em classes/ é igual ao nome da classe.
// Ex.: "new Produto()" carrega o arquivo classes/Produto.php
spl_autoload_register(function (string $classe) {
    require_once __DIR__ . '/../classes/' . $classe . '.php';
});
