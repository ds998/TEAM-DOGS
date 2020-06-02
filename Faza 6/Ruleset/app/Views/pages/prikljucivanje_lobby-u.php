<?php
/**
* prikljucivanje_lobby-u.php – fajl za prikaz prikljucivanja lobby-u
* Danilo Stefanovic,2017/0475
* @version 1.0
*/
?>

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
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('prikljucivanje_lobby-u/prikljucivanje_lobby-u.css'); ?>" />
    </head>
    <body>
    <div class="header">
        <nav class="navbar bg-dark navbar-dark"> <!-- navbar-expand-lg -->
            <a class="navbar-brand" href="<?php echo site_url("$controller");?>">
                <img id='logoRuleImage' src="<?php echo base_url('assets/navbar/rule_icon.png'); ?>" alt="Logo" class='logoImage'>
                <img id='logoSetImage' src="<?php echo base_url('assets/navbar/set_icon.png'); ?>" alt="Logo" class='logoImage'>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarElements"
                aria-controls="navbarElements" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarElements">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                    <?php
                        if ($_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/login_page")."'>Login</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/register")."'>Register</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/register")."'>Saved Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url("$controller/registerAdmin")."'>Choose Admin</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url("$controller/changeHD")."'>Choose Highlighted Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/logout")."'>Logout</a>";
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
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
                <div class="deckButton">
                    <a class="btn btn-primary" href="" role="button">Examine Deck</a>
                </div>
                <?php 
                   if(!empty($error)){
                       echo "<div id='errors'>".$error."</div>";
                   }
                ?>
                <div class="joinButton">
                    <a class="btn btn-primary" href="<?= site_url("$controller/joining_lobby/{$lobby->idLobby}") ?>" role="button">Join</a>
                </div>
                <div class="exitButton">
                    <a class="btn btn-primary" href="<?= site_url("$controller/all_lobbies") ?>" role="button">Exit</a>
                </div>
            </div>
        </div>
        </div>
        <script>
            setInterval(update_lobby,10000);
            /**
            * Asinhrono azuriranje prikaza za igrace u lobby-u
            *
            * @return void
            */
            function update_lobby(){
                var controller="<?php echo $controller; ?>";
                myTest().then((data)=>{
                    if(data=="Nothing!"){
                        location.href="http://localhost:8080/"+controller+"/all_lobbies";
                    }
                    else{
                        var players=data.split(",");
                        var new_s="";
                        for(var i=0;i<players.length;i++){
                            new_s+="<tr><td>"+players[i]+"</td></tr>";
                        }
                        console.log(new_s);
                        document.getElementById("playerTable").getElementsByTagName("tbody")[0].innerHTML=new_s;
                    }
                });
            }
            const myTest = async () => {
                var controller="<?php echo $controller; ?>";
                var idLobby="<?php echo $lobby->idLobby; ?>";
                var response = await fetch("http://localhost:8080/"+controller+"/update_lobby/"+idLobby, {
                    headers:{'Accept': 'application/json'},
                    method: "GET",
                    mode: "cors"
                });
                var returnData = await response.json();
                return returnData;
            }
        </script>
    </body>

</html>