# Csharp-01: Painel Gentelella em ASP.NET Core MVC (.NET 8)

Projeto didático feito no **Visual Studio Community 2022**.

É a versão **C#** do projeto que na branch `php-03` foi feito em **PHP puro**. Ela reproduz as mesmas funcionalidades, agora com o padrão **MVC** e o template administrativo **Gentelella** (Bootstrap):

| Funcionalidade | Como funciona |
|---|---|
| **Login com sessão** | Usuário `admin`, senha `123`. Quem não está logado é mandado para o login. |
| **Sidebar ajustada** | Menu lateral com Principal, Produto, Cliente, Vendas, Relatórios e Configurações, mostrando o **nome do usuário logado**. |
| **Lista de produtos** | Tabela com iPhone 17, Galaxy S25 e Moto G10. |
| **Edição de produto** | Formulário preenchido, com **validação** e mensagem de sucesso. |
| **Logout** | Encerra a sessão e volta para o login. |

> **Ainda não há banco de dados.** Os produtos ficam numa lista em memória, como o array do PHP. A edição é uma **simulação**: valida e avisa que deu certo, mas não grava nada (igual ao PHP).

---

## Sumário

1. [O que é MVC](#1-o-que-é-mvc)
2. [Como rodar no Visual Studio](#2-como-rodar-no-visual-studio)
3. [Estrutura de pastas](#3-estrutura-de-pastas)
4. [Do PHP para o C#: tabela de equivalências](#4-do-php-para-o-c-tabela-de-equivalências)
5. [Como uma requisição percorre o projeto](#5-como-uma-requisição-percorre-o-projeto)
6. [O fluxo do login](#6-o-fluxo-do-login)
7. [O fluxo da edição (Post/Redirect/Get)](#7-o-fluxo-da-edição-postredirectget)
8. [Conceitos-chave explicados](#8-conceitos-chave-explicados)
9. [Segurança: o que foi feito e o que falta](#9-segurança-o-que-foi-feito-e-o-que-falta)
10. [Roteiro de testes](#10-roteiro-de-testes)
11. [Limitações conhecidas](#11-limitações-conhecidas)
12. [Exercícios e próximos passos](#12-exercícios-e-próximos-passos)
13. [Glossário](#13-glossário)

---

## 1. O que é MVC

**MVC** divide a aplicação em três papéis. Cada um cuida de uma coisa só:

| Letra | Papel | Pergunta que responde | Neste projeto |
|---|---|---|---|
| **M** | **Model** (modelo) | "Quais são os dados e as regras?" | `Models/Produto.cs` |
| **V** | **View** (visão) | "Como mostrar isso na tela?" | `Views/**/*.cshtml` |
| **C** | **Controller** (controlador) | "O que fazer com este pedido?" | `Controllers/*.cs` |

Uma analogia: num restaurante, o **Controller** é o garçom (recebe o pedido e coordena), o **Model** é a cozinha e a receita (os dados e as regras) e a **View** é o prato que chega à mesa.

No PHP, cada arquivo misturava os três papéis: o `produto_unitario.php` buscava dados, validava e montava HTML. No MVC cada papel fica em seu lugar, e isso facilita manter e crescer o sistema.

> **Observação.** Antes desta versão, o projeto usava *Razor Pages* (cada página com um `.cshtml` e um `.cshtml.cs`). Agora usa **MVC clássico**, com Controllers separados das Views. Os dois modelos fazem parte do ASP.NET Core; esta versão usa MVC para espelhar a organização da versão PHP.

---

## 2. Como rodar no Visual Studio

### Pré-requisitos

- **Visual Studio Community 2022** com a carga de trabalho **"ASP.NET e desenvolvimento para a Web"**.
- **.NET 8 SDK** (já vem com a carga de trabalho acima).

### Passo a passo

1. Abra o Visual Studio e escolha **Abrir um projeto ou solução**.
2. Selecione o arquivo **`Csharp-01.sln`** (na raiz do repositório).
3. Confirme que está na branch **`csharp-02`** (canto inferior direito do Visual Studio).
4. Na barra superior, deixe o perfil **https** selecionado (ao lado do botão verde de play).
5. Pressione **F5** (com depurador) ou **Ctrl + F5** (sem depurador).
6. O navegador abre em `https://localhost:7121`. Como você ainda não está logado, aparece a **tela de login**.
7. Entre com **`admin`** e **`123`**.

> Na primeira execução com https, o Visual Studio pergunta se você confia no certificado de desenvolvimento. Responda **Sim**.

Para parar: **Shift + F5**.

### Dica: Hot Reload

Com F5, você pode editar um `.cshtml`, salvar e dar F5 no navegador. A tela atualiza sem reiniciar a aplicação. Alterações em arquivos `.cs` costumam exigir reinício.

### Rodando pelo terminal (opcional)

```bash
cd Csharp-01
dotnet run
```

O endereço aparece no terminal (perfil `http`: `http://localhost:5158`).

---

## 3. Estrutura de pastas

```
backend/
├── Csharp-01.sln                      ← solução do Visual Studio
└── Csharp-01/                         ← o projeto
    ├── Program.cs                     ← ponto de entrada: serviços, sessão e rotas
    ├── Csharp-01.csproj               ← configuração (.NET 8)
    │
    ├── Controllers/                   ← o "C": recebem os pedidos
    │   ├── AcessoController.cs        ← login e logout
    │   ├── HomeController.cs          ← dashboard
    │   ├── ProdutoController.cs       ← lista e edição de produtos
    │   └── ErroController.cs          ← página de erro
    │
    ├── Models/                        ← o "M": dados e regras
    │   ├── Produto.cs                 ← produto + regras de validação
    │   └── LoginViewModel.cs          ← dados do formulário de login
    │
    ├── Services/                      ← acesso aos dados
    │   ├── IProdutoRepositorio.cs     ← "contrato" (interface)
    │   └── ProdutoRepositorio.cs      ← implementação em memória
    │
    ├── Filters/
    │   └── SessaoObrigatoriaAttribute.cs  ← o "guarda" de login
    │
    ├── Views/                         ← o "V": as telas (.cshtml)
    │   ├── _ViewImports.cshtml        ← imports comuns a todas as views
    │   ├── _ViewStart.cshtml          ← define o layout padrão
    │   ├── Shared/
    │   │   ├── _Layout.cshtml         ← molde do painel (sidebar + topo + rodapé)
    │   │   ├── _LayoutLogin.cshtml    ← molde da tela de login (sem menu)
    │   │   ├── _Head.cshtml           ← <head> com os CSS
    │   │   ├── _Sidebar.cshtml        ← menu lateral
    │   │   ├── _Top.cshtml            ← barra superior
    │   │   ├── _Footer.cshtml         ← rodapé
    │   │   └── _Scripts.cshtml        ← <script> do fim da página
    │   ├── Home/Index.cshtml          ← dashboard
    │   ├── Acesso/Login.cshtml        ← tela de login
    │   ├── Produto/Index.cshtml       ← lista de produtos
    │   ├── Produto/Editar.cshtml      ← formulário de edição
    │   └── Erro/Index.cshtml          ← tela de erro
    │
    └── wwwroot/                       ← arquivos estáticos (CSS, JS, fontes, imagens)
```

Duas regras para memorizar:

- **`Views/`** é processado pelo servidor (HTML + C#). O navegador nunca acessa esses arquivos diretamente.
- **`wwwroot/`** é entregue **como está** ao navegador. É o equivalente da pasta `public/` do PHP. Por isso o caminho `~/css/custom.css` aponta para `wwwroot/css/custom.css`.

---

## 4. Do PHP para o C#: tabela de equivalências

Quem fez a versão PHP vai reconhecer cada peça:

| PHP (`php-03`) | C# (`csharp-02`) | Observação |
|---|---|---|
| `index.php` + `pagina/home.php` | `HomeController.Index` + `Views/Home/Index.cshtml` | Dashboard |
| `login.php` | `AcessoController.Login` (GET) + `Views/Acesso/Login.cshtml` | Mostra o formulário |
| `acesso.php` | `AcessoController.Login` (POST) | Confere usuário e senha |
| `logout.php` | `AcessoController.Logout` | Encerra a sessão |
| `produto.php` + `pagina/lista_produto.php` | `ProdutoController.Index` + `Views/Produto/Index.cshtml` | Lista |
| `produto_unitario.php` + `pagina/editar_produto.php` | `ProdutoController.Editar` (GET) + `Views/Produto/Editar.cshtml` | Formulário |
| `processar_edicao.php` | `ProdutoController.Editar` (POST) | Valida e redireciona |
| `config/produtos.php` (array) | `Services/ProdutoRepositorio.cs` | Dados de exemplo |
| `config/header.php` (`<head>` + guarda) | `Views/Shared/_Head.cshtml` + `[SessaoObrigatoria]` | O guarda virou um atributo |
| `config/sidebar.php` | `Views/Shared/_Sidebar.cshtml` | Menu |
| `config/top.php` | `Views/Shared/_Top.cshtml` | Barra do topo |
| `config/footer.php` / `config/scripts.php` | `_Footer.cshtml` / `_Scripts.cshtml` | |
| `require_once 'config/x.php'` | `<partial name="_X" />` | Incluir um pedaço de tela |
| "Cascas" (`produto.php`...) que montam a página | `_Layout.cshtml` + `@RenderBody()` | O molde existe **uma vez só** |
| `session_start()` / `$_SESSION['x']` | `AddSession()` / `HttpContext.Session` | Sessão |
| `$_POST['marca']` | Model Binding (`Produto.Marca`) | Preenchimento automático |
| `$_GET['id']` | Parâmetro `int id` da Action | A URL fica `/Produto/Editar/2` |
| `htmlspecialchars()` | `@variavel` no Razor | Já escapa o HTML automaticamente |
| `header('Location: x'); exit();` | `return RedirectToAction(...)` | Redirecionar |
| `if (empty($marca)) $erros[] = ...` | `[Required(ErrorMessage = ...)]` | Validação declarativa |
| `?mensagem=...` na URL | `TempData["Mensagem"]` | Mensagem de sucesso |
| `public/` | `wwwroot/` | Arquivos estáticos |
| `default.conf` bloqueando `/config/` | Não é necessário | As Views **nunca** são servidas direto pelo navegador |

---

## 5. Como uma requisição percorre o projeto

Exemplo: o usuário já logado abre `https://localhost:7121/Produto`.

```
Navegador                         Servidor (ASP.NET Core)
    │                                       │
    │  GET /Produto                         │
    │ ────────────────────────────────────► │
    │                                       │ 1. Pipeline (Program.cs): arquivos estáticos,
    │                                       │    roteamento, SESSÃO...
    │                                       │ 2. Rota {controller}/{action}: "Produto" + "Index"
    │                                       │    => ProdutoController.Index()
    │                                       │ 3. Filtro [SessaoObrigatoria]: há alguém na sessão?
    │                                       │      não => redireciona para /Acesso/Login
    │                                       │      sim => continua
    │                                       │ 4. Action Index() pede a lista ao repositório
    │                                       │    e chama View(lista)
    │                                       │ 5. Razor monta Views/Produto/Index.cshtml
    │                                       │    e encaixa no _Layout (no @RenderBody())
    │  HTML pronto                          │
    │ ◄──────────────────────────────────── │
    │  GET /css/..., /js/... (wwwroot)      │
    │ ────────────────────────────────────► │
```

**Como o MVC acha a View?** Por **convenção de nomes**. `ProdutoController.Index()` → `Views/Produto/Index.cshtml`. Se o nome do arquivo estiver diferente, dá erro "view not found".

**Rota padrão** (definida no `Program.cs`): `{controller=Home}/{action=Index}/{id?}`

| URL | Controller | Action | Parâmetro |
|---|---|---|---|
| `/` | `Home` | `Index` | |
| `/Produto` | `Produto` | `Index` | |
| `/Produto/Editar/2` | `Produto` | `Editar` | `id = 2` |
| `/Acesso/Login` | `Acesso` | `Login` | |

---

## 6. O fluxo do login

```
 Navegador                                    Servidor
 ─────────                                    ────────
 1. Abre /Produto (sem login)        ───►  [SessaoObrigatoria]: "sem sessão!"
                                     ◄───  redireciona para /Acesso/Login

 2. Vê o formulário (GET)            ───►  AcessoController.Login() devolve a View

 3. Digita admin/123 e clica
    em "Entrar" (POST)               ───►  AcessoController.Login(model) confere:
                                             ├─ certo  → grava nome_user na SESSÃO
                                             │           e redireciona para /
                                             └─ errado → mostra a tela com o aviso
                                                         "Usuário ou senha inválidos."

 4. Navega pelo painel               ───►  [SessaoObrigatoria]: "tem sessão, pode"

 5. Clica em "Log Out" (topo)        ───►  AcessoController.Logout() limpa a sessão
                                     ◄───  e volta para /Acesso/Login
```

**O guarda** (`Filters/SessaoObrigatoriaAttribute.cs`) é a peça central:

```csharp
public override void OnActionExecuting(ActionExecutingContext context)
{
    var nomeUser = context.HttpContext.Session.GetString(SessaoChaves.NomeUser);

    if (string.IsNullOrEmpty(nomeUser))
    {
        context.Result = new RedirectToActionResult("Login", "Acesso", null);
    }
}
```

Para proteger uma tela, basta colocar `[SessaoObrigatoria]` em cima do Controller:

```csharp
[SessaoObrigatoria]
public class ProdutoController : Controller { ... }
```

O `AcessoController` **não** tem esse atributo. Se tivesse, a tela de login exigiria login e ninguém conseguiria entrar (um ciclo infinito de redirecionamentos).

---

## 7. O fluxo da edição (Post/Redirect/Get)

```
 1. GET /Produto/Editar/2                    ProdutoController.Editar(2)
        → formulário preenchido com o Galaxy S25

 2. Usuário altera e clica "Salvar" (POST)   ProdutoController.Editar(Produto produto)
        → o ASP.NET monta o objeto Produto e valida ([Required]...)
        ├─ com erros → devolve a MESMA tela com a lista de erros
        │              e com o que o usuário digitou (não perde nada)
        └─ sem erros → TempData["Mensagem"] = "Produto atualizado..."
                       e REDIRECIONA para /Produto
                       (a lista mostra o aviso verde, uma única vez)
```

**Por que redirecionar em vez de mostrar a resposta direto?** Se a resposta fosse exibida logo após o POST, apertar **F5** reenviaria o formulário ("Deseja reenviar os dados?"). Com o redirecionamento, o F5 só recarrega a lista. Esse padrão se chama **Post/Redirect/Get (PRG)**.

**A validação mora no Model**, junto dos dados (`Models/Produto.cs`):

```csharp
[Required(ErrorMessage = "Marca é obrigatória")]
public string Marca { get; set; } = string.Empty;
```

No PHP eram vários `if (empty(...))`. Aqui o ASP.NET aplica as regras sozinho. No Controller basta perguntar:

```csharp
if (!ModelState.IsValid)
{
    return View(produto);   // volta para o formulário mostrando os erros
}
```

> O atributo `required` do HTML roda **no navegador**, e qualquer pessoa consegue burlá-lo. Por isso a validação é feita **também no servidor**, o único lugar confiável.

---

## 8. Conceitos-chave explicados

### 8.1 Sessão

O servidor não "lembra" quem você é entre uma página e outra, porque o HTTP não guarda estado. A **sessão** resolve isso: é uma "gaveta" no servidor ligada ao seu navegador por um **cookie**.

```csharp
HttpContext.Session.SetString("nome_user", "admin");   // guarda
var nome = HttpContext.Session.GetString("nome_user"); // lê em qualquer outra página
HttpContext.Session.Clear();                           // esvazia (logout)
```

Ela precisa ser **ligada no `Program.cs`** em dois lugares: `AddSession()` (serviço) e `app.UseSession()` (pipeline, depois de `UseRouting`).

### 8.2 Injeção de dependência (DI)

O `ProdutoController` precisa de um repositório, mas **não o cria**. Ele o pede no construtor:

```csharp
public ProdutoController(IProdutoRepositorio repositorio)
{
    _repositorio = repositorio;
}
```

E o `Program.cs` diz ao ASP.NET qual classe entregar:

```csharp
builder.Services.AddSingleton<IProdutoRepositorio, ProdutoRepositorio>();
```

**Vantagem:** quando o banco de dados entrar, criamos `ProdutoRepositorioSql` e trocamos **só essa linha**. Nenhum Controller muda.

### 8.3 Model Binding

O ASP.NET lê os campos do formulário e preenche o objeto sozinho, pelos **nomes**. O campo `name="Marca"` vira `produto.Marca`. É o equivalente automático do `$_POST['marca']`.

### 8.4 Tag Helpers

Atributos `asp-*` que o servidor transforma em HTML comum:

| Você escreve | O navegador recebe |
|---|---|
| `<a asp-controller="Produto" asp-action="Index">` | `<a href="/Produto">` |
| `<a asp-action="Editar" asp-route-id="2">` | `<a href="/Produto/Editar/2">` |
| `<input asp-for="Marca" />` | `<input name="Marca" id="Marca" value="Samsung" ...>` |
| `<select asp-for="Ram" asp-items="...">` | `<select>` com as `<option>` e a atual já `selected` |
| `<form asp-action="Editar" method="post">` | `<form action="/Produto/Editar">` + **token anti-CSRF** |

Se a rota mudar, os links se corrigem sozinhos. No PHP eram escritos à mão (`href="produto.php"`).

### 8.5 Layout e Partials

- `_Layout.cshtml` é o **molde** (menu + topo + rodapé). Cada View escreve só o miolo, que entra no `@RenderBody()`.
- `_ViewStart.cshtml` diz que todas as Views usam `_Layout`. O Login troca para `_LayoutLogin` com `Layout = "_LayoutLogin";`.
- `<partial name="_Sidebar" />` inclui um pedaço reutilizável (o `require_once` do PHP).

### 8.6 Razor

O `@` mistura C# e HTML:

```html
<td>@produto.Marca</td>                       <!-- escreve o valor, já escapando o HTML -->
@foreach (var produto in Model) { ... }       <!-- repete -->
@if (!ViewData.ModelState.IsValid) { ... }    <!-- condição -->
@* isto é um comentário Razor: não vai para o navegador *@
```

`@model Produto` no topo da View diz qual tipo de dado ela recebe do Controller. Dentro dela, `Model` é esse dado.

### 8.7 TempData

Guarda um valor para **a próxima requisição** e depois o apaga. Ideal para mensagens de sucesso depois de um redirecionamento. Substitui o `?mensagem=...` do PHP, e a mensagem não aparece na URL.

### 8.8 Nullable (`string` x `string?`)

O projeto tem `<Nullable>enable</Nullable>`. Com isso, uma propriedade `string` sem `?` é tratada como **obrigatória** pelo ASP.NET. A `Descricao` é opcional, por isso é `string?`.

---

## 9. Segurança: o que foi feito e o que falta

### Já implementado

| Proteção | Onde | O que evita |
|---|---|---|
| **Guarda de sessão** | `[SessaoObrigatoria]` | Acessar telas sem login (inclusive os POST) |
| **Token anti-CSRF** | `[ValidateAntiForgeryToken]` + `<form asp-...>` | Outro site enviar formulários em nome do usuário. O PHP não tinha. |
| **Escape de HTML** | Razor (`@x`) | XSS (código malicioso digitado em um campo e executado ao exibir) |
| **Cookie `HttpOnly`** | `Program.cs` | JavaScript da página ler o cookie de sessão |
| **Validação no servidor** | `[Required]` + `ModelState` | Dados inválidos mesmo com o `required` do HTML burlado |
| **Erro genérico no login** | `AcessoController` | Não revela se foi o usuário ou a senha que errou |

### O que NÃO é adequado para produção (é só ensino)

- **Usuário e senha fixos no código** (`admin` / `123`). Num sistema real: banco de dados e **hash** de senha, como no **ASP.NET Core Identity**.
- **Sessão para autenticar.** O PHP usava `session_regenerate_id(true)` ao logar. O ASP.NET Core não tem um equivalente direto para a `Session`. Em sistemas reais, use **Cookie Authentication** ou **Identity**.
- **Logout por GET** (link simples no menu). O ideal é POST com token, para que outro site não consiga deslogar o usuário.
- **Sessão em memória.** Se a aplicação reiniciar, todo mundo perde o login.

---

## 10. Roteiro de testes

Use este roteiro depois de rodar o projeto:

| # | Ação | Resultado esperado |
|---|---|---|
| 1 | Abrir `/` sem estar logado | Vai para a tela de **login** |
| 2 | Abrir `/Produto` sem login | Vai para o **login** |
| 3 | Entrar com `admin` / `errada` | Volta ao login com **"Usuário ou senha inválidos."** |
| 4 | Entrar com `admin` / `123` | Abre o **dashboard**; o nome **admin** aparece na sidebar e no topo |
| 5 | Com login feito, abrir `/Acesso/Login` | Vai direto para o dashboard |
| 6 | Menu **Produto → Lista de Produtos** | Tabela com 3 produtos |
| 7 | Clicar em **Editar** do Galaxy S25 | Formulário preenchido (RAM 8GB, armazenamento 256GB) |
| 8 | Apagar a Marca e o Modelo e salvar (use F12 para remover o atributo `required`) | **"Corrija os campos abaixo"** com a lista de erros |
| 9 | Alterar algo e clicar em **Salvar Alterações** | Volta à lista com o **aviso verde** |
| 10 | Apertar **F5** na lista | O aviso **some** e não pede reenvio de formulário |
| 11 | Abrir `/Produto/Editar/99` | **"Produto não encontrado."** |
| 12 | Clicar em **Log Out** (menu do usuário, no topo) | Volta ao login |
| 13 | Após o logout, abrir `/Produto` | Vai para o login de novo |

> Os fluxos principais (guarda de sessão, login certo e errado, bloqueio sem token CSRF, edição com e sem erros, mensagem exibida uma única vez, produto inexistente, logout e arquivos estáticos) foram testados com requisições HTTP reais contra a aplicação rodando. A conferência **visual** no navegador, dentro do Visual Studio, ainda precisa ser feita por você.

---

## 11. Limitações conhecidas

1. **Sem banco de dados.** Os dados voltam ao original a cada reinício da aplicação.
2. **A edição não grava.** Igual ao PHP, só simula. O ponto exato para gravar está comentado em `ProdutoController.Editar` (POST).
3. **O botão "Excluir"** só mostra a confirmação (como no PHP).
4. **Menus Cliente, Vendas, Relatórios e Configurações** têm links `#`. São espaços reservados para as próximas aulas.
5. **"Criar Conta" e "Esqueceu a sua senha?"** na tela de login são só visuais.
6. **`bin/` e `obj/` não são versionadas.** O `.gitignore` da raiz ignora os arquivos gerados pela compilação (`bin/`, `obj/`), as preferências pessoais do Visual Studio (`.vs/`, `*.user`), pacotes NuGet, logs, resultados de teste, arquivos de publicação e segredos (`secrets.json`, `.env`, `*.pfx`). Quem clonar o projeto gera tudo de novo ao compilar (F5). O que **continua** versionado: código, Views, `wwwroot/` (inclusive `wwwroot/lib`), `launchSettings.json` e `appsettings.json`.

   > **Atenção:** um `.gitignore` só vale para arquivos que **ainda não** foram commitados. Se algum dia aparecer um arquivo gerado já rastreado, remova-o do controle sem apagá-lo do disco: `git rm -r --cached caminho/do/arquivo`.

---

## 12. Exercícios e próximos passos

1. **Fácil:** mude a senha em `AcessoController` e teste o login. Depois mude o texto do título "Login C#".
2. **Fácil:** em `Produto.cs`, adicione `[StringLength(50)]` na `Marca` e veja o erro aparecer ao digitar um texto longo.
3. **Médio:** crie o menu **Cliente** de verdade: `ClienteController`, `Views/Cliente/Index.cshtml` e atualize o link na `_Sidebar.cshtml`.
4. **Médio:** faça a edição **gravar na lista em memória**. Dica: adicione `Atualizar(Produto)` à interface `IProdutoRepositorio` e implemente em `ProdutoRepositorio`.
5. **Médio:** transforme o logout em **POST** com token anti-CSRF.
6. **Difícil:** troque o repositório em memória por **Entity Framework Core** com SQL Server LocalDB. Só o `Program.cs` e a classe do repositório devem mudar.
7. **Difícil:** troque a autenticação por **Cookie Authentication** (`AddAuthentication().AddCookie()`) e use `[Authorize]` no lugar do filtro caseiro.

---

## 13. Glossário

| Termo | Explicação |
|---|---|
| **MVC** | Padrão que separa Model (dados), View (tela) e Controller (fluxo) |
| **Controller** | Classe C# que recebe o pedido e decide a resposta |
| **Action** | Método público de um Controller (ex.: `Index`, `Editar`) |
| **View** | Arquivo `.cshtml` com o HTML da tela |
| **Model** | Classe com os dados e as regras de validação |
| **ViewModel** | Model feito para uma tela específica (ex.: `LoginViewModel`) |
| **Razor** | Sintaxe com `@` que mistura C# e HTML |
| **Layout** | Molde HTML comum a várias Views |
| **Partial** | Pedaço de View reaproveitável |
| **Tag Helper** | Atributo `asp-*` que o servidor transforma em HTML |
| **Model Binding** | Preenchimento automático de objetos com os dados do formulário/URL |
| **ModelState** | Resultado da validação (válido ou lista de erros) |
| **Data Annotations** | Atributos `[Required]`, `[StringLength]`... que declaram regras |
| **Sessão** | "Gaveta" no servidor ligada ao navegador por um cookie |
| **Filtro (Filter)** | Código que roda antes/depois de uma Action (ex.: o guarda de login) |
| **DI (Injeção de Dependência)** | O framework entrega ao construtor os objetos de que a classe precisa |
| **Repositório** | Classe responsável por buscar e gravar os dados |
| **PRG** | Post/Redirect/Get: redirecionar após um POST para evitar reenvio com F5 |
| **TempData** | Valor que sobrevive a uma única requisição (mensagens após redirecionamento) |
| **CSRF** | Ataque em que outro site envia um formulário em nome do usuário logado |
| **XSS** | Ataque que injeta código JavaScript em uma página |
| **Kestrel** | Servidor web embutido no ASP.NET Core |
| **Pipeline (middleware)** | Sequência de etapas pela qual todo pedido passa (`Program.cs`) |
| **wwwroot** | Pasta de arquivos estáticos entregues direto ao navegador |
| **Gentelella** | Template administrativo em Bootstrap usado como visual |
