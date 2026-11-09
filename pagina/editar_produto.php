<?php
    // Busca o produto pelo id recebido na URL (produto_unitario.php?id=1)
    // O repositório devolve um objeto Produto, ou null se o id não existir.
    $repositorio = new ProdutoRepositorio();
    $id          = (int) ($_GET['id'] ?? 0);
    $produto     = $repositorio->buscar($id);

    // Mensagens de erro enviadas por processar_edicao.php (separadas por "|")
    $erros = [];
    if (!empty($_GET['erros'])) {
        $erros = explode('|', $_GET['erros']);
    }

    // As opções dos <select> ficam como constantes dentro da classe Produto
    $opcoes_ram           = Produto::OPCOES_RAM;
    $opcoes_armazenamento = Produto::OPCOES_ARMAZENAMENTO;
?>
                    <!-- page content -->
                    <div class="col-lg-12 col-md-12 right_col" role="main">
                        <div class="">
                            <div class="page-title row">
                                <div class="col-sm-6 col-12 text-left">
                                    <h3>Editar Produto</h3>
                                </div>

                                <div class="col-sm-6 col-12 text-right">
                                    <div class="row">
                                        <div class="offset-xl-7 col-xl-5 col-lg-12 col-md-12 col-sm-5 col-12 form-group pull-right top_search mt-3">
                                            <a href="produto.php" class="btn btn-secondary">
                                                <i class="fa fa-arrow-left"></i> Voltar para Lista
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>Informações do Produto</h2>
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
                                            <br />

                                            <?php if (!empty($erros)): ?>
                                            <div class="alert alert-danger" role="alert">
                                                <strong>Corrija os campos abaixo:</strong>
                                                <ul class="mb-0">
                                                    <?php foreach ($erros as $erro): ?>
                                                    <li><?= htmlspecialchars($erro) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>

                                            <?php if (!$produto): ?>
                                            <div class="alert alert-warning" role="alert">
                                                Produto não encontrado. <a href="produto.php">Voltar para a lista</a>.
                                            </div>
                                            <?php else: ?>
                                            <form id="editarProdutoForm" method="POST" action="processar_edicao.php" data-parsley-validate class="form-horizontal form-label-left">

                                                <input type="hidden" name="id" value="<?= $id ?>">

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="marca">Marca <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" id="marca" name="marca" required="required" class="form-control" value="<?= htmlspecialchars($produto->getMarca()) ?>">
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="modelo">Modelo <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" id="modelo" name="modelo" required="required" class="form-control" value="<?= htmlspecialchars($produto->getModelo()) ?>">
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="ram">RAM <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select class="form-control" id="ram" name="ram" required="required">
                                                            <option value="">Selecione a RAM</option>
                                                            <?php foreach ($opcoes_ram as $opcao): ?>
                                                            <option value="<?= $opcao ?>"<?= $produto->getRam() === $opcao ? ' selected' : '' ?>><?= $opcao ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="data_lancamento">Data de Lançamento <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="date" id="data_lancamento" name="data_lancamento" required="required" class="form-control" value="<?= htmlspecialchars($produto->getDataLancamento()) ?>">
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="cameras">Câmeras <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" id="cameras" name="cameras" required="required" class="form-control" value="<?= htmlspecialchars($produto->getCameras()) ?>" placeholder="Ex: Tripla: 48MP + 12MP + 12MP">
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="armazenamento">Armazenamento <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select class="form-control" id="armazenamento" name="armazenamento" required="required">
                                                            <option value="">Selecione o Armazenamento</option>
                                                            <?php foreach ($opcoes_armazenamento as $opcao): ?>
                                                            <option value="<?= $opcao ?>"<?= $produto->getArmazenamento() === $opcao ? ' selected' : '' ?>><?= $opcao ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="descricao">Descrição
                                                    </label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <textarea id="descricao" name="descricao" class="form-control" rows="3" placeholder="Descrição opcional do produto"><?= htmlspecialchars($produto->getDescricao()) ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="ln_solid"></div>
                                                <div class="item form-group">
                                                    <div class="col-md-6 col-sm-6 offset-md-3">
                                                        <button class="btn btn-primary" type="button" onclick="window.location.href='produto.php'">Cancelar</button>
                                                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                                    </div>
                                                </div>

                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /page content -->

<!-- Custom script for form validation -->
<script>
    // Executa no "load": jQuery só é carregado no fim da página (config/scripts.php)
    window.addEventListener('load', function() {
        // Parsley é opcional: se a biblioteca não estiver carregada, usa a validação nativa do navegador
        if (!window.jQuery || !jQuery.fn.parsley) { return; }
        $('#editarProdutoForm').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function() {
            return true; // Allow form submission
        });
    });
</script>
