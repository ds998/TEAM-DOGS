class Controller {
    static controller = null;

    static getController(numPlayers, rules, deck_template) {
        if (Controller.controller == null && numPlayers != null && rules != null && deck_template!=null)
            Controller.controller = new Controller(numPlayers, rules, deck_template);
        return Controller.controller;
    }

    constructor(numPlayers, rules, deck_template) {
        //Deck
        this.deck = new Deck(this, deck_template.num, deck_template.vales, deck_template.suits, deck_template.type);
        this.deck.generate_deck();
        this.deck.shuffle();

        this.discard = new Deck(this, deck_template.num, deck_template.vales, deck_template.suits, Deck.types.LIMITED);

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

        let deck='';
        this.discard.cards.forEach(card => {
            deck += card.name + '\n';
        });

        $("#discard").val(deck);

        deck='';
        this.deck.cards.forEach(card => {
            deck += card.name + '\n';
        });

        $("#deck").val(deck);
    }

    playerDrawCard(pl, num_cards = 1, sendSignal = true) {
        this.players[pl].draw(num_cards, this.deck,sendSignal);
    }

    playerPlayCard(pl, num_cards = 1, sendSignal = true) {
        let cardsPlayed = this.players[pl].deal(num_cards, sendSignal);
        if (cardsPlayed.length) this.discard.add(cardsPlayed);
    }


    // TARGET FUNCTION IMPLEMENTATION
    // Return the player after the player sent as a paramater
    getNextPlayer(player) {
        return this.players[(this.players.indexOf(player) + 1) % this.players.length];
    }

    // Return the player after the player sent as a paramater
    getPreviousPlayer(player) {
        return this.players[(this.players.indexOf(player) - 1 + this.players.length) % this.players.length];
    }

    chooseOther(player) {
        this.players[(this.players.indexOf(player) - 1 + this.players.length) % this.players.length];

        return this.players[(this.players.indexOf(player) - 1 + this.players.length) % this.players.length];
    }


}