using Csharp_01.Filters;
using Microsoft.AspNetCore.Mvc;

namespace Csharp_01.Controllers
{
    /// <summary>
    /// Dashboard (tela inicial). Equivale ao index.php + pagina/home.php do PHP.
    ///
    /// [SessaoObrigatoria] em cima da classe = TODAS as Actions daqui exigem login.
    /// </summary>
    [SessaoObrigatoria]
    public class HomeController : Controller
    {
        // GET /  (rota padrão: Home/Index)
        // Sem lógica por enquanto: o MVC procura a view Views/Home/Index.cshtml e a devolve.
        public IActionResult Index()
        {
            return View();
        }
    }
}
