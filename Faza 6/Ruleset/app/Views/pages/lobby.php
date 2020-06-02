<?php
/**
* lobby.php – fajl za prikaz lobby-a
* Danilo Stefanovic,2017/0475
* @version 1.0
*/
?>

<html>
    <head>
        <title>Lobby</title>
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
    <link rel="stylesheet" href="<?php echo base_url('lobby/lobby.css'); ?>" />
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
                       echo "<div id='errors'>".$error."</div>";
                   }
                   $user=$_SESSION["user"];
                   if($user->idUser==$lobby->idUser){
                       echo "<div class='gameButton'><a class='btn btn-primary' href='".base_url("$controller/game/{$lobby->idLobby}")."'>Start The Game</a></div>";
                   }
                ?>
                <div class="exitButton">
                    <a class="btn btn-primary" href="<?php echo site_url("$controller/exit_lobby/{$lobby->idLobby}"); ?>" role="button">Exit</a>
                </div>
        </div>
        
        
    </body>
    <script>
            setInterval(update_lobby,1000);
            setInterval(update_game,1200);
             /**
             * Asinhrono azuriranje prikaza za igrace u lobby-u
             *
             * @return void
             */
            function update_lobby(){
                var controller="<?php echo $controller; ?>";
                myTest().then((data)=>{
                    if(data=="Nothing!"){
                        location.href="http://localhost:8080/"+controller+"all_lobbies";
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
            /**
             * Asinhrono azuriranje statusa igre za igrace u lobby-u
             *
             * @return void
             */
            function update_game(){
                var controller="<?php echo $controller; ?>";
                var idLobby="<?php echo $lobby->idLobby; ?>";
                myT().then((data)=>{
                    if(data=="yes"){
                        location.href="http://localhost:8080/"+controller+"/game/"+idLobby;
                    }
                });
            }
            const myT = async () => {
                var controller="<?php echo $controller; ?>";
                var idLobby="<?php echo $lobby->idLobby; ?>";
                var response = await fetch("http://localhost:8080/"+controller+"/update_game/"+idLobby, {
                    headers:{'Accept': 'application/json'},
                    method: "GET",
                    mode: "cors"
                });
                var returnData = await response.json();
                return returnData;
            }
    </script>

</html>