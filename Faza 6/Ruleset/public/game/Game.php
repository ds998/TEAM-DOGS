<html>

<head>
    <title>GAME TEST</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('navbar/corgi_pixel.png'); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="<?php echo base_url('game/Rule.js'); ?>"></script>
    <script src="<?php echo base_url('game/TargetFunctions.js'); ?>"></script>
    <script src="<?php echo base_url('game/Deck.js'); ?>"></script>
    <script src="<?php echo base_url('game/Player.js'); ?>"></script>
    <script src="<?php echo base_url('game/Ruleset.js'); ?>"></script>
    <script src="<?php echo base_url('game/Controller.js'); ?>"></script>
    <script src="<?php echo base_url('game/graphics.js'); ?>"></script>
    <script src="<?php echo base_url('game/TestCases.js'); ?>"></script>

    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('game/Game.css'); ?>" />
</head>

<body>
    <div class="fluid-container">
        <div class="col-md-12">

            <canvas id="canvas" width="3200" height="1800">
                Sorry, your browser doesn't support the &lt;canvas&gt; element.
            </canvas>

        </div>
    </div>
</body>

<script type="text/javascript">
    $(document).ready(function () {

        var c = document.getElementById("canvas");
        var ctx = c.getContext("2d");

        var idUser = "<?php echo $idUser ?>";

        //var cont = new Controller(numPlayers, rules, ids, idUser);

        window.addEventListener('resize', resizeGame, false);
        cardImg.onload = function () {
            cm = new GraphicsManager(cont);
            cm.newCard('Jack', 'Diamonds');
            cm.newCard('Ace', 'Spades');
            cm.newCard('7', 'Spades');
            cm.newCard('Cmar', 'Hearts');
            cm.newCard('Jack', 'Diamonds');
            cm.newCard('Ace', 'Spades');
            //     cm.newCard('7', 'Spades');
            //     cm.newCard('Cmar', 'Hearts');
            //     cm.newCard('Jack', 'Diamonds');
            //     cm.newCard('Ace', 'Spades');
            //     cm.newCard('7', 'Spades');
            //     cm.newCard('Cmar', 'Hearts');
        }

    });
</script>


</html>