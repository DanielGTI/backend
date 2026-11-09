<?php
/**
 * Classe Autenticacao
 *
 * Cuida de tudo que é relacionado a LOGIN:
 *   - entrar()       -> confere usuário e senha
 *   - estaLogado()   -> diz se há alguém logado
 *   - exigirLogin()  -> manda para o login quem não está logado
 *   - nomeUsuario()  -> devolve o nome de quem está logado
 *   - sair()         -> faz o logout
 *
 * Antes, essa lógica ficava espalhada em acesso.php, logout.php, header.php...
 * Agora ela fica em UM só lugar. Se precisar mudar algo, muda aqui.
 */
class Autenticacao
{
    // Constantes: valores que nunca mudam durante a execução.
    // (Num sistema real o usuário viria do banco de dados e a senha seria guardada com password_hash)
    private const USUARIO = 'admin';
    private const SENHA   = '123';

    /**
     * O construtor roda automaticamente quando fazemos "new Autenticacao()".
     * Aqui usamos ele para garantir que a sessão esteja iniciada.
     */
    public function __construct()
    {
        // Só inicia a sessão se ainda não houver uma (evita erro de "session already started")
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Confere usuário e senha. Se estiverem certos, guarda os dados na sessão.
     * Retorna true (login ok) ou false (login inválido).
     */
    public function entrar(string $usuario, string $senha): bool
    {
        // "$this" representa o próprio objeto; "self::" acessa as constantes da classe
        if ($usuario !== self::USUARIO || $senha !== self::SENHA) {
            return false;
        }

        session_regenerate_id(true);            // Gera novo ID de sessão e descarta o antigo: evita session fixation
        $_SESSION['nome_user'] = $usuario;      // Nome de quem logou
        $_SESSION['login']     = true;          // Marca que o login foi feito
        $_SESSION['tipo']      = 'admin';       // Tipo de usuário

        return true;
    }

    /**
     * Retorna true se existe um usuário logado.
     */
    public function estaLogado(): bool
    {
        return isset($_SESSION['login']);
    }

    /**
     * Protege uma página: quem não está logado é enviado para o login.php.
     */
    public function exigirLogin(): void
    {
        if (!$this->estaLogado()) {
            header('Location: login.php');      // Redirecionar para a página de login
            exit();                             // Finalizar o script
        }
    }

    /**
     * Retorna o nome do usuário logado (ou texto vazio se não houver).
     */
    public function nomeUsuario(): string
    {
        return $_SESSION['nome_user'] ?? '';
    }

    /**
     * Faz o logout: apaga todos os dados da sessão.
     */
    public function sair(): void
    {
        session_unset();                        // Remover todas as variáveis de sessão
        session_destroy();                      // Destruir a sessão
    }
}
