class EnemyPlayer {
    constructor(id, index, cardCount) {
        this.id=id;
        this.index=index;
        this.cardCount = cardCount;
    }
}

class Player {
    constructor(controller, index, id) {
        this.hand = [];
        this.myController = controller;
        this.index = index;
        this.id=id;
    }

    // draw num_cards from souce
    draw(num_cards, source, sendSignal = true) {

        for (let c = 0; c < num_cards; c++) {
            let newCard = source[c];

            //Check source empty
            if (newCard == null) break;

            this.hand.push(newCard);

            if (sendSignal && (newCard.events[1][0] || newCard.events[1][1] || newCard.events[1][2]))
                this.myController.dispatchEvent(new CustomEvent('cardDrawn', {
                    detail: {
                        card: newCard,
                        player: this,
                        whereFrom: source
                    }
                }));
            this.myController.gm.newCard(newCard.name, newCard.suit);
        }
    }

    // draw num_cards from souce
    drawAndReturn(num_cards, source, sendSignal = true) {

        let cards = [];

        for (let c = 0; c < num_cards; c++) {
            let newCard = source.deal()[0];

            //Check source empty
            if (newCard == null) break;

            cards.push(newCard);

            this.hand.push(newCard);

            if (sendSignal && (newCard.events[1][0] || newCard.events[1][1] || newCard.events[1][2]))
                this.myController.dispatchEvent(new CustomEvent('cardDrawn', {
                    detail: {
                        card: newCard,
                        player: this,
                        whereFrom: source
                    }
                }));
        }

        return cards;
    }

    // deal a number cards
    deal(num_cards = 1, sendSignal = true) {

        let cards = [];
        let dealtCard;

        for (let c = 0; c < num_cards; c++) {
            dealtCard = this.hand.shift();
            if (dealtCard == null) break;
            cards.push(dealtCard);

            if (sendSignal && (dealtCard.events[0][0] || dealtCard.events[0][1] || dealtCard.events[0][2]))
                this.myController.dispatchEvent(new CustomEvent('cardPlayed', {
                    detail: {
                        card: dealtCard,
                        player: this
                    }
                }));
        }

        return cards
    }
}