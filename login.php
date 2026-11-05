<?php
    session_start();                            // Iniciar a sessão para saber se o usuário já está logado
    if (isset($_SESSION['login'])) {            // Usuário já autenticado: não precisa ver o formulário de login
        header('Location: index.php');          // Redirecionar para a página protegida
        exit();                                 // Finalizar o script
    }
    require_once 'config/header.php';           // header.php já imprime <!DOCTYPE>, <html> e <head>
?>
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form name="form_login" action="acesso.php" method="POST">
              <h1>Login PHP</h1>
              <?php if (isset($_GET['erro'])): ?>
              <div class="alert alert-danger" role="alert">Usuário ou senha inválidos.</div>
              <?php endif; ?>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <input type="submit" class="btn btn-default submit" value="Entrar"/>
                <a class="reset_pass" href="#">Esqueceu a sua senha?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Novo no site?
                  <a href="#signup" class="to_register"> Criar Conta </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Gentelella PHP!</h1>
                  <p>©2025 All Rights Reserved. Gentelella PHP</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form>
              <h1>Criar Conta</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="index.html">Enviar</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Já é membro ?
                  <a href="#signin" class="to_register"> Entrar </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Gentelella PHP!</h1>
                  <p>©2025 All Rights Reserved. Gentelella PHP</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>