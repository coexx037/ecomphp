<?php 

require_once("resources/functions.php");


?>

<?php include(TEMPLATE_FRONT.DS."header.php") ?>

    <!-- Page Content -->
    <div class="container">
        
        

      <header>
            <h1 class="text-center">Login</h1>
        <div class="col-sm-4 col-sm-offset-5"> 
        
        <?php login_user(); ?>
        
            <form class="" action="" method="post">
                
                <?php display_message(); ?>
                
                <div class="form-group"><label for="">
                    username<input type="text" name="username" class="form-control"></label>
                </div>
                 <div class="form-group"><label for="password">
                    Password<input type="password" name="password" class="form-control"></label>
                </div>

                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-primary" >
                </div>
            </form>
        </div>  


    </header>


        </div>

    
<?php include(TEMPLATE_FRONT.DS."footer.php") ?>
