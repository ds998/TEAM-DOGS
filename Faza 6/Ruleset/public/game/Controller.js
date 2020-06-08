class Controller {
	static myController = null;

	static getController(rules) {
		if (Controller.myController == null && rules != null)
			Controller.myController = new Controller(rules);
		return Controller.myController;
	}

	diff (diffMe, diffBy) {return diffMe.split(diffBy).join('');}

	constructor(rules) {

		//Audio Controller
		this.ac=new AudioController();

		//Ruleset
		this.ruleset = new Ruleset(strToRules(rules, cardNames), this);
		this.ruleset.addEventHandlers();

		//Deck
		this.deck = new Deck(0);
		this.lastData=null;

		//Discard pile
		this.discard = new Discard(-1, null);

		//Global Rules
		this.turns_left=0;
		this.winCon=parseInt(globalRules[0], 10);
		this.order=parseInt(globalRules[4], 10);
		this.startingCards=parseInt(globalRules[5], 10);
		this.handLimit=parseInt(globalRules[6], 10);
		this.cardsPerTurn=parseInt(globalRules[7], 10);
		this.playsPerTurn=parseInt(globalRules[8], 10);
		this.cantPlay=parseInt(globalRules[9], 10);

		//Players
		this.curPlayer = 0;
		this.enemyPlayers = [];
		this.idMap = [];
		this.claimed = false;
		
		this.skip=false;
		this.chosenOne=null;

		//Event handler
		this.handler = new EventTarget();
		this.handler.addEventListener('cardDrawn', (e) => {
			this.ruleset.handleOnDrawEvent(e.detail);
		}, false);
		this.handler.addEventListener('cardPlayed', (e) => {
			this.ruleset.handleOnPlayEvent(e.detail);
		}, false);
	}

	startMe(ids, playerId, playerNames) {
		this.numPlayers = ids.length;
		for (let i = 0; i < this.numPlayers; i++)
			if (ids[i] == playerId)
				this.idMap[ids[i]] = this.player = new Player(this, i, ids[i], playerNames[i]);
			else {
				let newLen = this.enemyPlayers.push(new EnemyPlayer(ids[i], i, 0, playerNames[i]));
				this.idMap[ids[i]] = this.enemyPlayers[newLen-1];
			}

		if(this.curPlayer == this.player.index) {
			this.claimed=true;
			this.turns_left=this.playsPerTurn;
		}
		this.gm.updateActive(this.curPlayer);
		myHand(playerId);
		this.enemyPlayers.forEach(enemy => {
			myHand(enemy.id);
		});
		setInterval(function() {update()}, 100);
	}

	addGM(gm) {
		this.gm = gm;
	}

	dispatchEvent(event) {
		this.handler.dispatchEvent(event);
	}

	playerPlayCard(card, sendSignal = true) {
		this.ac.place();
		this.player.play(card, sendSignal);
		this.discard.top_card=card;

		throwf(this.player.id, unparseCard(card));
		if(this.winCon == 1 && this.player.hand.length == 0) this.endGame(this.player.id);
		this.turns_left--;
		if (!this.turns_left) this.endTurn();
	}

	// TARGET FUNCTION IMPLEMENTATION
	// Return the player after the player sent as a paramater
	getNextPlayer() {
		let index = (this.player.index + 1) % this.numPlayers;
		if (this.player.index == index) return this.player.id;
		else for (let e=0; e<this.enemyPlayers.length; e++)
			if (this.enemyPlayers[e].index == index) return this.enemyPlayers[e].id;
	}

	// Return the player after the player sent as a paramater
	getPreviousPlayer() {
		let index = (this.player.index - 1 + this.numPlayers) % this.numPlayers;
		if (this.player.index == index) return this.player.id;
		else for (let e=0; e<this.enemyPlayers.length; e++)
			if (this.enemyPlayers[e].index == index) return this.enemyPlayers[e].id;
	}

	async markSkip() {
		this.skip = true;
		if (this.curPlayer==this.player.index && this.skip) {
			if (await claimTurn(this.player.id, 'null')) {
				this.endTurn();
			}
		}
	}

	async chooseOther() {
		this.gm.chooseCharacter();
		do {
			var promise = new Promise(function(resolve, reject) {
				setTimeout(() => {
					if (this.chosenOne!=null) resolve(false);
					else resolve(true);
				}, 500);
			  }.bind(this));
		} while (await promise);
		let index = (this.player.index + this.chosenOne+1) % this.numPlayers;
		this.chosenOne=null;
		for (let e=0; e<this.enemyPlayers.length; e++)
			if (this.enemyPlayers[e].index == index) return this.enemyPlayers[e].id;
	}

	tryToPlay(card) {
		return new Promise(async function(resolve, reject) {
			var success = false;
			if (this.canPlay(card, this.discard.top_card)) {
				if (this.claimed) {
					this.playerPlayCard(card, true);
					success = true;
				} else {
					if (await claimTurn(this.player.id, card)) {
						this.turns_left = this.playsPerTurn;
						this.claimed=true;
						this.playerPlayCard(card, true);
						success = true;
					}
				}
			}
		  if ( success ) {
			resolve(true);
		  } else {
			resolve(false);
		  }
		}.bind(this));
	}

	async drawFromDeck(num_cards = 1) {
		if (this.cantPlay == 3) return;
		if (this.myTurn()) {
			if (this.player.hand.length+num_cards> this.handLimit) {
				num_cards = this.handLimit - this.player.hand.length;
				if (num_cards < 1) return;
			}
			if (this.claimed) {
				draw(this.player.id, this.player.id, num_cards, this.deck.id);
				if (this.cantPlay == 1) this.endTurn();
			} else if (await claimTurn(this.player.id, 'null')) {
				this.turns_left = this.playsPerTurn;
				this.claimed=true;
				draw(this.player.id, this.player.id, num_cards, this.deck.id);
				if (this.cantPlay == 1) this.endTurn();
			}
		}
	}

	myTurn() {
		return this.curPlayer == this.player.index;
	}

	amPlaying() {
		return (this.myTurn() && this.claimed);
	}

	canPlay(card, cardDest) {
		if (this.myTurn() && (cardDest==null ||(card.suit == cardDest.suit || card.value == cardDest.value))) return true;
		else if (this.ruleset.canJumpIn(card, cardDest)) return true;
		else return false;
	}

	updateMe(data) {
		this.curPlayer = data.curPlayer;
		this.gm.updateActive(this.curPlayer);
		for (let i = 0; i < numPlayers; i++)
			if (i == this.player.index) continue
		else this.players[i].cardCount = data.cardCounts[i];
		this.discard.top_card = data.disacrdCard;

	}

	randomPlayerIndex(canBeMe = true) {
		let randIndex = Math.floor(Math.random() * Math.floor(this.numPlayers));
		while (!canBeMe && randIndex == this.player.index) {
			randIndex = Math.floor(Math.random() * Math.floor(this.numPlayers));
		}
		return randIndex;
	}

	endTurn() {
		this.skip=false;
		this.claimed=false;
		endTurn();
	}

	cardAt(n) {
		return this.player.hand[n];
	}

	handleUpdate(data) {
		if (data == this.lastData) return;
		let newCommands = this.diff(data, this.lastData);
		this.lastData=data;
		if (newCommands) {
			let commands = newCommands.split(';');

			commands.forEach(command => {
				this.parseRule(command);
			});
		}
	}

	parseRule(command) {
		let args = command.split(',');
		let idUserThrown, idUserAffected, numOfCards, idSource, rule, newValue, idUser, card;
		switch (args[0]) {
			case "draw":
				idUserThrown = parseInt(args[1]);
				idUserAffected = parseInt(args[2]);
				numOfCards = parseInt(args[3]);
				idSource = parseInt(args[4]);
				this.handleDrawCommands(idUserThrown, idUserAffected, numOfCards, idSource);
				break;
			case "drawUntil":
				idUserThrown = parseInt(args[1]);
				idUserAffected = parseInt(args[2]);
				card = args[3];
				let matchCode = args[4];
				this.handleDrawUntil(idUserThrown, idUserAffected, card, matchCode);
				break;
			case "skip":
				idUserThrown = parseInt(args[1]);
				idUserAffected = parseInt(args[2]);
				this.handleSkip(idUserThrown, idUserAffected);
				break;
			case "view":
				idUserThrown = parseInt(args[1]);
				idSource = parseInt(args[2]);
				numOfCards = parseInt(args[3]);
				break;
			case "endTurn":
				this.handleEndTurn();
				break;
			case "claimed":
				idUser = parseInt(args[1]);
				this.handleClaimed(idUser);
				break;
			case "cgr":
				rule = args[1];
				newValue = args[2];
				imaginaryFunctionChangeGlobalRule(rule, newValue);
				break;
			case "throw":
				idUser = parseInt(args[1]);
				card = args[2];
				this.handleThrow(idUser, card)
				break;
			default:
				break;
		}
	}

	handleDrawCommands(idUserThrown, idUserAffected, numOfCards, idSource) {
		if ( idUserAffected!=this.player.id && idUserThrown == idUserAffected) {
			//Draw
			for(let e=0; e<this.enemyPlayers.length; e++) {
				if (this.enemyPlayers[e].id == idUserThrown) {
					this.enemyPlayers[e].cardCount+=numOfCards;
					this.ac.draw();
					if (this.winCon == 2 && this.enemyPlayers[e].cardCount >= 20) this.endGame(this.enemyPlayers[e].id);
					break;
				}
			}

		} else if (idUserAffected == this.player.id && idUserThrown != idUserAffected) {
			if (this.player.hand.length+numOfCards> this.handLimit) {
				numOfCards = this.handLimit - this.player.hand.length;
				if (numOfCards < 1) return;
			}
			draw(this.player.id, this.player.id, numOfCards, idSource);
			if (this.winCon == 2 && this.player.hand.length+numOfCards >= 20) this.endGame(this.player.id);	
		} else if (idUserAffected == this.player.id) if (this.winCon == 2 && this.player.hand.length+numOfCards >= 20) this.endGame(this.player.id);
	}

	handleClaimed(idUser) {
		this.curPlayer=this.idMap[idUser].index;
		this.gm.updateActive(this.curPlayer);
	}

	async handleEndTurn() {
		if (!this.myTurn()) for (let e=0; e<this.enemyPlayers.length; e++)
		if (this.enemyPlayers[e].index == this.curPlayer) this.gm.enemyCards[(this.enemyPlayers[e].index-this.player.index + this.enemyPlayers.length) % (this.enemyPlayers.length+1)].setSkip(false);
		this.curPlayer=(this.curPlayer+this.numPlayers+this.order)%this.numPlayers;
		this.gm.updateActive(this.curPlayer);
		if (this.curPlayer==this.player.index && this.skip) {
			if (await claimTurn(this.player.id, 'null')) {
				this.endTurn();
			}
		}
	}

	handleThrow(idUser, card) {
		if (idUser != this.player.id) {
			this.idMap[idUser].cardCount--;
			this.ac.place();
			let xy = this.gm.getEnemyCenter((this.idMap[idUser].index-this.player.index + this.enemyPlayers.length) % (this.enemyPlayers.length+1));
			let cardInfo = this.ruleset.parseCard(card);
			this.gm.playAnimation(new CardSprite(cardInfo.name, cardInfo.suit), xy.x, xy.y);
			if (this.winCon == 1 && this.idMap[idUser].cardCount==0) this.endGame(idUser);
		}
		this.discard.top_card=this.ruleset.parseCard(card);
		this.gm.discardCard = new CardSprite(this.discard.top_card.name, this.discard.top_card.suit);
	}

	handleSkip(idUserThrown, idUserAffected) {
		if (idUserAffected == this.player.id) this.markSkip();
		else {
			this.gm.enemyCards[(this.idMap[idUserAffected].index-this.player.index + this.enemyPlayers.length) % (this.enemyPlayers.length+1)].setSkip(true);
		}
	}

	async handleDrawUntil(idUserThrown, idUserAffected, card, matchCode) {
		if (idUserAffected == myID) {
			let code = matchCode.split('-');
			let drawRule = new DrawUntilRule(0, 0, 0, 0, 0, 0, 0, code[0], code[1], false);

			let matcher = drawRule.detail.matcher;
			let startLen=this.player.hand.length
			let drawn=0;
			do {
				drawn++;
				if (startLen+drawn> this.handLimit) break;
				var newCard = await draw(myID, myID, 1, this.deck.id);
			} while (!matcher.isMatch(newCard[0]));
		}
	}

	endGame(idWinner) {
		this.gm.endGame();
		end_game(idWinner);
	}
}