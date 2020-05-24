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
                <table id="lobby_table" class="table table-striped table-bordered table-sm">
                    <thead>
                    <tr><th class="th-sm">Current players</th></tr>
                    </thead>
                    <?php 
                        $s=$lobby->PlayerList;
                        $arr=$s->str_split(",");
                        foreach($arr as $a){
                            echo "<tr><td>".$a."<td><tr>";
                        }
                    ?>

                </table>
                <p id="lobby_max">Maximum number of players: <?php echo $lobby->maxPlayers;?></p>
            </div>
            <div class="right col-sm-6 col-md-6 col-lg-6">
                <div id="errors"><?php
                   if(!empty($error)){
                       echo "<p font='red'>".$error."</p>";
                   }
                ?></div>
                <div class="joinButton">
                    <a class="btn btn-secondary" href="<?= site_url("$controller/joining_lobby/{$deck_id}") ?>" role="button">Join</a>
                </div>
                <div class="exitButton">
                    <a class="btn btn-secondary" href="<?= site_url("$controller/all_lobbies") ?>" role="button">Exit</a>
                </div>
            </div>
        </div>
        </div>
        
        
    </body>
    <style>
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:before {
       bottom: .5em;
    }
    .left{
        text-align:center;
    }
    .joinButton{
        float:left;
    }
    .exitbutton{
        float:right;
    }
    #lobby_table{
        text-align:center;
        margin-left:auto;
        margin-right:auto;
    }

    </style>
    <script>
       $(document).ready(function(){
           setInterval(getStuff,1000);

           function getStuff(){
               var id="<?php echo $lobby->idLobby; ?>";
               id=JSON.stringify({'idLobby:':id});
               $.ajax({
                   contentType:'application/json;charset=utf-8',
                   dataType:'text',
                   type:'GET',
                   url:'/help/UpdateLobbyPage',
                   data:id,
                   success:function(data){
                        var arr=data.split(",");
                        $("#lobby_in_game").html(arr[0]);
                        var new_str="";
                        for(int i=1;i<arr.length;i++){
                            new_str+="<tr><td>"+arr[i]+"<td><tr>";
                        }
                        $("#lobby_table").html(new_str);

                   }
               });
           }
       }
       );
    </script>

</html>