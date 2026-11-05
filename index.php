<?php 
    require_once 'config/header.php';
?>

<body class="nav-md">
<div class="body">
    <div class="main_container container-fluid">
        <div class="row">
            <?php 
                require_once 'config/sidebar.php';
            ?>
            <div class="col-lg-10 col-md-12 right_col_wrapper">
                <div class="row">
                    <?php 
                        require_once 'config/top.php';
                    ?>
                    <?php 
                        require_once 'pagina/home.php';
                        require_once 'config/footer.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    require_once 'config/scripts.php';
?>
</body>
</html>
