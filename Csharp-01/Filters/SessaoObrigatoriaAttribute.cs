using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Filters;

namespace Csharp_01.Filters
{
    /// <summary>
    /// Nomes das "gavetas" que guardamos na sessão.
    /// Usar constantes evita erro de digitação ("nome_user" vs "nomeuser").
    /// </summary>
    public static class SessaoChaves
    {
        public const string NomeUser = "nome_user"; // quem está logado
        public const string Tipo = "tipo";          // perfil do usuário
    }

    /// <summary>
    /// O "GUARDA" do painel: só deixa passar quem já fez login (ou seja, quem tem "nome_user" na sessão).
    ///
    /// Um FILTRO roda ANTES da Action do Controller. Se ninguém estiver logado,
    /// ele já devolve um redirecionamento para o login e a Action nem chega a executar.
    ///
    /// Como usar: coloque [SessaoObrigatoria] em cima de um Controller (protege todas as
    /// Actions dele) ou de uma Action específica.
    ///
    /// Um único atributo cobre as telas (GET) E os formulários (POST) do Controller.
    /// </summary>
    [AttributeUsage(AttributeTargets.Class | AttributeTargets.Method)]
    public class SessaoObrigatoriaAttribute : ActionFilterAttribute
    {
        public override void OnActionExecuting(ActionExecutingContext context)
        {
            // Session.GetString lê a "gaveta" da sessão. Se nunca foi preenchida, devolve null.
            var nomeUser = context.HttpContext.Session.GetString(SessaoChaves.NomeUser);

            if (string.IsNullOrEmpty(nomeUser))
            {
                // Definir um Result "curto-circuita" o pipeline: a Action NÃO será executada
                // e o usuário é redirecionado para a tela de login.
                context.Result = new RedirectToActionResult("Login", "Acesso", null);
            }
        }
    }
}
