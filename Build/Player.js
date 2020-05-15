class Player {
    constructor() {
        this.hand = [];
    }

    // draw num_cards from souce
    draw(num_cards, source, sendSignal = true) {

        for (let c = 0; c < num_cards; c++) {
            let newCard = source.deal()[0];

            //Check source empty
            if (newCard == null) break; 

            this.hand.push(newCard);

            if (!sendSignal) continue;

            Controller.getController().dispatchEvent(new CustomEvent('cardDrawn', {
                detail: {
                    card: newCard,
                    player: this,
                    whereFrom: source
                }
            }));
        }
    }

    // deal a number cards
    deal(num_cards = 1, sendSignal = true) {

        let cards = []
        let dealtCard;

        for (let c = 0; c < num_cards; c++) {
            dealtCard = this.hand.shift();
            cards.push(dealtCard);

            if (!sendSignal) continue;

            Controller.getController().dispatchEvent(new CustomEvent('cardPlayed', {
                detail: {
                    card: dealtCard,
                    player: this
                }
            }));
        }

        return cards
    }

}