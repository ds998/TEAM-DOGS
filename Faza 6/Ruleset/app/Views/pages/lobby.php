<html>
    <head>
        <title>Prikljucivanje lobby-u</title>
    <!-- Version: 0.2 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" 
       integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="<?php echo base_url('Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('Base.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('lobby.css'); ?>" />
    </head>
    <body>
        <div class="container-fluid">
                <h3 id="lobby_title"><?php echo $lobby->lobbyName; ?></h3>
                <table id="playerTable" class="table table-hover table-dark">
                    <thead>
                        <tr class="playerTableRowHeader">
                                <th scope="col" class='numRulesCol'>Players</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $s=$lobby->PlayerList;
                            $arr=explode(",",$s);
                            foreach($arr as $a){
                                echo "<tr><td>".$a."</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <?php  
                   if($error!=null){
                       echo "<div id='errors'".$error."</div>";
                   }
                   $user=$_SESSION["user"];
                   if($user->idUser==$lobby->idUser){
                       echo "<div class='gameButton'><a class='btn btn-primary' href=''>Start The Game</a></div>";
                   }
                ?>
                <div class="exitButton">
                    <a class="btn btn-primary" href="<?= site_url("$controller/all_lobbies") ?>" role="button">Exit</a>
                </div>
        </div>
        
        
    </body>

</html>