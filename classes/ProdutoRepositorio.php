<?php
/**
 * Classe ProdutoRepositorio
 *
 * É quem "busca" os produtos. As telas não precisam saber DE ONDE os dados vêm,
 * só pedem: "me dê todos os produtos" ou "me dê o produto 2".
 *
 * Hoje os dados vêm do array em config/produtos.php.
 * Quando tivermos banco de dados, só esta classe muda; as telas continuam iguais.
 */
class ProdutoRepositorio
{
    // Guarda os dados brutos (array) lidos do arquivo
    private $dados;

    public function __construct()
    {
        // __DIR__ é a pasta deste arquivo (classes/). O "require" devolve o array do arquivo.
        $this->dados = require __DIR__ . '/../config/produtos.php';
    }

    /**
     * Retorna TODOS os produtos, como uma lista de objetos Produto.
     */
    public function listar(): array
    {
        $produtos = [];

        foreach ($this->dados as $id => $linha) {
            $linha['id'] = $id;                     // O id é a chave do array; copiamos para dentro da linha
            $produtos[] = new Produto($linha);      // Cria um objeto Produto para cada linha
        }

        return $produtos;
    }

    /**
     * Retorna UM produto pelo id, ou null se não existir.
     * O "?Produto" no retorno significa: "um Produto ou null".
     */
    public function buscar(int $id): ?Produto
    {
        if (!isset($this->dados[$id])) {
            return null;
        }

        $linha = $this->dados[$id];
        $linha['id'] = $id;

        return new Produto($linha);
    }
}
