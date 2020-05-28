<html>
    <head>
    <title>Pregled svih lobby-a</title>
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
    <link rel="stylesheet" href="<?php echo base_url('pregled_svih_lobby-a/pregled_svih_lobby-a.css'); ?>" />
    </head>
    <body>
        <div class="container-fluid">
        <div class="row">
            <div class="left col-sm-4 col-md-4 col-lg-4">
                <div id="searchForm">
                    <form name="searchform" class="form-inline d-flex justify-content-center md-form form-sm mt-0" onkeydown="if(event.keyCode==13) {dosearch();}">
                        <input class="form-control" name="search_textbox" type="text" placeholder="Search">
                    </form>
                </div>
                <div class="menuButton">
                    <a class="btn btn-primary" href="<?php echo base_url('Main.html'); ?>" role="button">Menu</a>
                </div>
            </div>
            <div class="right col-sm-8 col-md-8 col-lg-8">
                <?php 
                    foreach ($lobbies as $lobby) {
                        echo "<div class='lobby_button' id='{$lobby->lobbyName}'><a class='btn btn-primary' href='".site_url("$controller/join_lobby/{$lobby->idLobby}")."'>{$lobby->lobbyName}</a></div>";
                    }
                ?>
            </div>
        </div>
        </div>
        
    </body>
    <script>
        var myVar=setInterval(update_lobbies,10000);
        function update_lobbies(){
            myTest().then((data)=>{
                var controller="<?php echo $controller; ?>";
                var s="";
                for(var i=0;i<data.length;i++){
                    s+="<div class='lobby_button' id='"+data[i]["lobbyName"]+"'><a class='btn btn-primary' href='localhost:8080/"+controller+"/join_lobby/"+data[i]["idLobby"]+"'>"+data[i]["lobbyName"]+"</a></div>";
                }
                document.getElementsByClassName("right")[0].innerHTML=s;
            });
        }
        const myTest = async () => {
            var controller="<?php echo $controller; ?>";
            var response = await fetch("http://localhost:8080/"+controller+"/update_lobbies", {
                headers:{'Accept': 'application/json'},
                method: "GET",
                mode: "cors"
            });
            var returnData = await response.json();
            return returnData;
        }
        function dosearch(){
            var sf=document.searchform;
            var submitted=sf.search_textbox.value;
            var item=document.getElementById(submitted);
            if(item!=null) {
                location.href="#";
                location.href="#"+submitted;
            }
            return;
        }
        </script>

</html>

