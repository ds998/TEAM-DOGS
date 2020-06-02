<?php
/**
* pregled svih lobby-a.php – fajl za prikaz pregleda svih lobby-a
* Danilo Stefanovic,2017/0475
* @version 1.0
*/
?>

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
            <div class="left col-sm-4 col-md-4 col-lg-4">
                <div id="searchForm">
                    <form name="searchform" class="form-inline d-flex justify-content-center md-form form-sm mt-0" onkeydown="if(event.keyCode==13) {dosearch();}">
                        <input class="form-control" name="search_textbox" type="text" placeholder="Search">
                    </form>
                </div>
                <div class="menuButton">
                    <a class="btn btn-primary" href="<?php echo site_url("$controller"); ?>" role="button">Menu</a>
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
        /**
        * Asinhrono azuriranje prikaza za lobby-e u bazi
        *
        * @return void
        */
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
        /**
        * Pretraga specificnog lobby-a u prikazu
        *
        * @return void
        */
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

