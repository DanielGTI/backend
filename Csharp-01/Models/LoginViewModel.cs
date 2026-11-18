namespace Csharp_01.Models
{
    /// <summary>
    /// Os dados que chegam do formulário de login.
    /// "Model Binding": o ASP.NET lê os campos do formulário (name="Username", name="Password")
    /// e preenche estas propriedades automaticamente.
    /// (PHP: $_POST['username'] e $_POST['password'])
    ///
    /// Valores padrão "" evitam o equivalente ao aviso "Undefined index" do PHP:
    /// se o campo não vier no pedido, a propriedade fica vazia em vez de null.
    /// </summary>
    public class LoginViewModel
    {
        public string Username { get; set; } = string.Empty;
        public string Password { get; set; } = string.Empty;
    }
}
