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
    <link rel="stylesheet" href="<?php echo base_url('prikljucivanje_lobby-u.css'); ?>" />
    </head>
    <body>
        <div class="container-fluid">
        <div class="row">
            <div class="left col-sm-6 col-md-6 col-lg-6">
                <h3 id="lobby_title"><?php echo $lobby->lobbyName; ?></h3>
                <p id="lobby_status">
                   <?php 
                      if($lobby->status==1) echo 'Public'; 
                      else echo 'Private';
                   ?>
                </p>
                <p id="lobby_in_game">
                   <?php 
                      if($lobby->inGame==1) echo 'Game: In progress'; 
                      else echo 'Game: Waiting';
                   ?>
                </p>
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
                <p id="lobby_max">Maximum number of players: <?php echo $lobby->maxPlayers;?></p>
            </div>
            <div class="right col-sm-6 col-md-6 col-lg-6">
                <?php  
                   if(!empty($errors)){
                       echo "<div id='errors'".$errors."</div>";
                   }
                ?>
                <div class="joinButton">
                    <a class="btn btn-primary" href="<?= site_url("$controller/joining_lobby/{$lobby->idDeck}") ?>" role="button">Join</a>
                </div>
                <div class="exitButton">
                    <a class="btn btn-primary" href="<?= site_url("$controller/all_lobbies") ?>" role="button">Exit</a>
                </div>
            </div>
        </div>
        </div>
        
        
    </body>

</html>