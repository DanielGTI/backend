using System.ComponentModel.DataAnnotations;

namespace Csharp_01.Models
{
    /// <summary>
    /// MODEL de produto (o "M" do MVC): descreve os dados e as REGRAS de validação.
    ///
    /// A validação é feita com DATA ANNOTATIONS: atributos entre colchetes que declaram
    /// a regra em cima da propriedade. O ASP.NET aplica tudo sozinho
    /// e preenche o ModelState com os erros.
    /// </summary>
    public class Produto
    {
        // Listas usadas nos <select> da tela de edição
        public static readonly string[] OpcoesRam = { "2GB", "4GB", "6GB", "8GB", "12GB", "16GB", "32GB" };
        public static readonly string[] OpcoesArmazenamento = { "32GB", "64GB", "128GB", "256GB", "512GB", "1TB" };

        public int Id { get; set; }

        [Required(ErrorMessage = "Marca é obrigatória")]
        public string Marca { get; set; } = string.Empty;

        [Required(ErrorMessage = "Modelo é obrigatório")]
        public string Modelo { get; set; } = string.Empty;

        [Required(ErrorMessage = "RAM é obrigatória")]
        public string Ram { get; set; } = string.Empty;

        // DateTime? (com "?") aceita vazio. Assim o [Required] mostra a NOSSA mensagem,
        // em vez de um erro genérico de conversão.
        // [DataType(Date)] faz o <input asp-for> virar type="date";
        // o DisplayFormat garante o formato yyyy-MM-dd que o input de data exige.
        [Required(ErrorMessage = "Data de lançamento é obrigatória")]
        [DataType(DataType.Date)]
        [DisplayFormat(DataFormatString = "{0:yyyy-MM-dd}", ApplyFormatInEditMode = true)]
        public DateTime? DataLancamento { get; set; }

        [Required(ErrorMessage = "Câmeras são obrigatórias")]
        public string Cameras { get; set; } = string.Empty;

        [Required(ErrorMessage = "Armazenamento é obrigatório")]
        public string Armazenamento { get; set; } = string.Empty;

        // Descrição é OPCIONAL, por isso "string?".
        // ATENÇÃO: com <Nullable>enable</Nullable> no .csproj, uma "string" sem "?" é tratada
        // como obrigatória automaticamente. O "?" é o que diz "pode ficar vazio".
        public string? Descricao { get; set; }
    }
}
