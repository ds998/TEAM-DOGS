class Controller {
    static controller = null;

    static getController(numPlayers, rules, deck_template) {
        if (Controller.controller == null && numPlayers != null && rules != null && deck_template != null)
            Controller.controller = new Controller(numPlayers, rules, deck_template);
        return Controller.controller;
    }

    constructor(numPlayers, rules, ids, playerId) {

        //Ruleset
        this.ruleset = new Ruleset(rules, this);
        this.ruleset.addEventHandlers();

        //Deck
        this.deck = new Deck(ids[numPlayers]);
        
        //Discard pile
        this.discard = new Discard(ids[numPlayers+1], top_card);

        //Players
        this.curPlayer = 0;
        this.numPlayers = numPlayers;
        this.players = [];
        this.claimed=false;
        for (let i = 0; i < numPlayers; i++)
            if (ids[i] == playerId) {
                this.playerIndex = i;
                this.player=this.players[i] = new Player(this, ids[i]);
            } else {
                this.players[i] = new EnemyPlayer(ids[i]);
            }

        //Event handler
        this.handler = new EventTarget();
        this.handler.addEventListener('cardDrawn', (e) => {
            this.ruleset.handleOnDrawEvent(e.detail);
        }, false);
        this.handler.addEventListener('cardPlayed', (e) => {
            this.ruleset.handleOnPlayEvent(e.detail);
        }, false);

        setInterval(this.updateState(), 200);
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
        return (this.playerIndex+1)%this.numPlayers;
    }

    // Return the player after the player sent as a paramater
    getPreviousPlayer() {
        return (this.playerIndex-1+this.numPlayers)%this.numPlayers;
    }

    chooseOther() {
        //TO-DO
    }

    tryToPlay(card) {
        if (canPlay(card, this.discard.top_card)) {
            if (myTurn()) {
                playerPlayCard(card, true);
                return true;
            } else {
                claimTurn(this.player.id, card);
                return true; // <------------------------------------------------ Zapravo tek kad dobijemo odgovor znamo
            }
        }
        return false;
    }

    drawFromDeck(num_cards = 1) {
        if (myTurn()) draw(this.players[this.playerIndex].id, this.players[this.playerIndex].id, num_cards, this.deckId, null);
    }

    myTurn() {
        return (this.curPlayer == this.playerIndex) && this.claimed;
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
        this.curPlayer=data.curPlayer;
        for (let i = 0; i < numPlayers; i++)
            if (i == this.playerIndex) continue
            else this.players[i].cardCount=data.cardCounts[i];
        this.discard.top_card=data.disacrdCard;
            
    }

    randomPlayerIndex(canBeMe = true) {
        let randIndex=Math.floor(Math.random() * Math.floor(this.numPlayers));
        while (!canBeMe && randIndex==this.playerIndex) {
            randIndex=Math.floor(Math.random() * Math.floor(this.numPlayers));
        }
        return randIndex;
    }

    cardAt(n) {
        return this.player.hand[n];
    }
}