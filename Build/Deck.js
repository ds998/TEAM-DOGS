class Deck {
    static defaultValues = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];
    static defaultSuits = ['Clubs', 'Diamonds', 'Spades', 'Hearts'];
    static types = {
        LIMITED: 1,
        INFINITE: 2,
        SHUFFLE_DISCARD: 3
    }

    constructor(controller, num_decks = 1, values = Deck.defaultValues, suits = Deck.defaultSuits, type = Deck.types.INFINITE) {
        this.values = values;
        this.suits = suits;
        this.num = num_decks;
        this.type = type;
        this.myController=controller;

        this.cards = [];
        this.dealt_cards = [];
    }

    // generates a deck of cards
    generate_deck() {

        // creates card generator function
        let card = (suit, value) => {
            let name = value + ' of ' + suit;
            //returns key and values into each instance of the this.cards array
            return {
                'name': name,
                'suit': suit,
                'value': value
            }
        }


        for (let s = 0; s < this.suits.length; s++) {
            for (let v = 0; v < this.values.length; v++) {
                for (let i = 0; i < this.num; i++)
                    this.cards.push(card(this.suits[s], this.values[v]));
            }
        }
    }

    // prints the deck of card objects
    print_deck() {
        if (this.cards.length === 0) {
            console.log('Deck has not been generated or is empty. Call generate_deck() on deck object before continuing.');
        } else {
            for (let c = 0; c < this.cards.length; c++) {
                console.log(this.cards[c]);
            }
        }
    }

    // shuffle the deck
    shuffle() {
        let temp;
        for (let c = this.cards.length - 1; c > 0; c--) {
            let rc = Math.floor(Math.random() * (c + 1));
            temp = this.cards[c];
            this.cards[c] = this.cards[rc];
            this.cards[rc] = temp;
        }
    }

    // deal a number cards
    deal(num_cards = 1) {

        let cards = []
        let dealt_card;
        let skip = false;

        if (num_cards == 0) num_cards = this.cards.length;

        for (let c = 0; c < num_cards; c++) {
            if (this.cards.length == 0) {
                switch (this.type) {
                    case Deck.types.LIMITED:
                        skip = true;
                        break;
                    case Deck.types.INFINITE:
                        this.generate_deck();
                        this.shuffle();
                        break;
                    case Deck.types.SHUFFLE_DISCARD:
                        this.add(this.myController.discard.deal(0));
                        this.shuffle();
                        break;
                }
            }

            if (skip) break;

            dealt_card = this.cards.shift();
            cards.push(dealt_card);
            this.dealt_cards.push(dealt_card);
        }

        return cards;
    }

    add(newCards) {
        for (let c = newCards.length - 1; c >= 0; c--)
            this.cards.push(newCards[c]);
    }

    // return last dealt card
    replace() {
        this.cards.unshift(this.dealt_cards.shift());
    }

    empty_deck() {
        this.cards = [];
    }

}