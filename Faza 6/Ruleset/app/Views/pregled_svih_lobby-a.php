<?php 

include('Navbar.php');
?>

<html>
    <head>
        <title>Pregled svih lobby-a</title>
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
        <div class="row">
            <div class="left col-sm-4 col-md-4 col-lg-4">
                <div id="searchForm">
                    <form name="searchform" class="form-inline d-flex justify-content-center md-form form-sm mt-0" onSubmit="dosearch();">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input class="form-control form-control-sm ml-3 w-75" name="search_textbox" type="text" placeholder="Search" aria-label="Search">
                        <div class="search_button"><input type="submit" name="search_submit" class="btn btn-primary" value="Send Request"></div>
                    </form>
                </div>
                <div class="menuButton">
                    <a class="btn btn-secondary" href="#" role="button">Menu</a>
                </div>
            </div>
            <div class="right col-sm-8 col-md-8 col-lg-8">
                <table id="lobby_table">
                    <?php 
                        foreach ($lobbies as $lobby) {
                             echo "<tr><td id='{$lobby->lobbyName}' onclick='join_lobby(this);'>{$lobby->lobbyName}</td></tr>";
                        }
                    ?>

                </table>
            </div>
        </div>
        </div>
        
        
    </body>
    <style>
        .row{
            height:90%;
        }
        .left{
            text-align:center;
        }
        .right{
            overflow-x:hidden;
            overflow-y:auto;
            height:100%;
        }
        #searchForm{
            padding-top:30%;
        }
        .search_button{
            padding-top:15px;
        }
        .menuButton{
            padding-top:80%;
        }
        #lobby_table{
            text-align:center;
            margin-left:auto;
            margin-right:auto;
            width:50%;
            height:100%;
            overflow:hidden;
        }
        #lobby_table td{
            border-bottom:150px solid #fff;
            line-height:100px;
            overflow:hidden;
            background-color:gray;
        }
        #lobby_table td:first-child{
            border-top:150px solid #fff;
        }
    </style>
    <script>
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
       function join_lobby(tcell){
           
       }
    </script>

</html>

