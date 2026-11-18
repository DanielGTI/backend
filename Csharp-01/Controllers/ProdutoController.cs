using Csharp_01.Filters;
using Csharp_01.Models;
using Csharp_01.Services;
using Microsoft.AspNetCore.Mvc;

namespace Csharp_01.Controllers
{
    /// <summary>
    /// Lista e edição de produtos. Tem três Actions:
    ///   Index         mostra a tabela de produtos
    ///   Editar (GET)  mostra o formulário preenchido
    ///   Editar (POST) valida os dados e redireciona
    /// </summary>
    [SessaoObrigatoria]
    public class ProdutoController : Controller
    {
        private readonly IProdutoRepositorio _repositorio;

        // INJEÇÃO DE DEPENDÊNCIA: o ASP.NET entrega o repositório pronto (registrado no Program.cs).
        public ProdutoController(IProdutoRepositorio repositorio)
        {
            _repositorio = repositorio;
        }

        // GET /Produto - tabela com todos os produtos
        public IActionResult Index()
        {
            // Passamos a lista para a View (Views/Produto/Index.cshtml) como Model.
            return View(_repositorio.ObterTodos());
        }

        // GET /Produto/Editar/2 - formulário preenchido com o produto de id 2
        // O "2" da URL vira o parâmetro "id" (rota padrão: {controller}/{action}/{id?}).
        // O ASP.NET converte o texto da URL para int sozinho (Model Binding).
        [HttpGet]
        public IActionResult Editar(int id)
        {
            var produto = _repositorio.ObterPorId(id);

            // Se o id não existir, produto é null e a View mostra "Produto não encontrado".
            return View(produto);
        }

        // POST /Produto/Editar - recebe o formulário, valida e redireciona
        // Este é o padrão POST / REDIRECT / GET (veja o README).
        [HttpPost]
        [ValidateAntiForgeryToken]
        public IActionResult Editar(Produto produto)
        {
            // MODEL BINDING: o ASP.NET montou o objeto "produto" com os campos do formulário
            // e já rodou as validações dos atributos ([Required]...). O resultado está em ModelState.

            if (_repositorio.ObterPorId(produto.Id) is null)
            {
                // id inexistente: volta para o GET, que mostra "Produto não encontrado"
                return RedirectToAction(nameof(Editar), new { id = produto.Id });
            }

            if (!ModelState.IsValid)
            {
                // COM ERROS: devolve a MESMA tela, já com os erros e com o que o usuário digitou
                // (o usuário não perde o que escreveu).
                return View(produto);
            }

            // SEM ERROS: aqui entraria o UPDATE no banco de dados.
            // Por enquanto é só uma simulação: nada é gravado.

            // TempData guarda a mensagem para a PRÓXIMA requisição e depois some sozinho.
            // (A mensagem não aparece na URL, diferente de passá-la por query string.)
            TempData["Mensagem"] = "Produto atualizado com sucesso!";

            // Redireciona em vez de mostrar a resposta direto: se o usuário apertar F5,
            // o navegador só recarrega a lista e NÃO reenvia o formulário.
            return RedirectToAction(nameof(Index));
        }
    }
}
