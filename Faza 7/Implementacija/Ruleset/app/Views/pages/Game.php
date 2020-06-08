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
		var playerNames = <?php echo json_encode($playerNames) ?>;
		var cardNames = "<?php echo $deck->Cards ?>";
		cardNames = cardNames.split(',');
		var rulesSTRING = "<?php echo $deck->cardRules ?>";
		var globalRules = "<?php echo $deck->globalRules ?>";
		globalRules = globalRules.split(';');

		console.log("myID: "+myID);

		function draw(idUser, idUser2, num, source) {
			return drawFunc(idUser, idUser2, num, source, idLobby).then((data) => {
				if (idUser2 == myID) {
					let con = Controller.getController();
					let cards = data.match(/.{1,2}/g);
					for(let c=0; c<cards.length;c++) {
						cards[c]=con.ruleset.parseCard(cards[c]);
					}
					
					con.player.draw(cards.length, cards);
					return cards;
				}
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

		function drawUntil(idUser, idUser2, card, matchCode) {
			return drawUntilFunc(idUser, idUser2, card, matchCode, idLobby).then((data) => {
			});
		}
		async function drawUntilFunc(idUser, idUser2, card, matchCode, idLobby) {
			var controller = "<?php echo $controller; ?>";
			await fetch("http://localhost:8080/" + controller + "/drawUntil/" + idUser + "/" +
				idUser2 + "/" + card + "/" + matchCode + "/" + idLobby, {
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					method: "GET",
					mode: "cors"
				});
			return 'Topcina';
		};

		function skip(idUser, idUser2) {
			skipFunc(idUser, idUser2, idLobby).then((data) => {
				if (idUser2 == myID) Controller.getController().markSkip();
				return;
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
				let con = Controller.getController();
				let cards = data.match(/.{1,2}/g);
				for(let c=0; c<cards.length;c++) {
					cards[c]=con.ruleset.parseCard(cards[c]);
				}
				
				con.gm.displayCards(cards);
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
				if (idUser == con.player.id) {
					if (!data || data.length == 0) {
						draw(con.player.id, con.player.id, con.startingCards, con.deck.id);
					} else {
						let cards = data.match(/.{1,2}/g);
						for(let c=0; c<cards.length;c++) {
							cards[c]=con.ruleset.parseCard(cards[c]);
							con.gm.newCard(cards[c].name, cards[c].suit);
						}
						con.player.hand=cards;
					}
				}
				else {
					if (data != null) con.idMap[idUser].cardCount=data.length/2;
				}
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
			return claimTurnFunc(idUser, idLobby, card).then((data) => {
				return JSON.parse(data);
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
			var response = await fetch("http://localhost:8080/" + controller + "/throw/" + idUser +
				"/" + card + "/" + idLobby, {
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					method: "GET",
					mode: "cors"
				});
			return 'topcina';
		};

		function end_game(idWinner) {
			var controller = "<?php echo $controller; ?>";
			location.href="http://localhost:8080/"+ controller + "/end_game/" + idLobby + "/" + idWinner;
			return;
		}
		function endTurn() {
			endTurnFunc(idLobby).then((data) => {
			});
		}
		async function endTurnFunc(idLobby) {
			var controller = "<?php echo $controller; ?>";
			var response = await fetch("http://localhost:8080/" + controller + "/endTurn/" + idLobby, {
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					method: "GET",
					mode: "cors"
				});
			return 'topcina';
		};
    </script>
    <script src="<?php echo base_url('game/Rule.js'); ?>"></script>
    <script src="<?php echo base_url('game/RuleObjects.js'); ?>"></script>
    <script src="<?php echo base_url('game/TargetFunctions.js'); ?>"></script>
    <script src="<?php echo base_url('game/Deck.js'); ?>"></script>
    <script src="<?php echo base_url('game/Player.js'); ?>"></script>
    <script src="<?php echo base_url('game/Ruleset.js'); ?>"></script>
	<script src="<?php echo base_url('game/AudioController.js'); ?>"></script>
    <script src="<?php echo base_url('game/Controller.js'); ?>"></script>
    <script src="<?php echo base_url('game/graphics.js'); ?>"></script>

    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css') ?>" />
	<link rel="stylesheet" href="<?php echo base_url('game/Game.css'); ?>" />
	<link rel="stylesheet" href="<?php echo base_url('cet/cet.css'); ?>" />
</head>

<body>
    <div class="fluid-container">
	</br>
		<div class="row">
			<div class="col-0 col-lg-1"></div>
			<div class="col-lg-10">

				<canvas id="canvas" width="3200" height="1800">
					Sorry, your browser doesn't support the &lt;canvas&gt; element.
				</canvas>

			</div>
		</div>
	</div>
	<div id = "chat_div">
		<div id = "display_messages"></div>
		<div id = "text_area_div">
			<input type="text" class="form-control customInput mediumInput" id = "enter_message_area" placeholder="Enter Message" onkeydown="if(event.keyCode==13) {send_message();}">
		</div>
	</div>

	<script type="text/javascript">
	setInterval(chat_update,300);
    $(document).ready(function () {

        var c = document.getElementById("canvas");
        var ctx = c.getContext("2d");

        //var con = new Controller(rules, ids, idUser);
        var con = Controller.getController(rulesSTRING);

        cardImg.onload = function () {
            gm = new GraphicsManager(con);
            con.addGM(gm);

            con.startMe(playerIDs, myID, playerNames);
        }
        cardImg.onerror= function () {
            console.log('ERROR-IMAGE IS AN ASSHOLE! src:' + cardImg.src);
        }

    });

    
    function send_message() {
        var text_area = document.getElementById("enter_message_area");
        var new_message = text_area.value;
		if(new_message.length>34)return;
        text_area.value = "";
        var controller = "<?php echo $controller; ?>";
        var idLobby = "<?php echo $idLobby; ?>";
        send_message_func(new_message, controller, idLobby).then((data) => {return;});
    }

    async function send_message_func(message, controller, idLobby) {
        var response = await fetch("http://localhost:8080/" + controller + "/send_message/" + idLobby + "/" + message, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
        var returnData = await response;
        return "done";
    }

    function chat_update() {
        var controller = "<?php echo $controller; ?>";
        var idLobby = "<?php echo $idLobby; ?>";
        chat_update_func(controller, idLobby).then((data) => {
            var div_display = document.getElementById('display_messages');
            var str = "";
            for (var i = 0; i < data.length; i++) {
                str += data[i]+"<br>";
            }
            div_display.innerHTML = str;
        });
    }

    async function chat_update_func(controller, idLobby) {
        var response = await fetch("http://localhost:8080/" + controller + "/chat_update/" + idLobby, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            method: "GET",
                            mode: "cors"
                        });
        var returnData = await response.json();
        return returnData;
    }
</script>

</body>




</html>