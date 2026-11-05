<?php
// processar_edicao.php - Processa a edição de produtos

session_start();                                            // Iniciar a sessão
if (!isset($_SESSION['login'])) {                           // Bloquear quem não está autenticado (este script não passa pelo header.php)
    header('Location: login.php');                          // Redirecionar para a página de login
    exit();                                                 // Finalizar o script
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera os dados do formulário
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $marca = isset($_POST['marca']) ? $_POST['marca'] : '';
    $modelo = isset($_POST['modelo']) ? $_POST['modelo'] : '';
    $ram = isset($_POST['ram']) ? $_POST['ram'] : '';
    $data_lancamento = isset($_POST['data_lancamento']) ? $_POST['data_lancamento'] : '';
    $cameras = isset($_POST['cameras']) ? $_POST['cameras'] : '';
    $armazenamento = isset($_POST['armazenamento']) ? $_POST['armazenamento'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    
    // Validação básica
    $erros = array();
    
    if (empty($marca)) {
        $erros[] = "Marca é obrigatória";
    }
    
    if (empty($modelo)) {
        $erros[] = "Modelo é obrigatório";
    }
    
    if (empty($ram)) {
        $erros[] = "RAM é obrigatória";
    }
    
    if (empty($data_lancamento)) {
        $erros[] = "Data de lançamento é obrigatória";
    }
    
    if (empty($cameras)) {
        $erros[] = "Câmeras são obrigatórias";
    }
    
    if (empty($armazenamento)) {
        $erros[] = "Armazenamento é obrigatório";
    }
    
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
        header("Location: produto_unitario.php?id=" . urlencode($id) . "&erros=" . urlencode($erros_string));
        exit();
    }
    
} else {
    // Se não foi via POST, redireciona para a página de edição
    header("Location: produto_unitario.php");
    exit();
}
?>