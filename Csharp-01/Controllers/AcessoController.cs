using Csharp_01.Filters;
using Csharp_01.Models;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;

namespace Csharp_01.Controllers
{
    /// <summary>
    /// Cuida de entrar e sair do sistema. Tem três Actions:
    ///   Login (GET)   mostra o formulário
    ///   Login (POST)  confere usuário e senha
    ///   Logout        encerra a sessão
    ///
    /// ESTE controller NÃO tem [SessaoObrigatoria], senão ninguém conseguiria abrir a tela de login
    /// (a própria tela de login exigiria login, num ciclo infinito de redirecionamentos).
    /// </summary>
    public class AcessoController : Controller
    {
        // GET /Acesso/Login  - exibe o formulário
        [HttpGet]
        public IActionResult Login()
        {
            // Já está logado? Não precisa ver o formulário: vai direto para o painel.
            if (!string.IsNullOrEmpty(HttpContext.Session.GetString(SessaoChaves.NomeUser)))
            {
                return RedirectToAction("Index", "Home");
            }

            return View(new LoginViewModel());
        }

        // POST /Acesso/Login - recebe o formulário
        // [ValidateAntiForgeryToken]: confere o token de segurança que o <form> gera sozinho.
        // Impede que OUTRO site envie um formulário em nome do usuário (ataque CSRF).
        [HttpPost]
        [ValidateAntiForgeryToken]
        public IActionResult Login(LoginViewModel model)
        {
            // Credenciais FIXAS apenas para ensinar o fluxo. Num sistema real a senha
            // nunca fica no código: usa-se banco de dados + hash de senha (ex.: ASP.NET Identity).
            if (model.Username == "admin" && model.Password == "123")
            {
                // Limpa qualquer resto de sessão anterior antes de gravar os novos dados.
                // (A Session do ASP.NET Core não tem um recurso simples para trocar o ID da sessão ao logar,
                //  o que protege contra "session fixation". Em sistemas reais prefira Cookie Authentication/Identity.)
                HttpContext.Session.Clear();

                // Guarda quem entrou nas "gavetas" da sessão. Os dados valem até o logout ou até a sessão expirar.
                HttpContext.Session.SetString(SessaoChaves.NomeUser, model.Username);
                HttpContext.Session.SetString(SessaoChaves.Tipo, "admin");

                // Redireciona para o painel (Home/Index). Com o redirecionamento, apertar F5 não reenvia o formulário.
                return RedirectToAction("Index", "Home");
            }

            // Credenciais inválidas: mostramos a MESMA tela com o aviso.
            // Basta devolver a View com o erro registrado no ModelState: a tela de login o exibe.
            ModelState.AddModelError(string.Empty, "Usuário ou senha inválidos.");
            model.Password = string.Empty; // nunca devolva a senha digitada para a tela
            return View(model);
        }

        // GET /Acesso/Logout - encerra a sessão.
        // Usamos GET para manter o link do menu simples. Em produção o ideal é POST + token.
        [HttpGet]
        public IActionResult Logout()
        {
            HttpContext.Session.Clear();                 // apaga tudo que estava na sessão
            return RedirectToAction(nameof(Login));      // volta para o login
        }
    }
}
