<?php 

include('Navbar.php');
?>

<html>
    <head>
        <title>Deljenje spilova</title>
    <!-- Version: 0.2 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    </head>
    <body>
        <div class="container-fluid">
            <div id="share_form">
                <form name="shareform" class="form-inline d-flex justify-content-center md-form form-sm mt-0" action="<?= site_url("$controller/share_deck_submit/{$deck_id}") ?>">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input class="form-control form-control-sm ml-3 w-75" name="share_textbox" type="text" placeholder="Name of the user">
                    <?php 
                    if(!empty($message)) 
                        echo "<font color='red'>".$message."</font><br?";
                    ?>
                    <div class="share_button"><input type="submit" name="share_submit" class="btn btn-primary" value="Share Request"></div>
                </form>
                
            </div>
        </div>
        <style>
            .container-fluid{
                height:50%;
                text-align:center;
                
            }
            #share_form{
                width:30%;
                margin-top:20%;
                height:70%;
                margin-left:auto;
                margin-right:auto;
            }
            .share_button{
                padding-top:20px;
            }
        </style>
        
        
    </body>