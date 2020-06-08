<html>

<head>
    <title>Ruleset by team DOGS</title>
    <meta charset="UTF-8">
    <!--<link rel ="shortcut icon" type="image/png" href="<?php echo base_url('assets/navbar/corgi_pixel.png'); ?>">-->
    <link rel="stylesheet" href="<?php echo base_url('endgame_screen/endgame_screen.css'); ?>" /><!--<link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />-->
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" /><!--<link rel="stylesheet" href="" />-->
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>
</head>

<body>
    <div id = "table_div">
        <table id = "table_tag">
            <thead>
                <tr>
                    <th colspan ="3" id = "table_header"><?php echo $winner." has won!";?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td><td class = "cell label_text">Ruleset played</td><td></td>
                </tr>
                <tr>
                    <td></td><td id = "ruleset_played" class = "cell">RulesetName</td><td></td>
                </tr>
                <tr>
                    <td></td><td class = "cell label_text" id = "rating_label">Rate the Deck</td><td></td>
                </tr>
                <tr>
                    <td></td>
                    <td id = "rating" class = "cell">
                        <div class="rate">
                            <input class = "star_class" type="radio" id="star5" name="rate" value="5" />
                            <label for="star5">5 stars</label>
                            <input class = "star_class" type="radio" id="star4" name="rate" value="4" />
                            <label for="star4">4 stars</label>
                            <input class = "star_class" type="radio" id="star3" name="rate" value="3" />
                            <label for="star3">3 stars</label>
                            <input class = "star_class" type="radio" id="star2" name="rate" value="2" />
                            <label for="star2">2 stars</label>
                            <input class = "star_class" type="radio" id="star1" name="rate" value="1" />
                            <label for="star1">1 star</label>
                          </div>
                        </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script>
    setTimeout(redir_func,10000);

    function redir_func(){
        var rating=0;
        if(document.getElementById('star1').checked){
            rating=1;
        }
        if(document.getElementById('star2').checked){
            rating=2;
        }
        if(document.getElementById('star3').checked){
            rating=3;
        }
        if(document.getElementById('star4').checked){
            rating=4;
        }
        if(document.getElementById('star5').checked){
            rating=5;
        }
        var controller="<?php echo $controller; ?>";
        var idLobby="<?php echo $idLobby; ?>";
        location.href="http://localhost:8080/"+controller+"/back_to_lobby/"+idLobby+"/"+rating;
        return;
    }
    

   
</script>

</html>