using Csharp_01.Filters;
using Csharp_01.Models;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;

namespace Csharp_01.Controllers
{
    /// <summary>
    /// Cuida de entrar e sair do sistema. Junta três arquivos da versão PHP:
    ///   login.php  -> Login (GET)   mostra o formulário
    ///   acesso.php -> Login (POST)  confere usuário e senha
    ///   logout.php -> Logout        encerra a sessão
    ///
    /// ESTE controller NÃO tem [SessaoObrigatoria], senão ninguém conseguiria abrir a tela de login
    /// (o ciclo infinito de redirecionamentos que o PHP evitava com a exceção "login.php").
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
        // O PHP deste projeto não tinha essa proteção.
        [HttpPost]
        [ValidateAntiForgeryToken]
        public IActionResult Login(LoginViewModel model)
        {
            // Credenciais FIXAS apenas para ensinar o fluxo. Num sistema real a senha
            // nunca fica no código: usa-se banco de dados + hash de senha (ex.: ASP.NET Identity).
            if (model.Username == "admin" && model.Password == "123")
            {
                // Limpa qualquer resto de sessão anterior antes de gravar os novos dados.
                // (O PHP usava session_regenerate_id(true). O ASP.NET Core não tem um equivalente direto
                //  para a Session; em sistemas reais prefira Cookie Authentication/Identity.)
                HttpContext.Session.Clear();

                // Guarda quem entrou nas "gavetas" da sessão (PHP: $_SESSION['nome_user'] = $username)
                HttpContext.Session.SetString(SessaoChaves.NomeUser, model.Username);
                HttpContext.Session.SetString(SessaoChaves.Tipo, "admin");

                // Redireciona para o painel (PHP: header('Location: index.php'); exit();)
                return RedirectToAction("Index", "Home");
            }

            // Credenciais inválidas: mostramos a MESMA tela com o aviso.
            // (O PHP redirecionava para login.php?erro=1; aqui basta devolver a View com o erro no ModelState.)
            ModelState.AddModelError(string.Empty, "Usuário ou senha inválidos.");
            model.Password = string.Empty; // nunca devolva a senha digitada para a tela
            return View(model);
        }

        // GET /Acesso/Logout - encerra a sessão (PHP: session_unset() + session_destroy())
        // Usamos GET para manter o link simples do menu, igual ao PHP. Em produção o ideal é POST + token.
        [HttpGet]
        public IActionResult Logout()
        {
            HttpContext.Session.Clear();                 // apaga tudo que estava na sessão
            return RedirectToAction(nameof(Login));      // volta para o login
        }
    }
}
