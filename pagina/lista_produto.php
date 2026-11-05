                    <!-- page content -->
                    <div class="col-lg-12 col-md-12 right_col" role="main">
                        <div class="">
                            <div class="page-title row">
                                <div class="col-sm-6 col-12 text-left">
                                    <h3>Produtos</h3>
                                </div>

                                <div class="col-sm-6 col-12 text-right">
                                    <div class="row">
                                        <div class="offset-xl-7 col-xl-5 col-lg-12 col-md-12 col-sm-5 col-12 form-group pull-right top_search mt-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search for...">
                                                <span class="input-group-btn">
                      <button class="btn btn-light" type="button">Go!</button>
                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="row">
                            
                <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Lista de produtos</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <?php if (!empty($_GET['mensagem'])): ?>
                    <div class="alert alert-success" role="alert"><?= htmlspecialchars($_GET['mensagem']) ?></div>
                    <?php endif; ?>

                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Marca</th>
                          <th>Modelo</th>
                          <th>RAM</th>
                          <th>Ações</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Apple</td>
                          <td>iPhone 17</td>
                          <td>32GB</td>
                          <td>
                            <a href="produto_unitario.php?id=1" class="btn btn-info btn-sm" title="Editar">
                              <i class="fa fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                              <i class="fa fa-trash"></i> Excluir
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">2</th>
                          <td>Samsung</td>
                          <td>Galaxy S25</td>
                          <td>8GB</td>
                          <td>
                            <a href="produto_unitario.php?id=2" class="btn btn-info btn-sm" title="Editar">
                              <i class="fa fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                              <i class="fa fa-trash"></i> Excluir
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">3</th>
                          <td>Motorola</td>
                          <td>G10</td>
                          <td>8GB</td>
                          <td>
                            <a href="produto_unitario.php?id=3" class="btn btn-info btn-sm" title="Editar">
                              <i class="fa fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                              <i class="fa fa-trash"></i> Excluir
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>

                            </div>
                        </div>
                    </div>
                    <!-- /page content -->