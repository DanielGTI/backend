using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;

namespace Csharp_01.Controllers
{
    /// <summary>
    /// Página de erro genérica. É para cá que o Program.cs manda os erros não tratados
    /// quando a aplicação roda fora do ambiente Development (UseExceptionHandler("/Erro")).
    /// Não tem [SessaoObrigatoria] de propósito: o erro pode acontecer com qualquer pessoa.
    /// </summary>
    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public class ErroController : Controller
    {
        public IActionResult Index()
        {
            // O Request ID ajuda a localizar este pedido nos logs do servidor.
            ViewData["RequestId"] = Activity.Current?.Id ?? HttpContext.TraceIdentifier;
            return View();
        }
    }
}
