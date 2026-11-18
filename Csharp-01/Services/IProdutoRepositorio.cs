using Csharp_01.Models;

namespace Csharp_01.Services
{
    /// <summary>
    /// "Contrato" de acesso aos produtos. O Controller só conhece esta interface,
    /// não sabe de onde os dados vêm (memória, SQL Server, API...).
    /// Assim, quando o banco de dados entrar, só criamos outra classe que implementa
    /// esta interface; os Controllers e as Views não mudam.
    /// </summary>
    public interface IProdutoRepositorio
    {
        IReadOnlyList<Produto> ObterTodos();
        Produto? ObterPorId(int id);   // devolve null se o id não existir
    }
}
