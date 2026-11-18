using Csharp_01.Models;

namespace Csharp_01.Services
{
    /// <summary>
    /// Repositório EM MEMÓRIA, com 3 produtos de exemplo (iPhone 17, Galaxy S25 e Moto G10).
    /// Ainda não existe banco de dados: a lista abaixo é recriada toda vez que a aplicação inicia.
    /// </summary>
    public class ProdutoRepositorio : IProdutoRepositorio
    {
        private static readonly List<Produto> _produtos = new()
        {
            new Produto
            {
                Id = 1, Marca = "Apple", Modelo = "iPhone 17", Ram = "32GB",
                DataLancamento = new DateTime(2025, 1, 15),
                Cameras = "Tripla: 48MP + 12MP + 12MP", Armazenamento = "128GB",
                Descricao = "iPhone 17 com processador A19 Pro, tela Super Retina XDR de 6.7 polegadas e bateria de longa duração."
            },
            new Produto
            {
                Id = 2, Marca = "Samsung", Modelo = "Galaxy S25", Ram = "8GB",
                DataLancamento = new DateTime(2025, 2, 7),
                Cameras = "Tripla: 50MP + 12MP + 10MP", Armazenamento = "256GB",
                Descricao = "Galaxy S25 com tela Dynamic AMOLED 2X de 6.2 polegadas."
            },
            new Produto
            {
                Id = 3, Marca = "Motorola", Modelo = "G10", Ram = "8GB",
                DataLancamento = new DateTime(2021, 2, 1),
                Cameras = "Quádrupla: 48MP + 8MP + 2MP + 2MP", Armazenamento = "64GB",
                Descricao = "Moto G10 com tela de 6.5 polegadas e bateria de 5000 mAh."
            }
        };

        public IReadOnlyList<Produto> ObterTodos() => _produtos;

        // FirstOrDefault (LINQ) devolve null quando nenhum produto tem o id pedido
        public Produto? ObterPorId(int id) => _produtos.FirstOrDefault(p => p.Id == id);
    }
}
