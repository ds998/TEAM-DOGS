class Rule {
    constructor(card, suit, trigger, type) {
        this.cardMatcher = new CardMatcher(card, suit);
        this.trigger = trigger;
        this.type = type;
    }
}

class DrawRule extends Rule {
    constructor (card, suit, trigger, type, target, target_can_be_cur, source, num_cards, counteraction) {
        super(card, suit, trigger, type);
        this.detail.target=target;
        this.detail.target_can_be_cur=target_can_be_cur;
        this.detail.source=source;
        this.detail.num_cards=num_cards;
        this.detail.counteraction=counteraction;
    }
}

class DrawUntilRule extends Rule {
    constructor (card, suit, trigger, type, target, target_can_be_cur, source, target_card, target_suit, counteraction) {
        super(card, suit, trigger, type);
        this.detail.target=target;
        this.detail.target_can_be_cur=target_can_be_cur;
        this.detail.source=source;
        this.detail.num_cards=num_cards;
        this.detail.counteraction=counteraction;
    }
}

