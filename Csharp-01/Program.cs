using Csharp_01.Services;

// =====================================================================
//  Program.cs - o PONTO DE ENTRADA da aplicação.
//  Aqui fazemos duas coisas, sempre nesta ordem:
//    1) Registrar os SERVIÇOS que a aplicação vai usar (builder.Services...)
//    2) Montar o PIPELINE: a sequência de etapas pela qual todo pedido passa (app.Use...)
// =====================================================================

var builder = WebApplication.CreateBuilder(args);

// ---------------------------------------------------------------------
// 1) SERVIÇOS
// ---------------------------------------------------------------------

// Habilita o padrão MVC: Controllers (lógica) + Views (telas .cshtml).
// Na versão PHP, cada tela era um arquivo (produto.php, login.php...).
// Aqui, quem decide o que mostrar é um Controller.
builder.Services.AddControllersWithViews();

// SESSÃO - equivalente ao session_start() / $_SESSION do PHP.
// A sessão guarda dados no servidor e liga esses dados ao navegador por um cookie.
// AddDistributedMemoryCache: onde os dados da sessão ficam guardados (na memória do servidor).
// ATENÇÃO: se você reiniciar a aplicação, todo mundo "perde o login".
builder.Services.AddDistributedMemoryCache();
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30); // sem usar por 30 min => sessão expira
    options.Cookie.HttpOnly = true;                 // o JavaScript do navegador NÃO consegue ler o cookie (mais seguro)
    options.Cookie.IsEssential = true;              // cookie necessário para o site funcionar (login)
});

// INJEÇÃO DE DEPENDÊNCIA: quando um Controller pedir um IProdutoRepositorio no construtor,
// o ASP.NET entrega uma instância de ProdutoRepositorio. Singleton = uma única instância para todos.
// Quando houver banco de dados, trocamos só esta linha (ex.: ProdutoRepositorioSql).
builder.Services.AddSingleton<IProdutoRepositorio, ProdutoRepositorio>();

var app = builder.Build();

// ---------------------------------------------------------------------
// 2) PIPELINE (a ORDEM importa!)
// ---------------------------------------------------------------------

if (!app.Environment.IsDevelopment())
{
    // Em produção, qualquer erro não tratado vai para /Erro (ErroController).
    // Em desenvolvimento, o ASP.NET mostra a página de erro detalhada.
    app.UseExceptionHandler("/Erro");
    app.UseHsts();
}

app.UseHttpsRedirection(); // converte http em https
app.UseStaticFiles();      // libera os arquivos da pasta wwwroot (css, js, imagens, fontes)

app.UseRouting();          // descobre qual Controller/Action atende a URL

app.UseSession();          // ativa a sessão. Deve vir DEPOIS do UseRouting e ANTES do MapControllerRoute

app.UseAuthorization();

// ROTA PADRÃO: /{controller}/{action}/{id?}
//   /                    -> HomeController.Index        (dashboard)
//   /Produto             -> ProdutoController.Index     (lista)
//   /Produto/Editar/2    -> ProdutoController.Editar(2) (edição)
//   /Acesso/Login        -> AcessoController.Login      (tela de login)
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
