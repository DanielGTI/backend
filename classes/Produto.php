<?php
/**
 * Classe Produto
 *
 * Representa UM produto (um celular). Cada "new Produto(...)" cria um objeto novo,
 * com os dados daquele produto.
 *
 * A classe tem 3 responsabilidades simples:
 *   1. Guardar os dados do produto (propriedades)
 *   2. Permitir a leitura desses dados (getters)
 *   3. Validar se os dados estão corretos (validar)
 */
class Produto
{
    // Constantes da classe: acessadas de fora como Produto::OPCOES_RAM
    // São as opções que aparecem nos <select> do formulário de edição.
    public const OPCOES_RAM           = ['2GB', '4GB', '6GB', '8GB', '12GB', '16GB', '32GB'];
    public const OPCOES_ARMAZENAMENTO = ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'];

    // Propriedades "private": só podem ser lidas/alteradas por dentro da própria classe.
    // Isso se chama ENCAPSULAMENTO: protege os dados de uso indevido.
    private $id;
    private $marca;
    private $modelo;
    private $ram;
    private $dataLancamento;
    private $cameras;
    private $armazenamento;
    private $descricao;

    /**
     * Construtor: recebe um array com os dados (pode ser o $_POST do formulário
     * ou uma linha da lista de produtos) e preenche as propriedades.
     *
     * O "?? ''" usa texto vazio quando a chave não existir no array.
     */
    public function __construct(array $dados)
    {
        $this->id             = (int) ($dados['id'] ?? 0);
        $this->marca          = trim($dados['marca'] ?? '');        // trim() remove espaços do começo e do fim
        $this->modelo         = trim($dados['modelo'] ?? '');
        $this->ram            = trim($dados['ram'] ?? '');
        $this->dataLancamento = trim($dados['data_lancamento'] ?? '');
        $this->cameras        = trim($dados['cameras'] ?? '');
        $this->armazenamento  = trim($dados['armazenamento'] ?? '');
        $this->descricao      = trim($dados['descricao'] ?? '');
    }

    // ---------- Getters: métodos que devolvem o valor de cada propriedade ----------

    public function getId(): int               { return $this->id; }
    public function getMarca(): string         { return $this->marca; }
    public function getModelo(): string        { return $this->modelo; }
    public function getRam(): string           { return $this->ram; }
    public function getDataLancamento(): string { return $this->dataLancamento; }
    public function getCameras(): string       { return $this->cameras; }
    public function getArmazenamento(): string { return $this->armazenamento; }
    public function getDescricao(): string     { return $this->descricao; }

    /**
     * Valida os dados do produto.
     * Retorna uma lista com as mensagens de erro (lista vazia = tudo certo).
     */
    public function validar(): array
    {
        $erros = [];

        if ($this->marca === '') {
            $erros[] = 'Marca é obrigatória';
        }
        if ($this->modelo === '') {
            $erros[] = 'Modelo é obrigatório';
        }
        if ($this->ram === '') {
            $erros[] = 'RAM é obrigatória';
        }
        if ($this->dataLancamento === '') {
            $erros[] = 'Data de lançamento é obrigatória';
        }
        if ($this->cameras === '') {
            $erros[] = 'Câmeras são obrigatórias';
        }
        if ($this->armazenamento === '') {
            $erros[] = 'Armazenamento é obrigatório';
        }

        return $erros;
    }
}
