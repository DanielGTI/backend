<?php
// processar_edicao.php - Processa a edição de produtos

require_once 'config/autoload.php';                         // Habilita o uso das classes (pasta classes/)

$auth = new Autenticacao();                                 // Objeto de login (já inicia a sessão)
$auth->exigirLogin();                                       // Bloquear quem não está autenticado (este script não passa pelo header.php)

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Cria um objeto Produto com os dados do formulário.
    // O próprio Produto sabe ler o $_POST e se validar.
    $produto = new Produto($_POST);

    // Pede ao objeto para validar os dados: devolve a lista de erros (vazia = tudo certo)
    $erros = $produto->validar();

    // Se não houver erros, processa os dados
    if (empty($erros)) {
        // Aqui você faria a conexão com o banco de dados e a atualização
        // Por enquanto, vamos apenas redirecionar com uma mensagem de sucesso

        // Simulação de processamento bem-sucedido
        $mensagem = "Produto atualizado com sucesso!";

        // Redireciona para a página de produtos com mensagem de sucesso
        header("Location: produto.php?mensagem=" . urlencode($mensagem));
        exit();

    } else {
        // Se houver erros, redireciona de volta com os erros
        $erros_string = implode("|", $erros);
        header("Location: produto_unitario.php?id=" . urlencode($produto->getId()) . "&erros=" . urlencode($erros_string));
        exit();
    }

} else {
    // Se não foi via POST, redireciona para a página de edição
    header("Location: produto_unitario.php");
    exit();
}
?>
