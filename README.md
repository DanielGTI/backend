# PHP Orientado a Objetos (POO) para iniciantes

Este projeto é um pequeno painel de administração (login, lista de produtos e edição de produto) feito em **PHP com Orientação a Objetos**.

Este material tem duas partes:

1. **Conceitos básicos** de POO, com exemplos simples.
2. **As classes e objetos deste projeto**, para você ver os conceitos funcionando de verdade.

> Dica: leia a parte 1 com calma e, depois, abra os arquivos da pasta [classes/](classes/) ao lado da parte 2.

---

## Como rodar o projeto

```bash
docker compose up
```

Depois acesse <http://localhost:8086> e entre com:

- **Usuário:** `admin`
- **Senha:** `123`

---

## Índice

**Parte 1 — Conceitos básicos**

1. [O que é POO e por que usar](#1-o-que-é-poo-e-por-que-usar)
2. [Classe e objeto](#2-classe-e-objeto)
3. [Propriedades e métodos](#3-propriedades-e-métodos)
4. [O construtor](#4-o-construtor)
5. [O `$this`](#5-o-this)
6. [Visibilidade e encapsulamento](#6-visibilidade-e-encapsulamento)
7. [Getters](#7-getters)
8. [Constantes da classe](#8-constantes-da-classe)
9. [Tipos de parâmetro e de retorno](#9-tipos-de-parâmetro-e-de-retorno)
10. [Uma classe usando outra](#10-uma-classe-usando-outra)
11. [Autoload](#11-autoload)

**Parte 2 — Este projeto**

12. [Mapa do projeto](#12-mapa-do-projeto)
13. [Classe `Autenticacao`](#13-classe-autenticacao)
14. [Classe `Produto`](#14-classe-produto)
15. [Classe `ProdutoRepositorio`](#15-classe-produtorepositorio)
16. [Todas juntas: o caminho de uma requisição](#16-todas-juntas-o-caminho-de-uma-requisição)
17. [Antes e depois](#17-antes-e-depois-procedural-x-poo)
18. [Exercícios](#18-exercícios)
19. [Glossário](#19-glossário)

---

# Parte 1 — Conceitos básicos

## 1. O que é POO e por que usar

**POO** significa **Programação Orientada a Objetos**. A ideia é organizar o código imitando coisas do mundo real.

Pense em um **celular**. Ele tem:

- **características**: marca, modelo, memória RAM...
- **ações**: ligar, tirar foto, enviar mensagem...

Na POO, juntamos as características e as ações **no mesmo lugar**. Esse "lugar" é a **classe**.

**Por que isso ajuda?**

| Sem POO (procedural) | Com POO |
|---|---|
| Código espalhado em vários arquivos | Cada assunto fica em uma classe |
| A mesma regra repetida em vários lugares | A regra fica em um lugar só |
| Difícil de achar onde mudar algo | Você sabe qual classe abrir |
| Difícil de reaproveitar | Basta usar a classe de novo |

---

## 2. Classe e objeto

Esta é a ideia mais importante.

- **Classe** = o **molde** (a planta da casa, a forma de bolo, a receita).
- **Objeto** = o que você **cria a partir do molde** (a casa pronta, o bolo, o prato).

Com **uma** classe você cria **vários** objetos, e cada um tem os seus próprios dados.

```
        CLASSE Celular  (o molde)
        ┌───────────────────────┐
        │ marca                 │
        │ modelo                │
        └───────────────────────┘
               │  new   │  new
               ▼        ▼
        ┌────────────┐ ┌────────────┐
        │ Apple      │ │ Samsung    │   ← OBJETOS
        │ iPhone 17  │ │ Galaxy S25 │
        └────────────┘ └────────────┘
```

Em PHP:

```php
<?php
// 1) Criamos a CLASSE (o molde)
class Celular
{
    public $marca;
    public $modelo;
}

// 2) Criamos OBJETOS a partir dela, usando a palavra "new"
$celular1 = new Celular();
$celular1->marca  = 'Apple';
$celular1->modelo = 'iPhone 17';

$celular2 = new Celular();
$celular2->marca  = 'Samsung';
$celular2->modelo = 'Galaxy S25';

echo $celular1->marca;   // Apple
echo $celular2->marca;   // Samsung
```

Repare:

- `new Celular()` **cria** um objeto.
- A seta `->` serve para **acessar** algo que está dentro do objeto.
- `$celular1` e `$celular2` são **independentes**: mudar um não muda o outro.

---

## 3. Propriedades e métodos

Dentro de uma classe temos dois tipos de coisa:

| Nome técnico | O que é | Analogia | Em PHP |
|---|---|---|---|
| **Propriedade** | uma variável da classe | característica ("tem marca") | `public $marca;` |
| **Método** | uma função da classe | ação ("tira foto") | `public function tirarFoto() {}` |

```php
<?php
class Celular
{
    // PROPRIEDADES (dados)
    public $modelo;
    public $bateria = 100;

    // MÉTODOS (ações)
    public function usar()
    {
        $this->bateria = $this->bateria - 10;
    }

    public function mostrarBateria()
    {
        echo "Bateria em {$this->bateria}%";
    }
}

$cel = new Celular();
$cel->usar();
$cel->usar();
$cel->mostrarBateria();   // Bateria em 80%
```

---

## 4. O construtor

Quando criamos um objeto, quase sempre queremos já **preencher os dados iniciais**. Para isso existe o **construtor**: um método especial chamado `__construct`, que o PHP executa **automaticamente** no `new`.

```php
<?php
class Celular
{
    public $marca;
    public $modelo;

    // Roda sozinho no momento do "new"
    public function __construct($marca, $modelo)
    {
        $this->marca  = $marca;
        $this->modelo = $modelo;
    }
}

// Os valores entre parênteses vão para o construtor
$cel = new Celular('Motorola', 'G10');

echo $cel->modelo;   // G10
```

Sem construtor, precisaríamos criar o objeto e depois preencher linha por linha. Com ele, o objeto já **nasce pronto**.

---

## 5. O `$this`

Dentro de um método, como a classe se refere a **ela mesma**? Com o **`$this`**.

`$this` significa: **"este objeto aqui"**.

```php
public function __construct($marca)
{
    $this->marca = $marca;
    //  ↑            ↑
    //  "a propriedade    "o valor que
    //   marca DESTE       chegou como
    //   objeto"           parâmetro"
}
```

Se existem dois objetos, `$this` é o objeto que está executando o método naquele momento.

---

## 6. Visibilidade e encapsulamento

Até agora usamos `public`, que permite que **qualquer um** mexa nos dados de fora:

```php
$cel->bateria = -500;   // Ninguém impede! Bateria negativa não existe...
```

Para proteger os dados usamos **visibilidade**:

| Palavra | Quem pode acessar |
|---|---|
| `public` | Qualquer parte do código |
| `private` | **Somente** a própria classe |

Isso se chama **encapsulamento**: esconder os detalhes internos e deixar aberto só o que faz sentido.

```php
<?php
class Celular
{
    private $bateria = 100;   // ninguém de fora mexe diretamente

    public function usar()
    {
        if ($this->bateria >= 10) {
            $this->bateria = $this->bateria - 10;
        }
    }
}

$cel = new Celular();
$cel->usar();                 // OK: o método controla a regra
$cel->bateria = -500;         // ERRO: propriedade privada!
```

A classe é a "dona" dos seus dados e garante que as regras sejam respeitadas.

---

## 7. Getters

Se a propriedade é `private`, como ler o valor de fora? Criamos um método que o **devolve**. Esse método é chamado de **getter** (do inglês *get* = pegar). Por convenção, o nome começa com `get`.

```php
<?php
class Celular
{
    private $modelo;

    public function __construct($modelo)
    {
        $this->modelo = $modelo;
    }

    // Getter: permite LER, mas não alterar
    public function getModelo()
    {
        return $this->modelo;
    }
}

$cel = new Celular('Galaxy S25');
echo $cel->getModelo();   // Galaxy S25  (leitura permitida)
$cel->modelo = 'outro';   // ERRO (alteração direta bloqueada)
```

---

## 8. Constantes da classe

Uma **constante** é um valor que **nunca muda**. Em uma classe, usamos `const`.

- Dentro da própria classe acessamos com `self::NOME`.
- Fora da classe acessamos com `NomeDaClasse::NOME`.

```php
<?php
class Celular
{
    public const OPCOES_COR = ['preto', 'branco', 'azul'];
    private const BATERIA_MAXIMA = 100;

    public function carregar()
    {
        return self::BATERIA_MAXIMA;   // dentro da classe: self::
    }
}

print_r(Celular::OPCOES_COR);   // fora da classe: Celular::
```

Repare que **não precisamos criar um objeto** (`new`) para usar uma constante: ela pertence à classe.

---

## 9. Tipos de parâmetro e de retorno

Podemos dizer **o tipo** de dado que um método recebe e devolve. Isso evita erros e deixa o código mais fácil de ler.

```php
public function somar(int $a, int $b): int
{
    return $a + $b;
}
```

- `int $a` → o parâmetro `$a` deve ser um número inteiro.
- `: int` → o método devolve um número inteiro.

Tipos que você vai encontrar:

| Tipo | Significado |
|---|---|
| `int` | número inteiro |
| `string` | texto |
| `bool` | `true` ou `false` |
| `array` | lista |
| `void` | o método **não devolve nada** |
| `?Produto` | devolve um `Produto` **ou** `null` (o `?` quer dizer "ou nulo") |

---

## 10. Uma classe usando outra

Na vida real, as coisas trabalham juntas. No código também: uma classe pode **criar e usar objetos de outra classe**.

```php
<?php
class Bateria
{
    public function carregar()
    {
        return 'Carregando...';
    }
}

class Celular
{
    private $bateria;

    public function __construct()
    {
        $this->bateria = new Bateria();   // um objeto dentro de outro
    }

    public function colocarNaTomada()
    {
        return $this->bateria->carregar();
    }
}

$cel = new Celular();
echo $cel->colocarNaTomada();   // Carregando...
```

Cada classe cuida de **uma coisa só**. É isso que deixa o projeto organizado.

---

## 11. Autoload

Normalmente, para usar uma classe que está em outro arquivo, precisaríamos de um `require`:

```php
require 'classes/Celular.php';
require 'classes/Bateria.php';
require 'classes/Pedido.php';
// ...um para cada classe. Fica cansativo!
```

O **autoload** resolve isso: o PHP carrega o arquivo **sozinho**, na primeira vez que você usa `new NomeDaClasse()`.

A regra combinada é: **o nome do arquivo é igual ao nome da classe**.

```
new Celular()  →  o PHP procura  classes/Celular.php
```

---

# Parte 2 — As classes e objetos deste projeto

Agora vamos ver tudo isso **em ação** no nosso código.

## 12. Mapa do projeto

```
backend/
├── classes/                     ← AS CLASSES (o coração da POO)
│   ├── Autenticacao.php         → cuida do login
│   ├── Produto.php              → representa um celular
│   └── ProdutoRepositorio.php   → busca os produtos
│
├── config/
│   ├── autoload.php             → carrega as classes automaticamente
│   ├── produtos.php             → dados de exemplo (será trocado por banco)
│   ├── header.php, sidebar.php, top.php, footer.php, scripts.php
│
├── pagina/                      ← conteúdo de cada tela
│   ├── home.php
│   ├── lista_produto.php        → usa ProdutoRepositorio
│   └── editar_produto.php       → usa ProdutoRepositorio e Produto
│
├── index.php, produto.php, produto_unitario.php   ← telas
├── login.php, acesso.php, logout.php              ← entrada e saída
└── processar_edicao.php                           ← recebe o formulário
```

Cada classe tem **uma responsabilidade**:

| Classe | Responsabilidade | Analogia |
|---|---|---|
| `Autenticacao` | Saber quem está logado | O **porteiro** do prédio |
| `Produto` | Guardar e validar os dados de **um** celular | A **ficha** do celular |
| `ProdutoRepositorio` | Buscar produtos | O **arquivo** onde ficam as fichas |

---

## 13. Classe `Autenticacao`

📄 Arquivo: [classes/Autenticacao.php](classes/Autenticacao.php)

É o "porteiro": confere usuário e senha e diz se alguém está logado.

### Conceitos usados

**Constantes privadas** — usuário e senha não mudam e só a classe precisa conhecê-los:

```php
private const USUARIO = 'admin';
private const SENHA   = '123';
```

**Construtor** — ao criar o objeto, ele já garante que a sessão esteja iniciada:

```php
public function __construct()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
```

**Método com retorno `bool`** — confere o login e responde `true` ou `false`:

```php
public function entrar(string $usuario, string $senha): bool
{
    if ($usuario !== self::USUARIO || $senha !== self::SENHA) {
        return false;                       // senha errada
    }

    $_SESSION['nome_user'] = $usuario;      // guarda quem logou
    $_SESSION['login']     = true;
    return true;                            // deu certo
}
```

> Repare no `self::USUARIO`: é assim que a classe acessa a **sua própria** constante.

### Os métodos da classe

| Método | O que faz | Retorna |
|---|---|---|
| `entrar($usuario, $senha)` | Confere o login e grava na sessão | `bool` |
| `estaLogado()` | Pergunta se há alguém logado | `bool` |
| `exigirLogin()` | Se não está logado, manda para o `login.php` | `void` |
| `nomeUsuario()` | Devolve o nome de quem está logado | `string` |
| `sair()` | Apaga a sessão (logout) | `void` |

### Onde o objeto é criado e usado

Em [acesso.php](acesso.php) — o formulário de login:

```php
$auth = new Autenticacao();                       // cria o objeto

if ($auth->entrar($username, $password)) {        // pergunta: "o login é válido?"
    header('Location: index.php');
} else {
    header('Location: login.php?erro=1');
}
```

Em [config/header.php](config/header.php) — protege todas as páginas:

```php
$auth = $auth ?? new Autenticacao();
$auth->exigirLogin();     // não logado? vai para o login
```

Em [config/sidebar.php](config/sidebar.php) — mostra o nome do usuário:

```php
<h2><?= htmlspecialchars($auth->nomeUsuario()) ?></h2>
```

Em [logout.php](logout.php):

```php
$auth = new Autenticacao();
$auth->sair();
```

> **Veja a vantagem:** a regra de login está em **um único arquivo**. Para trocar a senha ou passar a usar banco de dados, você altera só a classe `Autenticacao`. As telas continuam iguais.

---

## 14. Classe `Produto`

📄 Arquivo: [classes/Produto.php](classes/Produto.php)

Cada **objeto** `Produto` representa **um celular**. A classe é a ficha em branco; cada objeto é uma ficha preenchida.

### Conceitos usados

**Propriedades privadas** (encapsulamento) — os dados ficam protegidos:

```php
private $id;
private $marca;
private $modelo;
private $ram;
// ...
```

**Constantes públicas** — as opções dos campos de seleção do formulário:

```php
public const OPCOES_RAM           = ['2GB', '4GB', '6GB', '8GB', '12GB', '16GB', '32GB'];
public const OPCOES_ARMAZENAMENTO = ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'];
```

Usadas de fora, sem criar objeto, em [pagina/editar_produto.php](pagina/editar_produto.php):

```php
$opcoes_ram = Produto::OPCOES_RAM;
```

**Construtor** — recebe um array com os dados e preenche as propriedades:

```php
public function __construct(array $dados)
{
    $this->id     = (int) ($dados['id'] ?? 0);
    $this->marca  = trim($dados['marca'] ?? '');
    $this->modelo = trim($dados['modelo'] ?? '');
    // ...
}
```

> Dois detalhes úteis:
> - `$dados['marca'] ?? ''` significa: "use a marca; se ela não existir, use texto vazio".
> - `trim()` remove espaços do começo e do fim do texto.

**Getters** — permitem ler os dados, mas não alterá-los:

```php
public function getMarca(): string  { return $this->marca; }
public function getModelo(): string { return $this->modelo; }
public function getRam(): string    { return $this->ram; }
```

**Método `validar()`** — o objeto sabe dizer se **seus próprios dados** estão corretos:

```php
public function validar(): array
{
    $erros = [];

    if ($this->marca === '') {
        $erros[] = 'Marca é obrigatória';
    }
    if ($this->modelo === '') {
        $erros[] = 'Modelo é obrigatório';
    }
    // ...

    return $erros;     // lista vazia = tudo certo
}
```

### Criando objetos `Produto`

O mesmo molde pode gerar objetos com dados diferentes:

```php
$iphone = new Produto([
    'id'     => 1,
    'marca'  => 'Apple',
    'modelo' => 'iPhone 17',
    'ram'    => '32GB',
]);

$galaxy = new Produto([
    'id'     => 2,
    'marca'  => 'Samsung',
    'modelo' => 'Galaxy S25',
    'ram'    => '8GB',
]);

echo $iphone->getModelo();   // iPhone 17
echo $galaxy->getModelo();   // Galaxy S25
```

Em [processar_edicao.php](processar_edicao.php), o objeto é criado com os dados do formulário que o usuário enviou:

```php
$produto = new Produto($_POST);     // $_POST = dados do formulário
$erros   = $produto->validar();     // "Produto, você está correto?"

if (empty($erros)) {
    // sem erros: segue em frente
}
```

Se o usuário deixar a marca em branco, `validar()` devolve `['Marca é obrigatória']`.

---

## 15. Classe `ProdutoRepositorio`

📄 Arquivo: [classes/ProdutoRepositorio.php](classes/ProdutoRepositorio.php)

> *Repositório* é o lugar onde ficam guardadas as coisas. Esta classe é o "arquivo de fichas": quem precisa de produtos **pede a ela**, sem se importar de onde eles vêm.

Hoje os dados vêm do array em [config/produtos.php](config/produtos.php). No futuro virão de um banco de dados. **As telas não vão perceber a diferença.**

### Conceitos usados

**Uma classe criando objetos de outra** — o repositório fabrica objetos `Produto`:

```php
public function listar(): array
{
    $produtos = [];

    foreach ($this->dados as $id => $linha) {
        $linha['id'] = $id;
        $produtos[] = new Produto($linha);    // um objeto Produto para cada linha
    }

    return $produtos;                          // lista de objetos
}
```

**Retorno que pode ser nulo (`?Produto`)** — se o id não existir, devolve `null`:

```php
public function buscar(int $id): ?Produto
{
    if (!isset($this->dados[$id])) {
        return null;                           // não achou
    }

    $linha = $this->dados[$id];
    $linha['id'] = $id;

    return new Produto($linha);                // achou: devolve o objeto
}
```

### Onde é usado

Em [pagina/lista_produto.php](pagina/lista_produto.php), para montar a tabela:

```php
$repositorio = new ProdutoRepositorio();
$produtos    = $repositorio->listar();         // lista de objetos Produto

foreach ($produtos as $produto) {
    echo $produto->getMarca();                 // Apple, Samsung, Motorola
    echo $produto->getModelo();                // iPhone 17, Galaxy S25, G10
}
```

Em [pagina/editar_produto.php](pagina/editar_produto.php), para carregar um produto no formulário:

```php
$repositorio = new ProdutoRepositorio();
$produto     = $repositorio->buscar(2);        // busca o Galaxy S25

if (!$produto) {
    echo 'Produto não encontrado.';            // buscar() devolveu null
}
```

---

## 16. Todas juntas: o caminho de uma requisição

Veja os objetos trabalhando em conjunto quando o usuário **edita um produto**:

```
 1) LOGIN  (acesso.php)
    new Autenticacao()  →  ->entrar('admin', '123')  →  true   ✔ logado

 2) LISTA  (produto.php → pagina/lista_produto.php)
    new ProdutoRepositorio()
         │
         └─ ->listar()  →  cria 3 objetos Produto:
                            ┌──────────┐ ┌──────────┐ ┌──────────┐
                            │ Apple    │ │ Samsung  │ │ Motorola │
                            │ iPhone 17│ │ Galaxy   │ │ G10      │
                            └──────────┘ └──────────┘ └──────────┘
         O foreach mostra cada um na tabela.

 3) EDITAR  (produto_unitario.php?id=2 → pagina/editar_produto.php)
    new ProdutoRepositorio()  →  ->buscar(2)  →  objeto Produto (Samsung)
    O formulário é preenchido com os getters: ->getMarca(), ->getModelo()...

 4) SALVAR  (processar_edicao.php)
    new Autenticacao()  →  ->exigirLogin()       "o usuário pode estar aqui?"
    new Produto($_POST) →  ->validar()           "os dados estão certos?"
         ├─ sem erros → volta para a lista com "sucesso"
         └─ com erros → volta para o formulário mostrando os erros

 5) SAIR  (logout.php)
    new Autenticacao()  →  ->sair()
```

Quem faz o quê:

| Objeto | Quando é criado | O que ele faz |
|---|---|---|
| `Autenticacao` | Em quase toda página | Confere login, protege páginas, mostra nome |
| `ProdutoRepositorio` | Na lista e na edição | Entrega os produtos |
| `Produto` | Dentro do repositório (um por celular) e no `processar_edicao.php` | Guarda os dados e valida |

---

## 17. Antes e depois: procedural x POO

**Antes** — a validação era uma sequência de `if` solta dentro de `processar_edicao.php`:

```php
$marca = isset($_POST['marca']) ? $_POST['marca'] : '';
$modelo = isset($_POST['modelo']) ? $_POST['modelo'] : '';
// ... mais 6 linhas iguais ...

$erros = array();
if (empty($marca))  { $erros[] = "Marca é obrigatória"; }
if (empty($modelo)) { $erros[] = "Modelo é obrigatório"; }
// ... mais 4 ifs ...
```

**Depois** — o arquivo só **pede** a validação; quem sabe validar é o `Produto`:

```php
$produto = new Produto($_POST);
$erros   = $produto->validar();
```

Outra comparação, o **login**:

| | Antes | Depois |
|---|---|---|
| Conferir senha | `if($username === "admin" && $password === "123")` dentro do `acesso.php` | `$auth->entrar($username, $password)` |
| Checar se está logado | `isset($_SESSION['login'])` copiado em 3 arquivos | `$auth->estaLogado()` |
| Proteger página | `if(!isset($_SESSION...)) { header(...); exit(); }` | `$auth->exigirLogin()` |

O código das telas ficou **mais curto, mais legível e sem repetição**.

---

## 18. Exercícios

Tente fazer sozinho, do mais fácil ao mais difícil:

1. **Novo getter.** Adicione, na classe `Produto`, um método `getNomeCompleto()` que devolva a marca e o modelo juntos (ex.: `"Apple iPhone 17"`). Use-o na tabela de produtos.
2. **Nova regra.** No método `validar()`, avise se a descrição tiver mais de 200 caracteres. (Dica: `strlen()`.)
3. **Novo produto.** Adicione um quarto celular no arquivo `config/produtos.php`. Ele deve aparecer na lista sem mexer em mais nenhum arquivo. Por quê?
4. **Nova constante.** Crie `Produto::OPCOES_MARCA` e transforme o campo "Marca" do formulário em um `<select>`.
5. **Novo método.** Na classe `ProdutoRepositorio`, crie `contar()`, que devolve quantos produtos existem. Mostre o total na tela.
6. **Mais um usuário.** Faça a classe `Autenticacao` aceitar também o usuário `aluno` com a senha `456`. Perceba que **só um arquivo muda**.
7. **Nova classe (desafio).** Crie uma classe `Cliente` (nome, e-mail) com validação, e uma `ClienteRepositorio`, seguindo o mesmo padrão de `Produto`.

---

## 19. Glossário

| Termo | Significado simples |
|---|---|
| **Classe** | O molde, a receita |
| **Objeto** | O que é criado a partir do molde (também chamado de *instância*) |
| **Instanciar** | Criar um objeto com `new` |
| **Propriedade** | Variável que pertence à classe/objeto |
| **Método** | Função que pertence à classe/objeto |
| **Construtor** | Método `__construct`, executado automaticamente no `new` |
| **`$this`** | "Este objeto aqui" |
| **`->` (seta)** | Acessa uma propriedade ou método de um objeto |
| **`::`** | Acessa uma constante pela classe (`Produto::OPCOES_RAM`) |
| **`self`** | "Esta classe aqui" (usado dentro da própria classe) |
| **`public`** | Qualquer um pode acessar |
| **`private`** | Só a própria classe pode acessar |
| **Encapsulamento** | Proteger os dados da classe, liberando só o necessário |
| **Getter** | Método que devolve o valor de uma propriedade privada |
| **Constante** | Valor que nunca muda (`const`) |
| **Repositório** | Classe responsável por buscar/guardar objetos |
| **Autoload** | O PHP carregar a classe sozinho, sem `require` manual |
| **`null`** | "Nada", "vazio", "não encontrado" |

---

**Resumo em uma frase:** na POO, cada **classe** é um molde com **dados** (propriedades) e **ações** (métodos); você cria **objetos** a partir dela com `new` e pede para eles fazerem o trabalho.
