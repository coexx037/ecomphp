<?php 

require_once("resources/functions.php");


?>

<?php include(TEMPLATE_FRONT.DS."header.php") ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- side nav here -->
            <?php include(TEMPLATE_FRONT.DS."side_nav.php") ?>

            <div class="col-md-9">

                <div class="row">
                    
                    <?php get_products(); ?>

                </div><!-- row ends here -->

            </div>

        </div>

    </div>
    <!-- /.container -->

    
<?php include(TEMPLATE_FRONT.DS."footer.php") ?>