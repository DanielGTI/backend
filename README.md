# Csharp-01

Projeto de estudo em **C# / ASP.NET Core Razor Pages (.NET 8)**, criado no **Visual Studio Community 2022**.

A ideia do projeto é pegar um **template HTML pronto** (o *Gentelella*, um painel administrativo feito com Bootstrap) e colocá-lo dentro de uma aplicação web em C#. Hoje o projeto é a base para essa migração: o servidor já funciona e já carrega o visual do painel, mas as páginas internas do template ainda não foram convertidas.

> Este documento explica o que existe no projeto, como cada peça funciona e como rodar no Visual Studio.

---

## Sumário

1. [O que é Razor Pages](#1-o-que-é-razor-pages)
2. [Pré-requisitos](#2-pré-requisitos)
3. [Como abrir e rodar no Visual Studio](#3-como-abrir-e-rodar-no-visual-studio)
4. [Estrutura de pastas](#4-estrutura-de-pastas)
5. [Como o projeto funciona, passo a passo](#5-como-o-projeto-funciona-passo-a-passo)
6. [Explicando cada arquivo](#6-explicando-cada-arquivo)
7. [Os dois layouts](#7-os-dois-layouts)
8. [Perfis de execução (http, https, IIS Express)](#8-perfis-de-execução-http-https-iis-express)
9. [Pontos de atenção](#9-pontos-de-atenção)
10. [Próximos passos sugeridos](#10-próximos-passos-sugeridos)
11. [Glossário rápido](#11-glossário-rápido)

---

## 1. O que é Razor Pages

**Razor Pages** é um modelo do ASP.NET Core para criar sites onde **cada página tem dois arquivos**:

| Arquivo | Papel | Linguagem |
|---|---|---|
| `Index.cshtml` | A **tela** (o que o usuário vê) | HTML + Razor (`@...`) |
| `Index.cshtml.cs` | A **lógica** da tela (o que acontece por trás) | C# |

Pense assim: o `.cshtml` é a vitrine e o `.cshtml.cs` é o balcão que atende os pedidos.

**Razor** é a sintaxe que permite misturar C# dentro do HTML usando o símbolo `@`. Exemplo deste projeto:

```html
<title>@ViewData["Title"] - Csharp_01</title>
```

O servidor troca `@ViewData["Title"]` pelo texto real (por exemplo, "Home page") antes de enviar a página ao navegador.

---

## 2. Pré-requisitos

- **Visual Studio Community 2022** (versão 17.x) com a carga de trabalho **"ASP.NET e desenvolvimento para a Web"** instalada.
- **.NET 8 SDK** (já vem com o Visual Studio 2022 quando essa carga de trabalho é marcada).

Não é necessário instalar banco de dados nem nenhum serviço extra. O projeto não usa banco de dados por enquanto.

> Para conferir/instalar a carga de trabalho: abra o **Visual Studio Installer**, clique em **Modificar** no Visual Studio Community e marque "ASP.NET e desenvolvimento para a Web".

---

## 3. Como abrir e rodar no Visual Studio

### 3.1 Abrir o projeto

1. Abra o Visual Studio.
2. Clique em **Abrir um projeto ou solução**.
3. Selecione o arquivo **`Csharp-01.sln`**, que está na raiz do repositório.
   - O `.sln` (*solution*) é o "guarda-chuva" que agrupa um ou mais projetos. Aqui ele contém um só projeto: `Csharp-01`.
4. No **Gerenciador de Soluções** (painel lateral direito), você verá a árvore de arquivos do projeto.

### 3.2 Escolher como executar

Na barra superior há um botão verde de "play" com uma seta ao lado. Essa seta abre a lista de **perfis de execução**:

- **https** (perfil que está selecionado por padrão neste projeto)
- **http**
- **IIS Express**

Para começar, deixe **https**.

### 3.3 Rodar

| Atalho | O que faz |
|---|---|
| **F5** | Executa com o **depurador** anexado (permite parar em *breakpoints*) |
| **Ctrl + F5** | Executa **sem depurador** (mais rápido para só ver a página) |

O que acontece quando você aperta F5:

1. O Visual Studio **compila** o projeto (gera os arquivos em `bin/` e `obj/`).
2. Inicia o servidor web interno (**Kestrel**).
3. Abre o navegador automaticamente em `https://localhost:7121`.

> Na primeira execução com https, o Visual Studio pode perguntar se você confia no certificado de desenvolvimento do ASP.NET Core. Responda **Sim**. Isso é normal e é só para a sua máquina.

### 3.4 Parar

Clique no quadrado vermelho da barra superior ou use **Shift + F5**.

### 3.5 Alteração de código com a aplicação rodando

Com o depurador (F5) é possível usar o **Hot Reload** (ícone de chama na barra superior): você edita o `.cshtml`, salva e atualiza o navegador, sem precisar parar e iniciar tudo de novo. Mudanças em arquivos `.cs` às vezes exigem reiniciar.

---

## 4. Estrutura de pastas

```
backend/
├── Csharp-01.sln                  ← solução do Visual Studio
└── Csharp-01/                     ← o projeto
    ├── Csharp-01.csproj           ← configuração do projeto (.NET 8, Web)
    ├── Program.cs                 ← ponto de entrada: monta e inicia o servidor
    ├── appsettings.json           ← configurações (nível de log, hosts permitidos)
    ├── appsettings.Development.json
    ├── Properties/
    │   └── launchSettings.json    ← perfis de execução (portas, ambiente)
    ├── Pages/                     ← as páginas do site
    │   ├── Index.cshtml / .cs     ← página inicial  (rota "/")
    │   ├── Privacy.cshtml / .cs   ← página de privacidade (rota "/Privacy")
    │   ├── Error.cshtml / .cs     ← página de erro (rota "/Error")
    │   ├── _ViewStart.cshtml      ← define qual layout as páginas usam
    │   ├── _ViewImports.cshtml    ← imports e configurações comuns às páginas
    │   └── Shared/
    │       ├── _Layout.cshtml     ← layout ATIVO (painel Gentelella)
    │       ├── _Layout2.cshtml    ← layout padrão do template (não usado agora)
    │       └── _ValidationScriptsPartial.cshtml
    ├── wwwroot/                   ← arquivos estáticos (css, js, imagens, fontes)
    │   ├── css/ js/ fonts/ images/   ← arquivos do template Gentelella
    │   └── lib/                      ← bibliotecas (Bootstrap, jQuery, validação)
    ├── bin/  e  obj/              ← gerados na compilação (não editar)
```

Regra de ouro para memorizar:

- **`Pages/`** = o que é processado pelo servidor (HTML com C#).
- **`wwwroot/`** = o que é entregue **como está** ao navegador (css, js, imagens).

---

## 5. Como o projeto funciona, passo a passo

Quando você digita `https://localhost:7121/` no navegador, acontece o seguinte:

```
Navegador                         Servidor (ASP.NET Core / Kestrel)
    │                                          │
    │  1. GET /                                │
    │ ───────────────────────────────────────► │
    │                                          │  2. Passa pelo "pipeline" do Program.cs
    │                                          │     (https, arquivos estáticos, rotas...)
    │                                          │  3. A rota "/" aponta para Pages/Index
    │                                          │  4. Cria o IndexModel e executa OnGet()
    │                                          │  5. Renderiza Index.cshtml
    │                                          │  6. Coloca o resultado dentro de _Layout.cshtml
    │                                          │     (no lugar do @RenderBody())
    │  7. Recebe o HTML pronto                 │
    │ ◄─────────────────────────────────────── │
    │  8. Pede os arquivos css/js/imagens      │
    │     (servidos a partir de wwwroot)       │
```

Em palavras simples:

1. O navegador pede uma página.
2. O servidor descobre qual página corresponde ao endereço (o **roteamento** de Razor Pages usa o nome do arquivo: `Pages/Privacy.cshtml` vira `/Privacy`).
3. Executa o código C# da página (`OnGet`).
4. Monta o HTML usando o `.cshtml` **dentro do layout**.
5. Devolve o HTML ao navegador, que depois baixa os CSS, JS e imagens da pasta `wwwroot`.

---

## 6. Explicando cada arquivo

### `Csharp-01.csproj`: a configuração do projeto

```xml
<Project Sdk="Microsoft.NET.Sdk.Web">
  <PropertyGroup>
    <TargetFramework>net8.0</TargetFramework>
    <Nullable>enable</Nullable>
    <ImplicitUsings>enable</ImplicitUsings>
    <RootNamespace>Csharp_01</RootNamespace>
  </PropertyGroup>
  ...
</Project>
```

| Linha | Significado |
|---|---|
| `Sdk="Microsoft.NET.Sdk.Web"` | Projeto web (traz o ASP.NET Core) |
| `net8.0` | Usa o **.NET 8** |
| `Nullable` enable | O compilador avisa quando algo pode ser `null` (ex.: `string?`) |
| `ImplicitUsings` enable | Dispensa escrever os `using` mais comuns (`System`, `System.Linq` etc.) |
| `RootNamespace` | Namespace base: `Csharp_01` (o hífen do nome vira `_`, porque o C# não aceita hífen em nomes) |

### `Program.cs`: o ponto de entrada

É onde a aplicação **nasce**. Dividido em partes:

```csharp
var builder = WebApplication.CreateBuilder(args);
builder.Services.AddRazorPages();
var app = builder.Build();
```

- Cria o "construtor" da aplicação e **registra os serviços** (aqui, o Razor Pages).
- `builder.Build()` finaliza a configuração e gera o `app`.

```csharp
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Error");
    app.UseHsts();
}
```

- Só em **produção**: se der erro, redireciona para a página `/Error` e ativa o HSTS (força o navegador a usar sempre HTTPS).
- Em **desenvolvimento** o erro detalhado aparece na tela, o que ajuda a depurar.

```csharp
app.UseHttpsRedirection();   // converte http em https
app.UseStaticFiles();        // libera css/js/imagens de wwwroot
app.UseRouting();            // ativa o sistema de rotas
app.UseAuthorization();      // ponto onde entraria o controle de acesso (ainda sem uso)
app.MapRazorPages();         // liga as URLs às páginas da pasta Pages
app.Run();                   // inicia o servidor
```

Essa sequência é o **pipeline**: o pedido passa por cada etapa, **na ordem em que estão escritas**.

### `Pages/Index.cshtml` e `Index.cshtml.cs`: página inicial

`Index.cshtml`:

```html
@page
@model IndexModel
@{
    ViewData["Title"] = "Home page";
}
<div class="text-center">
    <h1 class="display-4">Welcome</h1>
    ...
</div>
```

- `@page`: marca o arquivo como uma Razor Page (sem isso ele não responde a uma URL).
- `@model IndexModel`: informa qual classe C# cuida desta página.
- `ViewData["Title"]`: guarda o título, que o layout usa dentro da tag `<title>`.

`Index.cshtml.cs`:

```csharp
public class IndexModel : PageModel
{
    private readonly ILogger<IndexModel> _logger;

    public IndexModel(ILogger<IndexModel> logger) { _logger = logger; }

    public void OnGet() { }
}
```

- `PageModel`: classe base de toda página Razor.
- O construtor recebe um `ILogger` por **injeção de dependência**: o próprio ASP.NET entrega o objeto pronto.
- `OnGet()` roda quando a página é aberta por um GET (ou seja, quando você digita o endereço). Por enquanto está vazio.

`Privacy.cshtml(.cs)` segue o mesmo padrão, com o título "Privacy Policy".

### `Pages/Error.cshtml(.cs)`: página de erro

Mostra "An error occurred while processing your request" e, se existir, o **Request ID** (um identificador do pedido que ajuda a localizar o erro nos logs). O C# usa:

```csharp
RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier;
```

Ou seja: usa o ID da atividade atual; se não houver, usa o identificador do próprio pedido HTTP.

### `Pages/_ViewStart.cshtml`: escolhe o layout

```csharp
@{ Layout = "_Layout"; }
```

Roda **antes de cada página** e diz: "use o layout `_Layout`". Para trocar o visual do site inteiro, basta mudar esse nome.

### `Pages/_ViewImports.cshtml`: configurações comuns

```csharp
@using Csharp_01
@namespace Csharp_01.Pages
@addTagHelper *, Microsoft.AspNetCore.Mvc.TagHelpers
```

- Evita repetir `@using` em todas as páginas.
- Define o namespace das páginas.
- Habilita os **Tag Helpers** (atributos como `asp-page`, que geram links corretos automaticamente).

### `Pages/Shared/_Layout.cshtml`: o molde da página

É o esqueleto HTML comum (cabeçalho, menu lateral, rodapé). O ponto principal é a linha:

```csharp
@RenderBody()
```

É nela que o conteúdo de cada página (`Index`, `Privacy`...) é **encaixado**. Tudo o que está ao redor aparece igual em todas as páginas.

### `appsettings.json`: configurações

```json
{
  "Logging": { "LogLevel": { "Default": "Information", "Microsoft.AspNetCore": "Warning" } },
  "AllowedHosts": "*"
}
```

- `Logging`: define o nível de detalhe das mensagens de log (aparecem na janela **Saída** do Visual Studio).
- `AllowedHosts: "*"`: aceita pedidos para qualquer nome de host.
- `appsettings.Development.json` sobrescreve valores apenas quando o ambiente é *Development*.

---

## 7. Os dois layouts

O projeto tem **dois** layouts na pasta `Pages/Shared`:

| Arquivo | Origem | Está em uso? |
|---|---|---|
| `_Layout.cshtml` | **Template Gentelella** (painel administrativo: menu lateral, barra superior, rodapé) | **Sim** (`_ViewStart` aponta para ele) |
| `_Layout2.cshtml` | Layout padrão que o Visual Studio gera (barra superior simples com Bootstrap) | Não, foi guardado como referência |

Isso mostra uma técnica comum: o desenvolvedor renomeou o layout original para `_Layout2` e colocou o HTML do template no lugar de `_Layout`. Para voltar ao visual padrão, é só trocar em `_ViewStart.cshtml`:

```csharp
Layout = "_Layout2";
```

Diferença importante entre os dois ao referenciar arquivos:

- `_Layout2` usa `~/css/site.css` e `asp-page="/Index"`: formas **recomendadas** do ASP.NET Core (o `~/` aponta para `wwwroot` e funciona em qualquer URL).
- `_Layout` usa `css/bootstrap.min.css` e `index.html`: caminhos copiados do HTML original (ver [pontos de atenção](#9-pontos-de-atenção)).

---

## 8. Perfis de execução (http, https, IIS Express)

Definidos em `Properties/launchSettings.json`:

| Perfil | Endereço | Servidor |
|---|---|---|
| **http** | `http://localhost:5158` | Kestrel |
| **https** (padrão) | `https://localhost:7121` e `http://localhost:5158` | Kestrel |
| **IIS Express** | `http://localhost:40969` (SSL: `44347`) | IIS Express |

- **Kestrel** é o servidor web embutido do ASP.NET Core, o mais usado hoje.
- **IIS Express** é uma versão leve do IIS do Windows, útil para simular o ambiente de hospedagem Windows.
- Todos rodam com `ASPNETCORE_ENVIRONMENT = Development`, o que ativa a página de erro detalhada e as configurações de `appsettings.Development.json`.

O perfil selecionado fica salvo em `Csharp-01.csproj.user` (`ActiveDebugProfile = https`).

---

## 9. Pontos de atenção

Observações encontradas ao ler o código. Elas ajudam a entender o estado atual do projeto:

1. **Links do menu apontam para páginas que ainda não existem.**
   O menu lateral do `_Layout.cshtml` tem links como `index2.html`, `form.html`, `tables.html` etc. Eles vêm do template original (HTML estático) e **ainda não foram convertidos em Razor Pages**. Clicar neles retorna erro 404. O primeiro link (`index.html`) também não existe: a página inicial agora é `/`.

2. **Caminhos relativos nos CSS/JS do `_Layout.cshtml`.**
   O layout usa `href="css/bootstrap.min.css"` (sem `/` nem `~/`). Funciona nas páginas de primeiro nível (`/`, `/Privacy`, `/Error`), mas **quebraria** em uma página dentro de subpasta (por exemplo `/Clientes/Lista`), porque o navegador procuraria `/Clientes/css/...`. A correção recomendada é usar `~/css/...`:
   ```html
   <link href="~/css/bootstrap.min.css" rel="stylesheet" />
   ```

3. **Um bloco de conteúdo do template está comentado.**
   Dentro do `_Layout.cshtml`, o trecho `<!-- page content ... /page content -->` contém o exemplo da "Plain Page" do template. Ele está comentado, e logo abaixo vem o `@RenderBody()`. Foi uma forma de guardar o modelo de como montar uma página dentro do painel.

4. **Pastas `bin/` e `obj/` estão no controle de versão (Git).**
   Elas são geradas automaticamente a cada compilação e **não deveriam** ser versionadas, pois causam conflitos entre máquinas. Recomenda-se criar um `.gitignore` para projetos .NET (o Visual Studio oferece isso em *Git > Configurações > Arquivos de ignorar*, ou use o modelo "VisualStudio" do GitHub) e remover essas pastas do Git com `git rm -r --cached Csharp-01/bin Csharp-01/obj`.

5. **Arquivos duplicados em `wwwroot`.**
   Existem dois conjuntos de bibliotecas: os do **template** (`wwwroot/css`, `wwwroot/js`) e os **padrão do Visual Studio** (`wwwroot/lib`). Como o `_Layout` ativo só usa os do template, o `wwwroot/lib` hoje só é usado pelo `_Layout2`.

6. **Linha solta no `.csproj`.**
   A linha `<None Include="Pages\Shared\_Layout.cshtml" />` indica ao MSBuild que o arquivo é do tipo "None" (não processado na compilação). Provavelmente foi adicionada pelo Visual Studio ao renomear/mover layouts. Como as páginas Razor já são tratadas pelo SDK Web, ela é desnecessária, mas inofensiva.

---

## 10. Próximos passos sugeridos

Uma ordem didática para continuar o estudo:

1. **Corrigir os caminhos** do `_Layout.cshtml` para `~/css/...`, `~/js/...` e `~/images/...`.
2. **Criar uma nova página** no Visual Studio: clique com o botão direito em `Pages` → **Adicionar** → **Razor Page** (escolha "Razor Page - Vazia"). Isso gera o par `.cshtml` e `.cshtml.cs`.
3. **Converter uma página do template** (por exemplo `tables.html`) em uma Razor Page: copie o miolo do HTML para o `.cshtml` e ajuste o link do menu para `asp-page="/Tables"`.
4. **Passar dados do C# para a tela**: crie uma propriedade pública no `PageModel` (ex.: `public string Nome { get; set; }`), preencha no `OnGet()` e mostre no HTML com `@Model.Nome`.
5. **Adicionar um formulário** com `OnPost()` para receber dados digitados pelo usuário.
6. Só depois, pensar em **banco de dados** (Entity Framework Core).

---

## 11. Glossário rápido

| Termo | Explicação |
|---|---|
| **ASP.NET Core** | Plataforma da Microsoft para criar sites e APIs em C# |
| **Razor Pages** | Modelo de páginas onde cada tela tem `.cshtml` + `.cshtml.cs` |
| **Razor** | Sintaxe com `@` para misturar C# no HTML |
| **Layout** | Molde HTML comum a várias páginas |
| **PageModel** | Classe C# que controla uma página |
| **Kestrel** | Servidor web embutido no ASP.NET Core |
| **Pipeline (middleware)** | Sequência de etapas pela qual todo pedido passa |
| **Injeção de dependência** | O framework entrega ao construtor os objetos de que a classe precisa |
| **Solution (`.sln`)** | Arquivo que agrupa projetos no Visual Studio |
| **wwwroot** | Pasta de arquivos estáticos entregues direto ao navegador |
| **Hot Reload** | Aplica alterações de código sem reiniciar a aplicação |
| **Gentelella** | Template administrativo em Bootstrap usado como visual do projeto |
