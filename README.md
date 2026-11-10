# Do HTML ao PHP: o que mudou em cada fase

Este projeto é um painel de administração (login, lista de produtos e edição de produto) feito com **PHP puro**, sem frameworks.

Ele foi construído em **fases**. Cada fase tem uma branch no Git. Este README explica **o que foi alterado em cada fase, e por quê**, até a **Fase 3** (branch `php-03`).

---

## Índice

1. [Como rodar](#1-como-rodar)
2. [Visão geral das fases](#2-visão-geral-das-fases)
3. [Conceitos que você precisa conhecer](#3-conceitos-que-você-precisa-conhecer)
4. [Ponto de partida: um HTML estático](#4-ponto-de-partida-um-html-estático)
5. [Fase 1: do HTML para o PHP](#5-fase-1-do-html-para-o-php)
6. [Fase 2: login e sessão](#6-fase-2-login-e-sessão)
7. [Fase 3: organização, produtos e proteção](#7-fase-3-organização-produtos-e-proteção)
8. [Como as telas funcionam por dentro](#8-como-as-telas-funcionam-por-dentro)
9. [Avaliação do projeto](#9-avaliação-do-projeto)
10. [Teste você mesmo](#10-teste-você-mesmo)
11. [Glossário](#11-glossário)

---

## 1. Como rodar

```bash
docker compose up
```

Abra <http://localhost:8086> no navegador e entre com:

- **Usuário:** `admin`
- **Senha:** `123`

O `docker-compose.yml` sobe dois "computadores virtuais" (containers):

| Container | Para que serve |
|---|---|
| `app` | Executa o código **PHP** (PHP 8.3) |
| `web` | Servidor **nginx**: recebe o pedido do navegador e entrega a resposta |

O arquivo `default.conf` é a configuração do nginx: ele diz que arquivos `.php` devem ser enviados ao container `app`.

---

## 2. Visão geral das fases

| Branch | Fase | O que mudou | Resultado |
|---|---|---|---|
| `main` | Início | Um único `index.html` estático | Página bonita, mas sem lógica |
| `php-01` | Fase 1 | O HTML foi **quebrado em partes** e montado com PHP | Mesma página, código reaproveitável |
| `php-02` | Fase 2 | Entrou o **login** com sessão | Só quem entra com senha vê o painel |
| `php-03` | Fase 3 | Pastas organizadas, **telas de produto**, correções de segurança | Painel com lista e edição de produtos |

Em cada fase o projeto continuou funcionando. Ninguém precisou refazer tudo do zero.

---

## 3. Conceitos que você precisa conhecer

Estes conceitos aparecem em todas as fases. Vale ler antes.

### `require_once`: montar uma página com peças
Em vez de copiar o mesmo menu em dez páginas, escrevemos o menu **uma vez** em um arquivo e o **incluímos** onde precisar:

```php
require_once 'config/sidebar.php';   // "cole o conteúdo deste arquivo aqui"
```

### Sessão (`$_SESSION`)
O servidor não "lembra" de você entre uma página e outra. A **sessão** resolve isso: é uma "gaveta" no servidor, ligada ao seu navegador por um cookie. Guardamos nela quem fez login.

```php
session_start();                      // abre a gaveta
$_SESSION['nome_user'] = 'admin';     // guarda algo nela
echo $_SESSION['nome_user'];          // lê em qualquer outra página
```

### `GET` e `POST`
Duas formas de enviar dados do navegador para o servidor:

| | `GET` | `POST` |
|---|---|---|
| Onde vão os dados | Na URL (`produto_unitario.php?id=2`) | Dentro do pedido, invisível na URL |
| Como ler no PHP | `$_GET['id']` | `$_POST['marca']` |
| Usamos para | Abrir/consultar algo | Enviar formulários |

### Redirecionamento: `header()` + `exit()`
Para mandar o usuário para outra página:

```php
header('Location: login.php');   // "vá para login.php"
exit();                          // "pare o script aqui"
```

O `exit()` é **obrigatório**. Sem ele, o PHP continua executando o resto do arquivo depois do redirecionamento.

### `htmlspecialchars()`: proteger a saída
Se um texto digitado por alguém contiver código (`<script>...`), mostrá-lo direto na tela deixaria o navegador **executar** esse código. O `htmlspecialchars()` transforma os símbolos perigosos em texto inofensivo.

```php
<?= htmlspecialchars($produto['marca']) ?>
```

> `<?= ... ?>` é um atalho para `<?php echo ... ?>`.

### O operador `??`
Significa "use este valor; se ele não existir, use aquele":

```php
$username = $_POST['username'] ?? '';   // sem POST, vira texto vazio (sem erro)
```

---

## 4. Ponto de partida: um HTML estático

Na `main`, o projeto era **um único arquivo**, o `index.html`, com 364 linhas. Dentro dele estavam misturados o `<head>`, o menu lateral, a barra do topo, o conteúdo, o rodapé e os `<script>`.

Problemas dessa abordagem:

- Para criar uma segunda página, era preciso **copiar o arquivo inteiro**.
- Para mudar um item do menu, era preciso mudar **em todas as páginas**.
- Não havia como saber quem estava acessando (HTML não tem lógica de servidor).

---

## 5. Fase 1: do HTML para o PHP

📌 Branch `php-01`

### O que foi feito

O `index.html` foi **fatiado em partes**, cada uma em seu arquivo PHP, e o `index.php` as monta na ordem certa:

| Arquivo novo | O que contém |
|---|---|
| `header.php` | `<!DOCTYPE>`, `<head>`, links dos CSS |
| `sidebar.php` | Menu lateral |
| `top.php` | Barra superior (usuário e mensagens) |
| `content.php` | O conteúdo da página |
| `footer.php` | Rodapé |
| `scripts.php` | Os `<script>` do fim da página |
| `index.php` | **Monta tudo** com `require_once` |

O `index.html` foi apagado.

### Como o `index.php` monta a página

```
index.php
 ├─ require_once 'header.php'     →  <head> e CSS
 ├─ <body>
 │   ├─ require_once 'sidebar.php'  →  menu lateral
 │   ├─ require_once 'top.php'      →  barra do topo
 │   ├─ require_once 'content.php'  →  conteúdo
 │   └─ require_once 'footer.php'   →  rodapé
 └─ require_once 'scripts.php'    →  JavaScript
```

### Docker e nginx

Também nesta fase entraram o `docker-compose.yml` e o `default.conf`, para o projeto rodar igual em qualquer computador (PHP 8.3 + nginx), sem instalar nada além do Docker.

### O que ganhamos

- O menu existe em **um arquivo só**: mudou ali, mudou em todas as páginas.
- Criar uma nova página passou a ser: reaproveitar as peças e trocar apenas o conteúdo.
- O ambiente de desenvolvimento ficou padronizado.

---

## 6. Fase 2: login e sessão

📌 Branch `php-02`

### O que foi feito

Entrou o controle de acesso. Três arquivos novos:

| Arquivo | Função |
|---|---|
| `login.php` | Tela com o formulário de usuário e senha |
| `acesso.php` | Recebe o formulário (`POST`) e confere usuário e senha |
| `logout.php` | Encerra a sessão |

E duas alterações:

- `header.php` ganhou um **"guarda"**: se não há ninguém logado, redireciona para o `login.php`.
- O link "Log Out" do `top.php` passou a apontar para `logout.php` (antes apontava para `login.html`).
- Entrou o CSS `animate.min.css`, usado pela animação da tela de login.

### O fluxo do login

```
 Navegador                                  Servidor
 ─────────                                  ────────
 1. Abre qualquer página protegida  ───►  header.php: "não tem sessão!"
                                    ◄───  redireciona para login.php

 2. Preenche usuário e senha
    e clica em "Entrar" (POST)      ───►  acesso.php confere:
                                            ├─ certo  → guarda na $_SESSION
                                            │           e vai para index.php
                                            └─ errado → volta para login.php

 3. Navega pelo painel              ───►  header.php: "tem sessão, pode entrar"

 4. Clica em "Log Out"              ───►  logout.php apaga a sessão
                                    ◄───  e volta para login.php
```

### O código-chave

O guarda, no `header.php`:

```php
session_start();
if (!isset($_SESSION['nome_user']) && $_SERVER['REQUEST_URI'] != '/login.php') {
    header('Location: login.php');
    exit();
}
```

> Repare na exceção para o `login.php`. Sem ela, a página de login também exigiria login, e ninguém conseguiria entrar (um ciclo infinito de redirecionamentos).

A conferência, no `acesso.php`:

```php
if ($username === "admin" && $password === "123") {
    $_SESSION['nome_user'] = $username;   // guarda quem entrou
    $_SESSION['login']     = true;
    header('Location: index.php');
    exit();
}
```

---

## 7. Fase 3: organização, produtos e proteção

📌 Branch `php-03`

Esta é a maior fase. Foram **três grupos de mudanças**.

### 7.1 Organização em pastas

Com mais arquivos, a raiz do projeto ficou bagunçada. Foi feita uma arrumação:

| Antes (raiz) | Depois | Motivo |
|---|---|---|
| `header.php`, `top.php`, `sidebar.php`, `footer.php`, `scripts.php` | `config/` | Peças que se repetem em todas as páginas |
| `content.php` | `pagina/home.php` | Conteúdo específico de **uma** tela |
| `css/`, `js/`, `fonts/`, `images/` | `public/` | Arquivos que o navegador baixa direto |

Todos os `require_once` e os caminhos de CSS, JS e imagens foram atualizados. Exemplo:

```php
// antes
require_once 'sidebar.php';
// depois
require_once 'config/sidebar.php';
```

```html
<!-- antes -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- depois -->
<link href="public/css/bootstrap.min.css" rel="stylesheet">
```

### 7.2 Telas de produtos (novas)

| Arquivo | O que faz |
|---|---|
| `produto.php` | **Casca** da tela de lista (monta a página) |
| `pagina/lista_produto.php` | Tabela com os produtos e o botão "Editar" |
| `produto_unitario.php` | **Casca** da tela de edição |
| `pagina/editar_produto.php` | Formulário de edição, já preenchido |
| `processar_edicao.php` | Recebe o formulário, **valida** e redireciona |
| `config/produtos.php` | Os dados de exemplo (iPhone, Galaxy, Moto G10) |

Os dados dos produtos ainda não estão em um banco. Ficam em um **array**, que o PHP lê com `require`:

```php
// config/produtos.php
return [
    1 => ['marca' => 'Apple',   'modelo' => 'iPhone 17',  'ram' => '32GB', /* ... */],
    2 => ['marca' => 'Samsung', 'modelo' => 'Galaxy S25', 'ram' => '8GB',  /* ... */],
];
```

A tela de edição busca o produto pelo `id` que vem na URL:

```php
$produtos = require __DIR__ . '/../config/produtos.php';
$id       = (int) ($_GET['id'] ?? 0);      // (int) garante que é um número
$produto  = $produtos[$id] ?? null;        // null se o id não existir
```

E se o `id` não existir, o formulário não aparece: mostra "Produto não encontrado".

### 7.3 Correções de segurança e robustez

Aqui estão as melhorias que **não aparecem na tela**, mas fazem diferença.

| Mudança | Onde | Por que importa |
|---|---|---|
| **`$_POST['x'] ?? ''`** | `acesso.php` | Abrir `acesso.php` direto no navegador (sem formulário) gerava o aviso "Undefined index". |
| **`session_regenerate_id(true)`** | `acesso.php` | Troca o ID da sessão ao logar. Evita o ataque *session fixation* (alguém "planta" um ID de sessão e espera você logar com ele). |
| **Mensagem "Usuário ou senha inválidos"** | `acesso.php` + `login.php` | Antes, errar a senha só recarregava a tela, sem explicar nada. Agora volta com `?erro=1` e o `login.php` mostra o aviso. |
| **`basename($_SERVER['SCRIPT_NAME'])`** | `config/header.php` | Na Fase 2 o guarda comparava `REQUEST_URI` com `/login.php`. Isso **falhava** em `login.php?erro=1` (a URL tem o `?erro=1`) ou em subpasta. O `basename` olha só o nome do arquivo. |
| **`session_status() === PHP_SESSION_NONE`** | `config/header.php` | O `login.php` já abre a sessão antes do header. Isso evita abrir duas vezes (erro). |
| **Já logado? Pula o login** | `login.php` | Quem já entrou e abre `login.php` é mandado direto para o painel. |
| **Nome do usuário real** | `config/sidebar.php`, `config/top.php` | Era "John Doe" fixo (herança do template). Agora mostra o usuário da sessão, com `htmlspecialchars`. |
| **Proteção do `processar_edicao.php`** | `processar_edicao.php` | Esse arquivo **não passa pelo header**, então ganhou o seu próprio guarda de login. |
| **Bloqueio de `/config/` e `/pagina/` na URL** | `default.conf` | Quem digitasse `localhost:8086/config/top.php` veria pedaços de layout quebrados. Agora recebe `403 Proibido`. O PHP continua lendo esses arquivos normalmente com `require`. |
| **Pequenos erros de HTML** | `login.php`, `config/header.php` | Havia uma tag `</a` sem fechar, um `<html>` duplicado e o idioma `en`; passou a ser `pt-BR`. |

### 7.4 O fluxo de validação da edição

Este é o padrão mais importante da fase, o chamado **Post/Redirect/Get**:

```
 1. Usuário abre produto_unitario.php?id=2          (GET)
        → formulário preenchido com o Galaxy S25

 2. Usuário altera e clica "Salvar"                 (POST)
        → processar_edicao.php

 3. processar_edicao.php valida os campos:
        ├─ com erros → redireciona para
        │               produto_unitario.php?id=2&erros=...
        │               (a tela mostra a lista de erros)
        │
        └─ sem erros → redireciona para
                        produto.php?mensagem=Produto atualizado...
                        (a tela mostra o aviso verde)
```

**Por que redirecionar em vez de mostrar a resposta direto?** Se a resposta fosse exibida logo após o `POST`, apertar F5 reenviaria o formulário ("Deseja reenviar os dados?"). Com o redirecionamento, o F5 só recarrega a tela.

A validação em si é uma sequência de verificações:

```php
$erros = array();
if (empty($marca))  { $erros[] = "Marca é obrigatória"; }
if (empty($modelo)) { $erros[] = "Modelo é obrigatório"; }
// ... e assim por diante

if (empty($erros)) {
    // tudo certo
}
```

> Além disso, os campos têm o atributo `required` no HTML. Mas esse atributo roda **no navegador**, e qualquer pessoa consegue burlá-lo. Por isso a validação **também** é feita no PHP, que é o único lugar confiável.

---

## 8. Como as telas funcionam por dentro

### Estrutura final de pastas (Fase 3)

```
backend/
├── index.php                  ← casca do dashboard
├── produto.php                ← casca da lista de produtos
├── produto_unitario.php       ← casca da edição de produto
├── login.php                  ← tela de login
├── acesso.php                 ← confere usuário e senha
├── logout.php                 ← encerra a sessão
├── processar_edicao.php       ← valida e "salva" a edição
│
├── config/                    ← peças que se repetem
│   ├── header.php             (<head> + guarda de login)
│   ├── sidebar.php            (menu lateral)
│   ├── top.php                (barra do topo)
│   ├── footer.php
│   ├── scripts.php
│   └── produtos.php           (dados de exemplo)
│
├── pagina/                    ← o conteúdo de cada tela
│   ├── home.php
│   ├── lista_produto.php
│   └── editar_produto.php
│
├── public/                    ← CSS, JS, fontes e imagens
├── docker-compose.yml
└── default.conf               ← configuração do nginx
```

### Por que "arquivos casca"?

`index.php`, `produto.php` e `produto_unitario.php` são quase idênticos. O que muda é **uma única linha**, a que inclui o conteúdo:

```php
require_once 'pagina/lista_produto.php';    // ← só isso muda entre as três
```

Todo o resto (header, menu, topo, rodapé, scripts) é montado igual. Assim, as peças comuns ficam em `config/` e o conteúdo em `pagina/`.

---

## 9. Avaliação do projeto

Testei o projeto da branch `php-03` de ponta a ponta: login certo e errado, bloqueio de páginas sem login, lista, edição, produto inexistente, validação com erro, validação correta e logout. **Tudo funciona como esperado.**

### ✅ O que está bom

- **Código dividido em peças reaproveitáveis**: menu, topo e rodapé existem uma vez só.
- **Todas as páginas protegidas** por login, inclusive o `processar_edicao.php`, que não passa pelo header.
- **`exit()` depois de todo `header('Location...')`**.
- **Validação no servidor** além do `required` do navegador.
- **`htmlspecialchars` em toda saída** de dado do usuário ou da URL.
- **`(int)` no `id`** que vem da URL.
- **`session_regenerate_id`** ao logar.
- **Padrão Post/Redirect/Get** na edição.
- **Pastas organizadas** e comentários em português nos pontos importantes.
- **nginx bloqueando** o acesso direto a `config/` e `pagina/`.

---

## 10. Teste você mesmo

Siga esta lista com o projeto rodando e **observe o que acontece**:

1. Abra `localhost:8086/produto.php` **sem estar logado**. Para onde você foi enviado? (Fase 2)
2. Entre com a senha **errada**. O que aparece na tela? (Fase 3)
3. Entre com `admin` / `123`. O nome no canto da tela é o seu usuário? (Fase 3)
4. Com o login feito, abra `localhost:8086/login.php`. O que acontece? (Fase 3)
5. Abra `localhost:8086/config/top.php`. Qual é a resposta? (Fase 3)
6. Na lista de produtos, clique em **Editar** no Galaxy S25. Veja o `?id=2` na URL.
7. Troque o `id` na URL para `99`. O que aparece?
8. Edite um produto, **apague a marca** e clique em "Salvar". Veja a URL e a mensagem de erro. (Dica: se o navegador não deixar enviar, é o `required` dele; remova o atributo pelo "Inspecionar" para testar o PHP.)
9. Edite corretamente e salve. Veja a mensagem verde. Ela é permanente? (Dica: o produto mudou na lista?)
10. Clique em "Log Out". Tente voltar com o botão "voltar" do navegador. O que acontece?

---

## 11. Glossário

| Termo | Significado simples |
|---|---|
| **Fase** | Uma etapa do projeto, guardada em uma branch do Git |
| **Branch** | Uma "linha do tempo" paralela do código no Git |
| **Include / `require_once`** | Trazer o conteúdo de outro arquivo para dentro da página |
| **Sessão** | Memória do servidor sobre você, ligada ao seu navegador por um cookie |
| **Cookie** | Pequeno dado guardado no navegador (aqui, o número da sua sessão) |
| **`GET` / `POST`** | Formas de enviar dados ao servidor (pela URL / escondido) |
| **Redirecionamento** | Mandar o navegador para outra URL (`header('Location: ...')`) |
| **Post/Redirect/Get** | Depois de receber um `POST`, redirecionar, para o F5 não reenviar o formulário |
| **Validação** | Conferir se os dados enviados estão corretos |
| **Session fixation** | Ataque em que alguém define o ID da sua sessão antes de você logar |
| **XSS** | Ataque em que um texto digitado vira código executado no navegador |
| **CSRF** | Ataque em que outro site faz seu navegador executar uma ação sem você perceber |
| **Casca (shell)** | Arquivo que só monta a página com as peças |
| **Container** | "Computador virtual" leve do Docker |
| **nginx** | Servidor web: recebe o pedido do navegador e devolve a resposta |
| **Array** | Lista de valores (`['a', 'b']` ou `['chave' => 'valor']`) |
