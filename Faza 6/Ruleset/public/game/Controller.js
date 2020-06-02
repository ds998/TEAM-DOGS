class Controller {
    static controller = null;

    static getController(rules, ids, playerId) {
        if (Controller.controller == null && rules != null && ids != null && playerId != null)
            Controller.controller = new Controller(rules, ids, playerId);
        return Controller.controller;
    }

    diff (diffMe, diffBy) {diffMe.split(diffBy).join('');}
    
    constructor(rules, ids, playerId) {

        //Ruleset
        this.ruleset = new Ruleset(rules, this);
        this.ruleset.addEventHandlers();

        //Deck
        this.deck = new Deck(0);
        this.lastData=null;

        //Discard pile
        this.discard = new Discard(-1, null);

        //Players
        this.curPlayer = 0;
        this.numPlayers = ids.length;
        this.enemyPlayers = [];
        this.claimed = false;
        for (let i = 0; i < this.numPlayers; i++)
            if (ids[i] == playerId)
                this.player = new Player(this, i, ids[i]);
            else
                this.enemyPlayers.push(new EnemyPlayer(ids[i], i, 0));

        if(this.curPlayer == this.player.index) this.claimed=true;

        //Event handler
        this.handler = new EventTarget();
        this.handler.addEventListener('cardDrawn', (e) => {
            this.ruleset.handleOnDrawEvent(e.detail);
        }, false);
        this.handler.addEventListener('cardPlayed', (e) => {
            this.ruleset.handleOnPlayEvent(e.detail);
        }, false);
    }

    startMe() {
        setInterval(this.updateState(), 200);
    }

    addGM(gm) {
        this.gm = gm;
    }

    dispatchEvent(event) {
        this.handler.dispatchEvent(event);
    }

    playerPlayCard(card, sendSignal = true) {

        let cardPlayed = this.player.deal(card, sendSignal);
        this.discard.top_card(cardPlayed);

        throwCard(this.player.id, card);
        this.turns_left--;
        if (this.turns_left) endTurn();
    }

    // TARGET FUNCTION IMPLEMENTATION
    // Return the player after the player sent as a paramater
    getNextPlayer() {
        return (this.player.index + 1) % this.numPlayers;
    }

    // Return the player after the player sent as a paramater
    getPreviousPlayer() {
        return (this.player.index - 1 + this.numPlayers) % this.numPlayers;
    }

    markSkip() {
        this.skip = true;
    }

    chooseOther() {
        //TO-DO
    }

    tryToPlay(card) {
        if (this.canPlay(card, this.discard.top_card)) {
            if (this.myTurn()) {
                this.playerPlayCard(card, true);
                return true;
            } else {
                claimTurn(this.player.id, card);
                return true; // <------------------------------------------------ Zapravo tek kad dobijemo odgovor znamo
            }
        }
        return false;
    }

    drawFromDeck(num_cards = 1) {
        if (this.myTurn()) draw(this.player.id, this.player.id, num_cards, this.deck.id);
    }

    myTurn() {
        return (this.curPlayer == this.player.index) && this.claimed;
    }

    canPlay(card, cardDest) {
        if (this.myMove && (card.suit == cardDest.suit || card.value == cardDest.value)) return true;
        else if (this.ruleset.canJumpIn(card, cardDest)) return true;
        else return false;
    }

    updateState() {
        update();
        //Neke provere, neki pozivi
    }

    updateMe(data) {
        this.curPlayer = data.curPlayer;
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
                parseRule(command);
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

                handleDrawCommands(idUserThrown, idUserAffected, numOfCards, idSource);
                break;
            case "skip":
                idUserThrown = parseInt(args[1]);
                idUserAffected = parseInt(args[2]);

                imaginaryFunctionSkip(idUserThrown, idUserAffected);
                break;
            case "view":
                idUserThrown = parseInt(args[1]);
                idSource = parseInt(args[2]);
                numOfCards = parseInt(args[3]);
            case "endTurn":
                imaginaryFunctionEndTurn();
                break;
            case "claimed":
                idUser = parseInt(args[1]);
                imaginaryFunctionClaimed(idUser);
                break;
            case "cgr":
                rule = args[1];
                newValue = args[2];
                imaginaryFunctionChangeGlobalRule(rule, newValue);
                break;
            case "throw":
                idUser = parseInt(args[1]);
                card = args[2];
                imaginaryFunctionThrow(idUser, card);
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
                    break;
                }
            }
            
        } else if (idUserAffected == this.player.id && idUserThrown != idUserAffected) {
            draw(this.player.id, this.player.id, numOfCards, idSource);
        }
    }
}