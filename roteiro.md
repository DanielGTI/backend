# Roteiro de evolução da aula: PHP e POO

Este roteiro sugere os próximos passos para continuar a aula, partindo do estado atual do projeto: login, lista e edição de produtos com as classes `Autenticacao`, `Produto` e `ProdutoRepositorio`.

Os conceitos já vistos estão no [README.md](README.md).

---

## Sequência sugerida

Cada passo se apoia no anterior.

| # | Tema | Por que encaixa | Já preparado no projeto |
|---|---|---|---|
| 1 | **Banco de dados com PDO e MySQL** | É o próximo passo natural. Só o `ProdutoRepositorio` muda, e as telas continuam iguais. Isso mostra na prática o valor da POO. | O comentário "aqui você faria a conexão com o banco" em `processar_edicao.php` e o comentário do repositório. |
| 2 | **CRUD completo** (criar, editar, excluir) | Hoje o "Salvar" só simula e o botão "Excluir" não faz nada. Fecha o ciclo e dá resultado visível. | Os botões já existem na tela. |
| 3 | **Segurança básica** | É o que mais falta para iniciantes. Cobre `password_hash` e `password_verify` no lugar da senha fixa `123`, *prepared statements* e SQL injection (com demonstração), e token CSRF no formulário. | `htmlspecialchars` (contra XSS) já é usado nas telas. |
| 4 | **Exceções (`try/catch`)** | Entram junto com o banco, para tratar falha de conexão e erro ao salvar. | Nada ainda. |
| 5 | **Interfaces e polimorfismo** | Crie um `RepositorioInterface` com duas implementações, uma em array e outra em banco. Os alunos trocam uma pela outra sem mexer nas telas. | O `ProdutoRepositorio` atual vira a versão "array". |
| 6 | **Herança e classes abstratas** | Exemplo: `Pessoa`, com `Cliente` e `Usuario` herdando dela. Use as telas de "Cliente" que já estão no menu lateral. | O menu já tem "Cliente" e "Vendas" vazios. |
| 7 | **Injeção de dependência** | Em vez de a classe dar `new` em outra, ela recebe o objeto pelo construtor. Deixa o código testável e prepara o MVC. | `Autenticacao` e `ProdutoRepositorio` são bons candidatos. |
| 8 | **Namespaces e Composer (PSR-4)** | Substitui o `autoload.php` manual pelo padrão do mercado. | O autoload atual é a versão simples da mesma ideia. |
| 9 | **Controller e MVC simples** | Tira a lógica de dentro dos arquivos de página e reduz a repetição de layout (`index.php`, `produto.php`, `produto_unitario.php`). | A estrutura repetida hoje nos três arquivos. |
| 10 | **Testes com PHPUnit** | Comece pelo `Produto::validar()`, que é fácil de testar. | Mostra por que classes pequenas são boas. |

---

## Recomendação: próxima aula

Faça os passos **1, 2 e 3** como a próxima aula: **banco, CRUD e segurança**.

Com eles o projeto vira uma aplicação real, e os alunos veem o resultado de tudo que aprenderam. Depois disso, entram interfaces e herança.

### Sugestão de divisão

| Aula | Conteúdo | Resultado visível |
|---|---|---|
| A | MySQL no `docker-compose` e PDO no `ProdutoRepositorio` | A lista passa a vir do banco |
| B | Inserir, atualizar e excluir produtos, com exceções | Os botões "Salvar" e "Excluir" funcionam de verdade |
| C | `password_hash`, prepared statements e CSRF | Login seguro e demonstração de SQL injection |

---

## Recursos do PHP 8 para encaixar no meio

- `declare(strict_types=1)`
- Construtor com propriedades promovidas
- `readonly`
- `enum`, por exemplo para as opções de RAM no lugar das constantes
- `static`, que ainda não está no README

O código atual evita esses recursos de propósito, para rodar também no PHP 7.4 local. Se a turma usar só o Docker (PHP 8.3), vale incluí-los depois que o básico estiver firme.

---

## Melhorias de método

- **Exercícios com gabarito em branches.** O projeto já usa uma branch por fase (`php-01`, `php-02`...). Dá para criar uma `php-04-gabarito` com as soluções dos exercícios do README.
- **Um mini-projeto no fim.** Cada aluno cria o próprio módulo (Cliente, Venda ou outro), seguindo o padrão de `Produto`.
- **Um diagrama UML simples.** Um desenho de caixas e setas com as 3 classes ajuda quem aprende pelo visual.

---

## Pontos do projeto atual que podem virar aula

Itens que hoje são simulados ou incompletos e que servem como gancho para os passos acima:

- O botão **Excluir** não faz nada (passo 2).
- O **Salvar** só simula a atualização e redireciona (passos 1 e 2).
- O **login** usa usuário e senha fixos, `admin` e `123` (passo 3).
- O formulário **"Criar Conta"** em `login.php` é só visual (passos 2 e 3).
- As **mensagens de sucesso e erro** viajam pela URL (`?mensagem=` e `?erros=`). Pode-se trocar por mensagens guardadas na sessão (passo 9).
- As três **páginas-casca** repetem a mesma estrutura de layout (passo 9).
