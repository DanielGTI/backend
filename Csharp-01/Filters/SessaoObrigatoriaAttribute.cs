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
        public const string NomeUser = "nome_user"; // quem está logado   (PHP: $_SESSION['nome_user'])
        public const string Tipo = "tipo";          // perfil do usuário  (PHP: $_SESSION['tipo'])
    }

    /// <summary>
    /// O "GUARDA" do painel. Equivale ao bloco que ficava no topo do config/header.php:
    ///
    ///     if (!isset($_SESSION['nome_user']) && ...) { header('Location: login.php'); exit(); }
    ///
    /// Um FILTRO roda ANTES da Action do Controller. Se ninguém estiver logado,
    /// ele já devolve um redirecionamento para o login e a Action nem chega a executar.
    ///
    /// Como usar: coloque [SessaoObrigatoria] em cima de um Controller (protege todas as
    /// Actions dele) ou de uma Action específica.
    ///
    /// Vantagem sobre o PHP: na versão PHP, o processar_edicao.php NÃO passava pelo header.php
    /// e precisou ganhar um guarda próprio. Aqui, o mesmo atributo cobre telas E formulários (POST).
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
                // Definir um Result "curto-circuita" o pipeline: a Action NÃO será executada.
                // (PHP: header('Location: login.php'); exit();)
                context.Result = new RedirectToActionResult("Login", "Acesso", null);
            }
        }
    }
}
