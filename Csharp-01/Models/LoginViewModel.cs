namespace Csharp_01.Models
{
    /// <summary>
    /// Os dados que chegam do formulário de login.
    /// "Model Binding": o ASP.NET lê os campos do formulário (name="Username", name="Password")
    /// e preenche estas propriedades automaticamente.
    ///
    /// Valores padrão "" evitam null: se o campo não vier no pedido, a propriedade fica vazia.
    /// </summary>
    public class LoginViewModel
    {
        public string Username { get; set; } = string.Empty;
        public string Password { get; set; } = string.Empty;
    }
}
