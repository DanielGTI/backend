<?php 
    require_once 'header.php';
?>

<body class="nav-md">
<div class="body">
    <div class="main_container container-fluid">
        <div class="row">
            <?php 
                require_once 'sidebar.php';
            ?>
            <div class="col-lg-10 col-md-12 right_col_wrapper">
                <div class="row">
                    <?php 
                        require_once 'top.php';
                    ?>
                    <?php 
                        require_once 'content.php';
                        require_once 'footer.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    require_once 'scripts.php';
?>
</body>
</html>
