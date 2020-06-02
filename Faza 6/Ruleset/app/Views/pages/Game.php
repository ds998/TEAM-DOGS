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
    <script type="text/javascript">
                //setInterval(update, 1000); //kalibrisacemo milisek. kada bude vreme za to

                var myID = "<?php echo $idUser ?>";
                var idLobby = "<?php echo $idLobby ?>";
                var playerIDs = <?php echo json_encode($players) ?>;
                var cardNames = "<?php echo $deck->Cards ?>";
                cardNames = cardNames.split(',');
                var rules = "<?php echo $deck->cardRules ?>";

                console.log("myID: "+myID);

                function draw(idUser, idUser2, num, source) {
                    drawFunc(idUser, idUser2, num, source, idLobby).then((data) => {
                        if (idUser2 == myID) {
                            let cards = data.match(/.{1,2}/g);
                            for(let c=0; c<cards.length;c++) {
                                cards[c]=parseCard(cards[c]);
                            }
                            
                            let con = Controller.getController();
                            con.player.draw(cards.length, cards);
                        }
                        //ovde javascript kod za podatke(refreshovati innerhtml,tako nesto) koji se vracaju,znace Damjan sta da radi sa tim
                    });
                }

                async function drawFunc(idUser, idUser2, num, source, idLobby) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/draw/" + idUser + "/" +
                        idUser2 + "/" + num + "/" + source + "/" + idLobby, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };

                function skip(idUser, idUser2) {
                    skipFunc(idUser, idUser2, idLobby).then((data) => {
                        if (idUser2 == myID) Controller.getController().markSkip();
                        return; //zatraziti od Urosa da vraca nesto u backend php f-ji,recimo string done
                    });
                }

                async function skipFunc(idUser, idUser2, idLobby) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/skip/" + idUser + "/" +
                        idUser2 + "/" + idLobby, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };

                function viewCard(idUser, source, num) {
                    viewCardFunc(idUser, source, num, idLobby).then((data) => {
                        if (idUser2 == myID) {
                            let con = Controller.getController();
                            con.gm.display(data.cards);//Treba karte parsirati i napraviti od njih spriteove
                        }
                        //ovde javascript kod za podatke(refreshovati innerhtml,tako nesto) koji se vracaju,znace Damjan sta da radi sa tim
                    });
                }

                async function viewCardFunc(idUser, source, num, idLobby) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/viewCard/" + idUser +
                        "/" + source + "/" + num + "/" + idLobby, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };

                function update() {
                    updateFunc(idLobby).then((data) => {
                        Controller.getController().handleUpdate(data);
                    });
                }


                async function updateFunc(idLobby) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/update/" + idLobby, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        method: "GET",
                        mode: "cors"
                    });
                    var returnData = await response.json();
                    return returnData;
                };

                function myHand(idUser) {
                    myHandFunc(idUser).then((data) => {
                        let con = Controller.getController();
                        con.player.hand = data.cards;
                        con.gm.cards=data.cards;    //Treba konverzija
                        //ovde javascript kod za podatke(refreshovati innerhtml,tako nesto) koji se vracaju,znace Damjan sta da radi sa tim
                    });
                }


                async function myHandFunc(idUser) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/myHand/" + idUser, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        method: "GET",
                        mode: "cors"
                    });
                    var returnData = await response.json();
                    return returnData;
                };


                function claimTurn(idUser, card) {
                    claimTurnFunc(idUser, idLobby, card).then((data) => {
                        return JSON.parse(data);
                        //ovde javascript kod za podatke(refreshovati innerhtml,tako nesto) koji se vracaju,znace Damjan sta da radi sa tim
                    });
                }


                async function claimTurnFunc(idUser, idLobby, card) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/claimTurn/" + idUser +
                        "/" + idLobby + "/" + card, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };

                function changeGlobalRule(rule, newValue, card) {
                    cgrFunc(rule, newValue, card).then((data) => {

                        return;
                    });
                }


                async function cgrFunc(rule, newValue, card) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/changeGlobalRule/" +
                        rule + "/" + newValue + "/" + card, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };

                function throwf(idUser, card) {
                    throwFunc(idUser, card, idLobby).then((data) => {

                        return;
                    });
                }


                async function throwFunc(idUser, card, idlobby) {
                    var controller = "<?php echo $controller; ?>";
                    var response = await fetch("http://localhost:8080/" + controller + "/claimTurn/" + idUser +
                        "/" + card + "/" + idLobby, {
                            headers: {
                                'Content-Type': 'application/json',
                            'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
                    var returnData = await response.json();
                    return returnData;
                };
    </script>
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

        //var con = new Controller(rules, ids, idUser);
        var con = Controller.getController('', playerIDs, myID);

        window.addEventListener('resize', resizeGame, false);
        cardImg.onload = function () {
            gm = new GraphicsManager(con);
            con.addGM(gm);

            con.startMe();

            gm.newCard('Jack', 'Diamonds');
            gm.newCard('Ace', 'Spades');
            gm.newCard('7', 'Spades');
            gm.newCard('Cmar', 'Hearts');
            gm.newCard('Jack', 'Diamonds');
            gm.newCard('Ace', 'Spades');
            //     gm.newCard('7', 'Spades');
            //     gm.newCard('Cmar', 'Hearts');
            //     gm.newCard('Jack', 'Diamonds');
            //     gm.newCard('Ace', 'Spades');
            //     gm.newCard('7', 'Spades');
            //     gm.newCard('Cmar', 'Hearts');
        }
        cardImg.onerror= function () {
            console.log('ERROR-IMAGE IS AN ASSHOLE! src:' + cardImg.src);
        }

    });
</script>


</html>