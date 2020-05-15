class Controller {
    static controller = null;

    static getController(numPlayers = null, rules = null) {
        if (Controller.controller == null && numPlayers != null && rules != null)
            Controller.controller = new Controller(numPlayers, rules);
        return Controller.controller;
    }

    constructor(numPlayers, rules) {
        //Deck
        this.deck = new Deck();
        this.deck.generate_deck();
        this.deck.shuffle();

        //Discard pile
        this.discardPile = new Deck();

        //Players
        this.numPlayers = numPlayers;
        this.players = [];
        for (let i = 0; i < this.numPlayers; i++)
            this.players.push(new Player());

        //Ruleset
        this.ruleset = new Ruleset(rules);
        this.ruleset.addEventHandlers();

        //Event handler
        this.handler = new EventTarget();
        this.handler.addEventListener('cardDrawn', (e) => {
            this.ruleset.handleOnDrawEvent(e.detail);
        }, false);
        this.handler.addEventListener('cardPlayed', (e) => {
            this.ruleset.handleOnPlayEvent(e.detail);
        }, false);
    }

    dispatchEvent(event) {this.handler.dispatchEvent(event);}

    //Deal a number of cards to all players
    dealToAllPlayers(num_cards, sendSignal = true) {
        this.players.forEach(player => {
            player.draw(num_cards, this.deck, sendSignal);
        });
    }

    //_DEBUG - Print all player hands to console
    printHands() {
        // this.players.forEach(player => {
        //     console.log(player.hand);
        // });

        for (let i=0;i<2;i++) {
            let hand='';
            this.players[i].hand.forEach(card => {
                hand += card.name + '\n';
            });

            if (i==0) $("#p1").val(hand);
            else $("#p2").val(hand);
        }
    }

    playerDrawCard(pl, num_cards = 1, sendSignal = true) {
        this.players[pl].draw(num_cards, this.deck,sendSignal);
    }

    playerPlayCard(pl, num_cards = 1, sendSignal = true) {
        let cardsPlayed = this.players[pl].deal(num_cards, sendSignal);
        if (cardsPlayed) this.deck.add(cardsPlayed);
    }

    getNextPlayer(player) {
        return this.players[(this.players.indexOf(player) + 1) % this.players.length];
    }
}