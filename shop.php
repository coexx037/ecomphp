<?php 

require_once("resources/functions.php");


?>

<?php include(TEMPLATE_FRONT.DS."header.php") ?>

    <!-- Page Content -->
    <div class="container">
        
        <header>
            <h1>Shop</h1>
        </header>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Products</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

            <?php
                get_products_to_shop();
            ?>


        </div>
        <!-- /.row -->


    </div>
    <!-- /.container -->

<?php include(TEMPLATE_FRONT.DS."footer.php") ?>