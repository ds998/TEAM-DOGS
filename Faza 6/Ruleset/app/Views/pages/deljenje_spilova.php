<?php
/**
* deljenje_spilova.php – fajl za prikaz deljenja spilova
* Danilo Stefanovic,2017/0475
* @version 1.0
*/
?>

<html>
    <head>
    <title>Deljenje spilova</title>
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
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('deljenje_spilova/deljenje_spilova.css'); ?>">
    </head>
    <body>
        <div class="container-fluid">
            <form name="shareform" action="<?= site_url("$controller/share_deck_submit/{$deck_id}") ?>">
                <input type="text" class="form-control" name="share_textbox"  placeholder="Name of the user">
                <?php 
                    if(!empty($message)) 
                        echo "<div id='errors'>".$message."</div>";
                ?>
                <div class="share_button"><input type="submit" name="share_submit" class="btn btn-primary" value="Share Request"></div>
            </form>
        </div>
    </body>
</html