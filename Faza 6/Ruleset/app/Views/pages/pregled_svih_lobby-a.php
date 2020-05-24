<html>
    <head>
        <title>Pregled svih lobby-a</title>
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
                <?php 
                    foreach ($lobbies as $lobby) {
                        echo "<div class='lobby_div' id='{$lobby->lobbyName}'>{$lobby->lobbyName}</div>";
                    }
                ?>
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
            overflow-y:scroll;
            height:100%;
            display:flex;
            flex-direction:column;
            align-items:center;
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
        .lobby_div{
            line-height:100px;
            text-align:center;
            margin-top:100px;
            height:100px;
            width:250px;
            background-color:gray;
        }
        .lobby_div:hover{
            cursor:pointer;
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
       $(document).ready(function(){
           $(".lobby_div").click(function(){
                var id=$(this).id;
                var controller="<?php echo $controller; ?>";
                var stuff=JSON.stringify({'lobbyName':id,'controller':controller});
                jQuery.support.cors = true;
                request=$.ajax({
                    contentType:'application/json;charset=utf-8',
                    dataType:'text',
                    type:'GET',
                    url:"help/RedirectLobbyPage.php",
                    data:stuff                   
                });
                request.done(function(data){
                    location.href=data;
                    return;
                });
                request.fail(function(xhr, status, errorThrown){
                    alert( "Sorry, there was a problem!" );
                    console.log( "Error: " + errorThrown );
                    console.log( "Status: " + status );
                    console.dir(xhr);
                return;
                });
           });
       });
    </script>

</html>

